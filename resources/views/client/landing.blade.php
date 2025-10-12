@extends('layouts.app')

@section('title', 'PawTulong | Client Landing')

@php
    $layoutCss = 'landing.css';
    $page = 'home';
@endphp

@section('content')
<div class="client-landing-container">

    {{-- HERO SECTION --}}
    <section class="hero">
        <div class="hero-text">
            <h1>Welcome, {{ $user->username }}!</h1>
            <p>
                Your pet’s health is our priority. Chat with our PawTulong AI or explore common questions to keep your furry friend happy and healthy.
            </p>
            <a href="{{ route('chatbot') }}" class="btn btn-primary">Start Chatting</a>
        </div>

        <div class="hero-image">
            <img src="{{ asset('img/8.png') }}" alt="Happy Pet">
        </div>
    </section>

    {{-- CONTENT SECTION --}}
    <section class="content-section">
        <div class="dashboard-grid">

            {{-- Frequently Asked Questions --}}
            <div class="card shadow">
                <div class="card-header faq-header">💡 Frequently Asked Questions</div>
                <div class="card-body">
                    <ul class="card-list">
                        @forelse($mostAsked as $q)
                            <li><a href="#"><i class="fas fa-comments"></i> {{ $q }}</a></li>
                        @empty
                            <li>No questions available yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Recent Conversations --}}
            <div class="card shadow">
                <div class="card-header conv-header">Your Last Conversations</div>
                <div class="card-body">
                    <ul class="card-list">
                        @forelse($recentConversations as $conv)
                            <li>
                                <a href="{{ route('chatbot.show', $conv->id) }}">
                                    <div><strong>Q:</strong> {{ $conv->question }}</div>
                                    <div><strong>A:</strong> {{ $conv->answer ?? '— Unanswered —' }}</div>
                                    <div class="timestamp">{{ $conv->created_at->format('M d, Y h:i A') }}</div>
                                </a>
                            </li>
                        @empty
                            <li>No recent conversations yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection
