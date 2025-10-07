@extends('layouts.app')

@section('title', 'PawTulong | Home')

@php
    $layoutCss = 'landing.css';
    $page = 'home';
@endphp

@section('content')
<main>
    <section class="hero">
        <div class="hero-text">
            <h1>LOVE YOUR<br />PET MORE</h1>
            <p>Your Trusted Partner in Pet Care, Ensuring<br />the Health and Well-Being of Your<br />Beloved Canine and Feline Companions</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('img/8.png') }}" alt="Cute Puppy in Location Marker" />
        </div>
    </section>
</main>
@endsection
