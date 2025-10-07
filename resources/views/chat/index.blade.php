@extends('layouts.app')

@section('title', 'Chatbot | PawTulong')

@section('content')
<div class="products-section" style="display:flex;justify-content:center;align-items:flex-start;margin-top:40px;margin-bottom:40px;">
    <div class="card shadow" style="background:#fff;border-radius:15px;width:100%;max-width:600px;display:flex;flex-direction:column;overflow:hidden;">
        
        {{-- Chat Header --}}
        <div class="card-header" style="padding:16px;background:#e6b6d6;font-weight:700;font-size:1.2rem;">
            🤖 Chatbot
        </div>

        {{-- Chat Body --}}
        <div id="chat-box" style="flex:1;overflow-y:auto;padding:16px;min-height:300px;max-height:400px;background:#f9f9f9;">
            @if(isset($conversation) && count($conversation))
                @foreach($conversation as $msg)
                    {{-- User Message --}}
                    <div style="display:flex;justify-content:flex-end;margin-bottom:8px;">
                        <div style="background:#d1e7dd;padding:10px 14px;border-radius:12px;max-width:70%;">
                            <strong>You:</strong> {{ $msg->question }}
                        </div>
                    </div>
                    
                    {{-- Bot Reply --}}
                    <div style="display:flex;justify-content:flex-start;margin-bottom:12px;">
                        <div style="background:#f8d7da;padding:10px 14px;border-radius:12px;max-width:70%;">
                            <strong>Bot:</strong> {{ $msg->answer }}
                        </div>
                    </div>
                @endforeach
            @else
                <div style="color:#6c757d;">Start a new conversation!</div>
            @endif
        </div>

        {{-- Input Area --}}
        <form id="chat-form" style="display:flex;padding:16px;border-top:1px solid #eee;background:#fff;">
            <input type="text" name="message" id="chat-input" 
                style="flex:1;padding:10px 14px;border:1px solid #ddd;border-radius:8px;margin-right:12px;font-size:0.95rem;"
                placeholder="Type your message..." autocomplete="off">
            <button type="submit" 
                style="padding:10px 16px;background:#6b4a6b;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;transition:0.2s;">
                Send
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const form = document.getElementById('chat-form');
const input = document.getElementById('chat-input');
const chatBox = document.getElementById('chat-box');

// Scroll to bottom on load
chatBox.scrollTop = chatBox.scrollHeight;

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = input.value.trim();
    if(!msg) return;

    // Append user message bubble
    chatBox.innerHTML += `
        <div style="display:flex;justify-content:flex-end;margin-bottom:8px;">
            <div style="background:#d1e7dd;padding:10px 14px;border-radius:12px;max-width:70%;">
                <strong>You:</strong> ${msg}
            </div>
        </div>
    `;
    input.value = '';
    chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });

    try {
        const res = await fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({message: msg})
        });

        const data = await res.json();

        // Append bot reply bubble
        chatBox.innerHTML += `
            <div style="display:flex;justify-content:flex-start;margin-bottom:12px;">
                <div style="background:#f8d7da;padding:10px 14px;border-radius:12px;max-width:70%;">
                    <strong>Bot:</strong> ${data.reply}
                </div>
            </div>
        `;
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });

    } catch (err) {
        chatBox.innerHTML += `<div style="color:red;">⚠️ Error sending message</div>`;
    }
});
</script>
@endsection
