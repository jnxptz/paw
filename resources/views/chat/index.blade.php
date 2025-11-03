@extends('layouts.app')

@section('title', 'Chatbot | PawTulong')

@section('content')
<div style="display:flex; height:100vh; margin:0; padding:0; background:#fafafa; overflow:hidden; position:relative;">

    {{-- 🟣 Toggle Sidebar Button --}}
    <button id="toggle-sidebar" style="
        position:absolute;
        top:15px;
        left:15px;
        background:#6b4a6b;
        color:#fff;
        border:none;
        border-radius:6px;
        padding:8px 12px;
        cursor:pointer;
        z-index:2000;
        transition:0.3s;
    ">☰</button>

    {{-- ✅ LEFT SIDEBAR --}}
    <div id="sidebar" style="
        width:22%;
        min-width:250px;
        background:#fff;
        border-right:1px solid #eee;
        display:flex;
        flex-direction:column;
        border-radius:12px 0 0 12px;
        overflow:hidden;
        box-shadow:3px 0 8px rgba(0,0,0,0.05);
        padding-top:40px;
        height:100vh;
        transition:all 0.3s ease;
    ">
        {{-- Header --}}
        <div style="
            color:#6b4a6b;
            text-align:center;
            font-weight:700;
            font-size:1.3rem;
            padding:18px 0;
            border-radius:8px;
            margin:0 16px 10px 16px;
            overflow-y:hidden;
            flex-shrink:0;
        ">
            Recent Conversations
        </div>

        <div id="sessions-container" style="flex:1; overflow-y:auto; padding:0 16px 16px 16px; min-height:0;">

            {{-- ✅ New Chat Button --}}
            <button id="new-chat-btn" style="
                width:100%;
                padding:10px 14px;
                background:#6b4a6b;
                color:#fff;
                border:none;
                border-radius:8px;
                font-weight:600;
                cursor:pointer;
                transition:0.2s;
                margin-bottom:16px;
            " onmouseover="this.style.background='#8a5b8a'"
              onmouseout="this.style.background='#6b4a6b'">
                + New Chat
            </button>

            {{-- ✅ Chat Sessions --}}
            @if(isset($sessions) && $sessions->count() > 0)
                @foreach($sessions as $session)
                    <div class="session-item" data-id="{{ $session->id }}" style="position:relative; margin-bottom:10px;">
                        <a href="{{ route('chatbot.show', $session->id) }}" style="
                            display:block;
                            padding:12px 14px;
                            text-decoration:none;
                            border-radius:8px;
                            border:1px solid #eee;
                            background: {{ $currentSession && $currentSession->id == $session->id ? '#d1e7dd' : '#fff' }};
                            color:#333;
                            font-weight:500;
                            transition:0.2s;
                        " onmouseover="if(this.style.background!=='rgb(209, 231, 221)') this.style.background='#f7f7f7'"
                          onmouseout="if(this.style.background!=='rgb(209, 231, 221)') this.style.background='#fff'">
                            <div class="session-name" style="font-weight:700; font-size:0.95rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $session->session_name }}
                            </div>
                            <div style="font-size:0.85rem; color:#666; margin-top:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $session->preview }}
                            </div>
                            <div style="font-size:0.75rem; color:#999; margin-top:2px;">
                                {{ $session->last_updated }}
                            </div>
                        </a>

                        {{-- 3-dot menu --}}
                        <div class="dropdown" style="position:absolute; top:8px; right:10px;">
                            <button class="session-menu-btn" style="
                                background:none;
                                border:none;
                                font-size:1.2rem;
                                cursor:pointer;
                                padding:0 6px;
                            ">⋮</button>

                            <div class="dropdown-content" style="
                                display:none;
                                position:absolute;
                                right:0;
                                top:24px;
                                background:#fff;
                                border:1px solid #ddd;
                                border-radius:6px;
                                box-shadow:0 2px 6px rgba(0,0,0,0.15);
                                z-index:1000;
                                min-width:100px;
                                padding:4px 0;
                            ">
                                <button class="rename-session" data-id="{{ $session->id }}" style="
                                    display:flex; align-items:center; gap:8px;
                                    width:100%; padding:10px 16px;
                                    background:#f9f9f9; border:none; text-align:left;
                                    cursor:pointer; border-radius:6px;
                                    font-weight:500; color:#333;
                                " onmouseover="this.style.background='#e0e0e0'"
                                  onmouseout="this.style.background='#f9f9f9'">
                                    ✏️ Edit
                                </button>

                                <button class="delete-session" data-id="{{ $session->id }}" style="
                                    display:flex; align-items:center; gap:8px;
                                    width:100%; padding:10px 16px;
                                    background:#f9f9f9; border:none; text-align:left;
                                    cursor:pointer; border-radius:6px;
                                    font-weight:500; color:#d9534f;
                                " onmouseover="this.style.background='#f8d7da'"
                                  onmouseout="this.style.background='#f9f9f9'">
                                    🗑️ Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div id="no-sessions-msg" style="text-align:center; color:#999; padding:30px 10px; font-size:0.9rem;">
                    No conversations yet.<br>Start one!
                </div>
            @endif
        </div>
    </div>

    {{-- ✅ MAIN CHAT AREA --}}
    <div id="chat-area" style="flex:1; display:flex; justify-content:center; align-items:flex-start; padding-top:40px; padding-right:20px; height:100vh; transition:all 0.3s ease;">
        <div style="width:100%; display:flex; justify-content:center;">
            <div class="card shadow" style="
                background:#fff;
                border-radius:15px;
                width:100%;
                max-width:1000px;
                display:flex;
                flex-direction:column;
                overflow:hidden;
                box-shadow:0 2px 10px rgba(0,0,0,0.08);
            ">
                <meta name="csrf-token" content="{{ csrf_token() }}">

                {{-- Header --}}
                <div style="padding:14px; background:#6b4a6b; font-weight:700; font-size:1.1rem; color:#fff;">
                    🤖 PawTulong Chatbot
                    @if(isset($currentSession))
                        <div style="font-size:0.85rem; font-weight:400; margin-top:3px; color:#f5f5f5;">
                            {{ $currentSession->session_name }}
                        </div>
                    @endif
                </div>

                {{-- Chat Messages --}}
                <div id="chat-box" style="
                    flex:1;
                    overflow-y:auto;
                    padding:14px;
                    min-height:380px;
                    max-height:55vh;
                    background:#fafafa;
                ">
                    @if(isset($conversation) && count($conversation))
                        @foreach($conversation as $msg)
                            <div style="display:flex; justify-content:flex-end; margin-bottom:6px;">
                                <div style="background:#d1e7dd; padding:8px 12px; border-radius:12px; max-width:70%; font-size:0.9rem;">
                                    <strong>You:</strong> {{ e($msg->question) }}
                                </div>
                            </div>
                            <div style="display:flex; justify-content:flex-start; margin-bottom:10px;">
                                <div style="background:#f8d7da; padding:8px 12px; border-radius:12px; max-width:70%; font-size:0.9rem;">
                                    <strong>Bot:</strong> {{ e($msg->answer) }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="color:#6c757d;">Start a new conversation!</div>
                    @endif
                </div>

                {{-- Input --}}
                <div id="chat-input-container" style="display:flex; padding:12px; border-top:1px solid #eee; background:#fff;">
                    <input type="text" name="message" id="chat-input" style="
                        flex:1;
                        padding:8px 12px;
                        border:1px solid #ddd;
                        border-radius:8px;
                        margin-right:10px;
                        font-size:0.9rem;
                    " placeholder="Type your message..." autocomplete="off">
                    <button type="button" id="send-btn" style="
                        padding:8px 14px;
                        background:#6b4a6b;
                        color:#fff;
                        border:none;
                        border-radius:8px;
                        font-weight:700;
                        cursor:pointer;
                        transition:0.2s;
                    " onmouseover="this.style.background='#8a5b8a'"
                      onmouseout="this.style.background='#6b4a6b'">
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ PROMPT SUGGESTIONS PANEL --}}
    <div id="prompt-panel" style="
        width:280px;
        background:#fff;
        border-left:1px solid #eee;
        border-radius:0 12px 12px 0;
        padding:20px;
        box-shadow:-3px 0 8px rgba(0,0,0,0.05);
        overflow-y:auto;
        height:100vh;
    ">
        <h3 style="color:#6b4a6b; font-size:1.1rem; font-weight:700; margin-bottom:15px; text-align:center;">
            🧠 Try Asking
        </h3>

        <ul id="prompt-list" style="list-style:none; padding:0; margin:0;">
            @forelse($promptSuggestions as $prompt)
                <li class="prompt-item" style="
                    background:#f3e3f3;
                    padding:10px 14px;
                    border-radius:18px;
                    margin-bottom:10px;
                    cursor:pointer;
                    text-align:center;
                    font-weight:500;
                    transition:0.3s;
                " onmouseover="this.style.background='#e3cde3'"
                  onmouseout="this.style.background='#f3e3f3'">
                    {{ $prompt }}
                </li>
            @empty
                <li style="text-align:center; color:#999;">No suggestions yet.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- ✅ JS HANDLERS --}}
