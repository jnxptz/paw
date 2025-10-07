@extends('layouts.app')

@section('title', 'PawTulong | Welcome')

@php
    $layoutCss = 'landing.css';
    $page = 'public_home';
@endphp

@section('content')
<main>
    <section class="hero" style="display:flex; justify-content:center; align-items:center; height:100vh; background:#f9f9f9;">
        <div class="hero-container" style="text-align:center; max-width:700px;">
            <h1 style="font-size:3rem; font-weight:bold; margin-bottom:1rem;">LOVE YOUR<br>PET MORE</h1>
            <p style="font-size:1.2rem; line-height:1.5; margin-bottom:2rem;">
                Your Trusted Partner in Pet Care, Ensuring<br>
                the Health and Well-Being of Your<br>
                Beloved Canine and Feline Companions
            </p>

            <!-- Dropdown Sign In / Sign Up -->
            <div class="dropdown mb-4">
                <button class="btn btn-primary dropdown-toggle" type="button" id="authDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Get Started
                </button>
                <ul class="dropdown-menu" aria-labelledby="authDropdown">
                    <li><a class="dropdown-item" href="{{ route('login.form') }}">Sign In</a></li>
                    <li><a class="dropdown-item" href="{{ route('register.form') }}">Sign Up</a></li>
                </ul>
            </div>

            <!-- Hero Image -->
            <div class="hero-image mt-4">
                <img src="{{ asset('img/8.png') }}" alt="Cute Puppy in Location Marker" style="max-width:100%; height:auto; border-radius:15px;">
            </div>
        </div>
    </section>
</main>
@endsection
