<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response; // ✅ Correct place
use App\Models\ChatLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Dashboard Summary
        $totalQuestions   = ChatLog::count();
        $totalUnanswered  = ChatLog::whereNull('answer')->count();
        $totalUsers       = User::count();

        // Most Asked Questions (top 20)
        $mostAsked = ChatLog::select('question', DB::raw('COUNT(*) as count'))
            ->groupBy('question')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        // Top Users (top 20)
        $topUsers = ChatLog::select('user_id', DB::raw('COUNT(*) as conversations_count'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('conversations_count')
            ->limit(20)
            ->get();

        // Daily Trends for last N days
        $range = $request->get('range', 7); // default last 7 days
        $start = Carbon::today()->subDays($range - 1);
        $end   = Carbon::today();

        $rawTrends = ChatLog::whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $trendLabels = [];
        $trendSeries = [];

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            $trendLabels[] = $d->format('M d');
            $trendSeries[] = (int) ($rawTrends[$key] ?? 0);
        }

        return view('admin.dashboard', compact(
            'user',
            'totalQuestions',
            'totalUnanswered',
            'totalUsers',
            'mostAsked',
            'topUsers',
            'trendLabels',
            'trendSeries'
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }

    /**
     * Export CSV for top questions, top users, or trends.
     */
   
public function export(Request $request)
{
    $type = $request->get('type');

    switch ($type) {
        case 'top_questions':
            $data = ChatLog::select('question', DB::raw('COUNT(*) as count'))
                ->groupBy('question')
                ->orderByDesc('count')
                ->get();
            $filename = 'top_questions.csv';
            $headers = ['Question', 'Count'];
            break;

        case 'top_users':
            $data = ChatLog::select('user_id', DB::raw('COUNT(*) as conversations_count'))
                ->with('user')
                ->groupBy('user_id')
                ->orderByDesc('conversations_count')
                ->get();
            $filename = 'top_users.csv';
            $headers = ['User', 'Conversations'];
            break;

        case 'trends':
            $start = Carbon::today()->subDays(6);
            $rawTrends = ChatLog::where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            $data = $rawTrends;
            $filename = 'trends.csv';
            $headers = ['Date', 'Questions'];
            break;

        default:
            return redirect()->back()->with('error', 'Invalid export type.');
    }

    $callback = function() use ($data, $headers) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $headers);

        foreach ($data as $row) {
            if (isset($row->user)) {
                $rowData = [
                    $row->user->username ?? $row->user->email ?? 'Unknown',
                    $row->conversations_count
                ];
            } elseif (isset($row->question)) {
                $rowData = [$row->question, $row->count];
            } else {
                $rowData = [$row->date, $row->total];
            }
            fputcsv($file, $rowData);
        }

        fclose($file);
    };

    return Response::stream($callback, 200, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}",
    ]);
}
  public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
