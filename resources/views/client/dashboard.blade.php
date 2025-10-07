@extends('layouts.app')

@section('title', 'Client Profile | PawTulong')

@php
    $layoutCss = 'admin_products.css';
    $layoutJs = 'client.js';
    $page = 'profile';
@endphp

@section('content')
<h1 class="page-title">Profile</h1>

<div class="products-section" style="display:flex;justify-content:center;align-items:center;margin-bottom:40px;">
    <div style="background:#fff;border-radius:15px;box-shadow:0 4px 16px rgba(0,0,0,0.1);padding:24px;width:100%;max-width:600px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
            <i class="fas fa-user-circle" style="font-size:40px;color:#6b4a6b;"></i>
            <div>
                <div style="font-size:1.25rem;font-weight:700;">{{ $user->username }}</div>
                <a href="mailto:{{ $user->email }}" style="color:#6b4a6b;text-decoration:underline;">{{ $user->email }}</a>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:220px 1fr;row-gap:10px;column-gap:24px;">
            <div style="font-weight:700;color:#231f20;">Name</div>
            <div>{{ $user->username }}</div>
            <div style="font-weight:700;color:#231f20;">Email account</div>
            <div><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></div>
            <div style="font-weight:700;color:#231f20;">User Type</div>
            <div>{{ ucfirst($user->user_type) }}</div>
            <div style="font-weight:700;color:#231f20;">Account Created</div>
            <div>{{ $user->created_at->format('M d, Y') }}</div>
        </div>
       

    </div>
</div>
{{-- ================= Dashboard Cards ================= --}}
<div class="products-section" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">
    
    {{-- Frequently Asked Questions --}}
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




    {{-- Recent Conversations --}}
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




@endsection

@section('scripts')
<script>
    const modal = document.getElementById('editProfileModal');
    const openBtn = document.getElementById('editProfileBtn');
    const closeBtn = document.getElementById('closeModal');

    openBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside the content
    window.addEventListener('click', (e) => {
        if(e.target === modal) modal.style.display = 'none';
    });
</script>
@endsection
