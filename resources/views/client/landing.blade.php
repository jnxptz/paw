@extends('layouts.app')

@section('title', 'PawTulong | Client Landing')

@php
    $layoutCss = 'landing.css';
    $page = 'home';
@endphp

@section('content')
<div class="client-landing-container" style="padding:40px 20px;">

    

    {{-- ⚙️ Dashboard Grid --}}
    <div style="
        display:flex;
        justify-content:center;
        flex-wrap:wrap;
        gap:24px;
    ">

        {{-- 💬 Frequently Asked Questions --}}
        <div style="
            background:#fff;
            border:1px solid #eee;
            border-radius:14px;
            width:350px;
            padding:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        ">
            <h3 style="margin:0 0 14px;text-align:center;color:#6b4a6b;">
                💡 Frequently Asked Questions
            </h3>
            <ul style="list-style:none;padding:0;margin:0;max-height:320px;overflow-y:auto;">
                @forelse($mostAsked as $q)
                    <li style="
                        padding:10px 12px;
                        margin-bottom:8px;
                        border:1px solid #eee;
                        border-radius:8px;
                        background:#fafafa;
                        font-size:0.9rem;
                        line-height:1.4;
                        cursor:default;
                    ">
                        <i class="fas fa-question-circle" style="color:#6b4a6b;margin-right:6px;"></i>
                        {{ $q }}
                    </li>
                @empty
                    <li style="text-align:center;color:#999;">No questions available yet.</li>
                @endforelse
            </ul>
        </div>

        {{-- 📝 Recent Conversations --}}
        <div style="
            background:#fff;
            border:1px solid #eee;
            border-radius:14px;
            width:500px;
            padding:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        ">
            <h3 style="margin:0 0 14px;text-align:center;color:#28a745;">
                📝 Your Last 10 Conversations
            </h3>
            <ul style="list-style:none;padding:0;margin:0;max-height:320px;overflow-y:auto;">
                @forelse($recentConversations as $conv)
                    <li style="
                        padding:10px 12px;
                        margin-bottom:8px;
                        border:1px solid #eee;
                        border-radius:8px;
                        background:#fafafa;
                        font-size:0.9rem;
                        line-height:1.4;
                    ">
                        <div><strong>Q:</strong> {{ $conv->question }}</div>
                        <div style="margin-top:4px;">
                            <strong>A:</strong> {{ $conv->answer ?? '— Unanswered —' }}
                        </div>
                        <div style="margin-top:4px;font-size:0.8rem;color:#6c757d;text-align:right;">
                            {{ $conv->created_at->format('M d, Y h:i A') }}
                        </div>
                    </li>
                @empty
                    <li style="text-align:center;color:#999;">No recent conversations yet.</li>
                @endforelse
            </ul>
        </div>

    </div>
</div>
@endsection
