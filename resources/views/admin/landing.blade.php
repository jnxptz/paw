@extends('layouts.app')

@section('title', 'Admin | Landing')

@php
    $layoutCss = 'landing.css';
    $page = 'home';
@endphp

@section('content')
<main>
    <section class="hero">
        <div class="hero-text">
            <h1>Welcome, Admin</h1>
            <p>You are on the landing page. Use the dropdown for admin actions.</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('img/8.png') }}" alt="Paw" />
        </div>
    </section>
</main>
@endsection


