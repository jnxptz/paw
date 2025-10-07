<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
	public function index()
	{
		// Always show landing page, regardless of auth state
		return view('landing', ['page' => 'home']);
	}
}
