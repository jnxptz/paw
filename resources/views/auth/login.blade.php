@extends('layouts.app')

@section('title', 'Login')

@php
    $layoutCss = 'login.css';
    $layoutJs = 'login.js';
@endphp

@section('content')
<div class="container">
    <div class="login-box">
        <h1>Login</h1>

        {{-- Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">LOGIN</button>
        </form>

        <p class="mt-3 text-center">
            <a href="#" id="openForgotModal">Forgot your password?</a>
        </p>

        <div class="signup-link text-center">
            Not yet a member? <a href="{{ route('register.form') }}">Sign Up</a>
        </div>
    </div>
</div>

{{-- 🟣 Forgot Password Modal --}}
<div id="forgotModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:450px;">
        <span class="close">&times;</span>
        <h2><i class="fas fa-unlock-alt"></i> Reset Password</h2>

        @if(session('modal_success'))
            <div class="message success">
                <i class="fas fa-check-circle"></i> {{ session('modal_success') }}
            </div>
        @endif
        @if(session('modal_error'))
            <div class="message error">
                <i class="fas fa-triangle-exclamation"></i> {{ session('modal_error') }}
            </div>
        @endif

        <form id="resetForm" action="{{ route('reset.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="new_password_confirmation" required>
            </div>

            <button type="submit" class="add-btn" style="width:100%;">Save New Password</button>
        </form>
    </div>
</div>

<script>
// 🟣 Modal Logic (same as product modal)
const forgotModal = document.getElementById('forgotModal');
const openForgotModal = document.getElementById('openForgotModal');
const closeForgotModal = forgotModal.querySelector('.close');

openForgotModal.addEventListener('click', (e) => {
    e.preventDefault();
    forgotModal.style.display = 'flex';
});

closeForgotModal.addEventListener('click', () => forgotModal.style.display = 'none');
window.addEventListener('click', e => { if (e.target === forgotModal) forgotModal.style.display = 'none'; });
</script>

</style>
@endsection
