<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ChatLog;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    // Admin dashboard
    public function index(Request $request)
    {
        $user = Auth::user();

        $totalChats      = ChatLog::count();
        $totalUnanswered = ChatLog::whereNull('answer')->count();
        $totalUsers      = User::count();

        $mostAsked = ChatLog::select('question', DB::raw('COUNT(*) as count'))
            ->groupBy('question')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        $topUsers = ChatLog::select('user_id', DB::raw('COUNT(*) as conversations_count'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('conversations_count')
            ->limit(20)
            ->get();

        $range = (int) $request->get('range', 7);
        $start = Carbon::today()->subDays($range - 1);
        $end   = Carbon::today();

        $rawTrends = ChatLog::whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $trendData = [];
        $previous = null;

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            $count = (int) ($rawTrends[$key] ?? 0);
            $change = $previous !== null ? $count - $previous : '—';

            $trendData[] = [
                'date'   => $d->format('M d'),
                'count'  => $count,
                'change' => is_numeric($change) ? ($change >= 0 ? '+' . $change : (string)$change) : $change,
            ];

            $previous = $count;
        }

        return view('admin.dashboard', compact(
            'user',
            'totalChats',
            'totalUnanswered',
            'totalUsers',
            'mostAsked',
            'topUsers',
            'trendData'
        ));
    }

    // Admin profile page
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    // Update profile info
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $user->update(['username' => $request->username]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    // Change admin password
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    // Admin logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

    // Export CSV
    public function export(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'top_questions':
                $data = ChatLog::select('question', DB::raw('COUNT(*) as count'))
                    ->groupBy('question')->orderByDesc('count')->get();
                $filename = 'top_questions.csv';
                $headers = ['Question', 'Count'];
                break;

            case 'top_users':
                $data = ChatLog::select('user_id', DB::raw('COUNT(*) as conversations_count'))
                    ->with('user')->groupBy('user_id')->orderByDesc('conversations_count')->get();
                $filename = 'top_users.csv';
                $headers = ['User', 'Conversations'];
                break;

            case 'trends':
                $start = Carbon::today()->subDays(6);
                $data = ChatLog::where('created_at', '>=', $start)
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                    ->groupBy('date')->orderBy('date')->get();
                $filename = 'trends.csv';
                $headers = ['Date', 'Questions'];
                break;

            default:
                return back()->with('error', 'Invalid export type.');
        }

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($data as $row) {
                if (isset($row->user)) {
                    fputcsv($file, [$row->user->username ?? 'Unknown', $row->conversations_count]);
                } elseif (isset($row->question)) {
                    fputcsv($file, [$row->question, $row->count]);
                } else {
                    fputcsv($file, [$row->date, $row->total]);
                }
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ]);
    }
}
