@extends('layouts.app')

@section('title', 'PawTulong | Home')

@section('content')
<main>
    <div class="hero">
        <div class="hero-text">
            <h1>LOVE YOUR PET MORE</h1>
            <p>
                Your Trusted Partner in Pet Care, Ensuring the Health and 
                Well-Being of Your Beloved Canine and Feline Companions
            </p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('img/puppy-location.png') }}" alt="Cute Puppy in Location Marker">
        </div>
    </div>
</main>
@endsection
