<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatLog;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

       
        // General most-asked questions FOR THIS USER only (top 5)
            $mostAsked = ChatLog::where('user_id', $user->id)
            ->select('question')
            ->groupBy('question')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(20)
            ->pluck('question');


        // Recent conversations of this client (last 10)
        $recentConversations = ChatLog::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        return view('client.dashboard', compact('user', 'mostAsked', 'recentConversations'));
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
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

public function showChangePasswordForm()
{
    return view('client.change-password');
}


}
