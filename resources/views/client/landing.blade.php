@extends('layouts.app')

@section('title', 'PawTulong | Client Landing')

@php
    $layoutCss = 'landing.css';
    $page = 'home';
@endphp

@section('content')
<div class="client-landing-container">

    

    {{-- CONTENT SECTION --}}
    <section class="content-section" style="padding-top:80px;">
        <div class="dashboard-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">

            {{-- Frequently Asked Questions (match Client Profile styles) --}}
            <div class="card shadow" style="background:#fff;border-radius:15px;overflow:hidden;min-height:300px;max-height:400px;display:flex;flex-direction:column;">
                <div class="card-header" style="padding:12px 16px;background:#e6b6d6;font-weight:700;">
                    💡 Frequently Asked Questions
                </div>
                <div class="card-body" style="padding:16px;overflow-y:auto;flex:1;">
                    <ul style="margin:0;padding-left:0;list-style:none;">
                        @forelse($mostAsked as $q)
                            <li style="margin-bottom:10px;">
                                <a href="#" style="display:block;padding:10px 12px;border:1px solid #eee;border-radius:8px;background:#f9f9f9;color:#333;text-decoration:none;transition:0.2s;">
                                    <i class="fas fa-comments" style="margin-right:6px;color:#6b4a6b;"></i>
                                    {{ $q }}
                                </a>
                            </li>
                        @empty
                            <li>No questions available yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Recent Conversations (match Client Profile styles) --}}
            <div class="card shadow" style="background:#fff;border-radius:15px;overflow:hidden;min-height:300px;max-height:400px;display:flex;flex-direction:column;">
                <div class="card-header" style="padding:12px 16px;background:#d4edda;font-weight:700;">
                    📝 Your Last 10 Conversations
                </div>
                <div class="card-body" style="padding:16px;overflow-y:auto;flex:1;">
                    <ul style="margin:0;padding-left:0;list-style:none;">
                        @forelse($recentConversations as $conv)
                            <li style="margin-bottom:10px;">
                                <a href="{{ route('chatbot.show', $conv->id) }}" 
                                   style="display:block;padding:10px 12px;border:1px solid #eee;border-radius:8px;background:#f9f9f9;color:#333;text-decoration:none;transition:0.2s;">
                                    <div><strong>Q:</strong> {{ $conv->question }}</div>
                                    <div style="margin-top:4px;"><strong>A:</strong> {{ $conv->answer ?? '— Unanswered —' }}</div>
                                    <div style="margin-top:4px;font-size:0.8rem;color:#6c757d;">
                                        {{ $conv->created_at->format('M d, Y h:i A') }}
                                    </div>
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
