<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientLandingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch data for the landing page
        $mostAsked = []; // Replace with your logic to fetch frequently asked questions
        $recentConversations = []; // Replace with logic to fetch last 10 conversations

        return view('client.landing', compact('user', 'mostAsked', 'recentConversations'));
    }
}