<script>

 // 🧠 Dynamic prompt click (from chat_logs)
document.addEventListener('click', e => {
    if (e.target.classList.contains('prompt-item')) {
        chatInput.value = e.target.textContent.trim();
        handleSend();
    }
});


// 🟣 Sidebar Toggle
const toggleBtn = document.getElementById('toggle-sidebar');
const sidebar = document.getElementById('sidebar');
const chatArea = document.getElementById('chat-area');

toggleBtn.addEventListener('click', () => {
  if (sidebar.style.marginLeft === '-260px') {
    sidebar.style.marginLeft = '0';
    sidebar.style.opacity = '1';
    toggleBtn.textContent = '☰';
  } else {
    sidebar.style.marginLeft = '-260px';
    sidebar.style.opacity = '0';
    toggleBtn.textContent = '>>';
  }
});

const chatBox = document.getElementById('chat-box');
const chatInput = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-btn');
const newChatBtn = document.getElementById('new-chat-btn');
let isSending = false;
let currentSessionId = '{{ $currentSession->id ?? "" }}';

// 🟢 Send message
async function handleSend() {
    const msg = chatInput.value.trim();
    if (!msg || !currentSessionId) return;

    isSending = true;
    chatInput.disabled = true;
    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';

    // User message
    chatBox.innerHTML += `
        <div style="display:flex; justify-content:flex-end; margin-bottom:8px;">
            <div style="background:#d1e7dd; padding:10px 14px; border-radius:12px; max-width:70%;">
                <strong>You:</strong> ${msg}
            </div>
        </div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
    chatInput.value = '';

    // Typing animation
    const typingEl = document.createElement('div');
    typingEl.innerHTML = `
        <div style="display:flex; justify-content:flex-start; margin-bottom:12px;">
            <div style="background:#fff3cd; padding:10px 14px; border-radius:12px; max-width:70%;">
                <strong>Bot:</strong> <span class="typing-dots">...</span>
            </div>
        </div>`;
    chatBox.appendChild(typingEl);
    chatBox.scrollTop = chatBox.scrollHeight;

    try {
        const res = await fetch('{{ route("chatbot.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: msg, session_id: currentSessionId })
        });

        if (!res.ok) throw new Error('HTTP error');
        const data = await res.json();

        typingEl.remove();
        chatBox.innerHTML += `
            <div style="display:flex; justify-content:flex-start; margin-bottom:12px;">
                <div style="background:#f8d7da; padding:10px 14px; border-radius:12px; max-width:70%;">
                    <strong>Bot:</strong> ${data.reply}
                </div>
            </div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
        currentSessionId = data.session_id;
    } catch (err) {
        console.error(err);
        typingEl.remove();
        chatBox.innerHTML += `
            <div style="display:flex; justify-content:flex-start; margin-bottom:12px;">
                <div style="background:#f8d7da; padding:10px 14px; border-radius:12px; max-width:70%;">
                    <strong>Bot:</strong> Sorry, there was an error. Try again.
                </div>
            </div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    } finally {
        isSending = false;
        chatInput.disabled = false;
        sendBtn.disabled = false;
        sendBtn.textContent = 'Send';
    }
}

sendBtn.addEventListener('click', e => { e.preventDefault(); if (!isSending) handleSend(); });
chatInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        if (!isSending) handleSend();
    }
});

// 🆕 New chat
newChatBtn.addEventListener('click', async () => {
    const res = await fetch('{{ route("chatbot.new") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
    if (!res.ok) return;
    const data = await res.json();
    currentSessionId = data.session_id;
    chatBox.innerHTML = '<div style="color:#6c757d;">Start a new conversation!</div>';
    location.reload();
});

// 🟠 Load conversation history on session click
document.querySelectorAll('.session-item').forEach(item => {
    item.addEventListener('click', async () => {
        const id = item.dataset.id;
        currentSessionId = id;
        chatBox.innerHTML = '<div style="color:#6c757d;">Loading conversation...</div>';

        try {
            const res = await fetch(`/chatbot/${id}`);
            if (!res.ok) throw new Error('Failed to load chat');
            const html = await res.text();

            // Parse chat logs from response HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newChatBox = doc.querySelector('#chat-box');
            if (newChatBox) chatBox.innerHTML = newChatBox.innerHTML;
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (err) {
            console.error(err);
            chatBox.innerHTML = '<div style="color:#dc3545;">Failed to load conversation.</div>';
        }
    });
});

// 🔹 Dropdown toggle
document.querySelectorAll('.session-menu-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        const menu = btn.nextElementSibling;
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });
});
document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-content').forEach(menu => menu.style.display = 'none');
});

// 🔴 Delete (silent + reload)
document.querySelectorAll('.delete-session').forEach(btn => {
    btn.addEventListener('click', async e => {
        e.preventDefault();
        e.stopPropagation();
        const id = btn.getAttribute('data-id');
        try {
            const res = await fetch(`/chatbot/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (data.success) location.reload();
        } catch (err) {
            console.error(err);
        }
    });
});

