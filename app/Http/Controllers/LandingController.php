<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function __construct()
    {
        // Require authentication
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // ✅ Prepare data based on user type
        // Weekly trends (for both admin and client)
        $start = Carbon::today()->subDays(6);
        $end = Carbon::today();
        $rawTrends = ChatLog::whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $trendData = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            $trendData->push([
                'label' => $d->format('M d'),
                'value' => (int)($rawTrends[$key] ?? 0)
            ]);
        }
        $trendLabels = $trendData->pluck('label');
        $trendSeries = $trendData->pluck('value');

        if ($user->user_type === 'admin') {
            // Admin dashboard data
            $totalQuestions = ChatLog::count();
            $totalUsers = User::count();
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
            return view('landing', [
                'page' => 'home',
                'totalQuestions' => $totalQuestions,
                'totalUsers' => $totalUsers,
                'mostAsked' => $mostAsked,
                'topUsers' => $topUsers,
                'trendLabels' => $trendLabels,
                'trendSeries' => $trendSeries
            ]);
        } else {
            // Client landing view (hero + client cards)
            $recentConversations = ChatLog::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            // Show ALL users' questions globally (distinct, ordered by frequency)
            $mostAsked = ChatLog::select('question', DB::raw('COUNT(*) as count'))
                ->groupBy('question')
                ->orderByDesc('count')
                ->pluck('question');
            return view('client.landing', compact('user', 'recentConversations', 'mostAsked'));
        }
    }
}