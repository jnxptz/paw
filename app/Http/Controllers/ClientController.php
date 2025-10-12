<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatLog;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    // Client Dashboard (per-user data)
    public function index()
    {
        $user = Auth::user();

        // Recent conversations of this client (last 20)
        $recentConversations = ChatLog::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        // Most asked questions FOR THIS USER (top 20)
        $mostAsked = ChatLog::where('user_id', $user->id)
            ->select('question')
            ->groupBy('question')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(20)
            ->pluck('question');

        return view('client.dashboard', compact('user', 'mostAsked', 'recentConversations'));
    }

    // Client Landing (global data)
    public function landing()
    {
        $user = Auth::user();

        // Most asked questions globally (top 10)
        $mostAsked = ChatLog::select('question', DB::raw('COUNT(*) as count'))
            ->groupBy('question')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('question');

        // Recent conversations of this client (last 5)
        $recentConversations = ChatLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.landing', compact('user', 'mostAsked', 'recentConversations'));
    }

    // Edit profile
    public function edit()
    {
        $user = auth()->user();
        return view('client.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('username', 'email'));

        return redirect()->route('client.dashboard')->with('success', 'Profile updated!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

    // Show change password form
    public function showChangePasswordForm()
    {
        return view('client.change-password');
    }
}