// 🟣 Rename inline
document.querySelectorAll('.rename-session').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();

        const sessionItem = btn.closest('.session-item');
        const nameDiv = sessionItem.querySelector('.session-name');
        if (sessionItem.querySelector('input')) return;

        const currentName = nameDiv.textContent;
        const input = document.createElement('input');
        input.type = 'text';
        input.value = currentName;
        input.style.width = '90%';
        input.style.fontWeight = '700';
        input.style.fontSize = '0.95rem';
        input.style.border = '1px solid #ccc';
        input.style.borderRadius = '4px';
        input.style.padding = '2px 6px';

        nameDiv.replaceWith(input);
        input.focus();

        const commitRename = async () => {
            const newName = input.value.trim();
            if (!newName) return;
            try {
                const res = await fetch(`/chatbot/${sessionItem.dataset.id}/rename`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name: newName })
                });
                const data = await res.json();
                if (data.success) {
                    nameDiv.textContent = newName;
                    input.replaceWith(nameDiv);
                } else input.replaceWith(nameDiv);
            } catch (err) {
                input.replaceWith(nameDiv);
            }
        };

        input.addEventListener('keydown', ev => {
            if (ev.key === 'Enter') commitRename();
            if (ev.key === 'Escape') input.replaceWith(nameDiv);
        });
        input.addEventListener('blur', () => input.replaceWith(nameDiv));
    });
});

document.getElementById('toggle-sidebar').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const promptPanel = document.getElementById('prompt-panel');
    const chatArea = document.getElementById('chat-area');

    sidebar.classList.toggle('hidden');
    promptPanel.classList.toggle('hidden');
    chatArea.classList.toggle('sidebar-hidden');
});



</script>

@endsection
