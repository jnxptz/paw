@extends('layouts.app')

@section('title', 'Admin Profile | PawTulong')

@php
    $layoutCss = 'admin_products.css';
    $layoutJs  = 'admin.js';
    $page      = 'profile';
@endphp

@section('content')
<h1 class="page-title" style="text-align:center;margin-bottom:30px;">Profile</h1>

{{-- Profile Card --}}
{{-- Profile Card --}}
<div class="products-section" 
    style="display:flex;justify-content:center;align-items:center;margin-bottom:40px;flex-direction:column;">
    
    <div style="background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.08);
        padding:32px;width:100%;max-width:640px;display:flex;flex-direction:column;gap:20px;">
        
        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;
            border-bottom:2px solid #e6b6d6;padding-bottom:10px;">
            <i class="fas fa-user" style="font-size:22px;color:#6b4a6b;"></i>
            <h2 style="font-size:1.25rem;margin:0;color:#231f20;">Profile Information</h2>
        </div>

        {{-- Profile Grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;column-gap:24px;row-gap:18px;">
            
            <div>
                <label style="font-weight:600;color:#555;">Full Name</label>
                <div style="border:none;border-bottom:1px solid #ccc;padding:6px 0;font-size:1rem;">
                    {{ $user->username }}
                </div>
            </div>

            <div>
                <label style="font-weight:600;color:#555;">Email Address</label>
                <div style="border:none;border-bottom:1px solid #ccc;padding:6px 0;font-size:1rem;">
                    <a href="mailto:{{ $user->email }}" style="color:#6b4a6b;text-decoration:none;">
                        {{ $user->email }}
                    </a>
                </div>
            </div>

            <div>
                <label style="font-weight:600;color:#555;">User Type</label>
                <div style="border:none;border-bottom:1px solid #ccc;padding:6px 0;font-size:1rem;">
                    {{ ucfirst($user->user_type) }}
                </div>
            </div>

            <div>
                <label style="font-weight:600;color:#555;">Account Created</label>
                <div style="border:none;border-bottom:1px solid #ccc;padding:6px 0;font-size:1rem;">
                    {{ $user->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>

        {{-- Change Password Link --}}
        <div style="text-align:center;margin-top:10px;padding-top:10px;border-top:1px solid #eee;">
            <a href="#" id="changePasswordLink" 
               style="color:#6b4a6b;
               font-weight:600;
               text-decoration:underline;
               cursor:pointer;">
               Change Password
            </a>
        </div>
    </div>
</div>


  

{{-- Password Change Modal --}}

<div id="changePasswordModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div class="modal-content" style="background:#fff; padding:24px; border-radius:12px; max-width:400px; width:90%; position:relative;">
        <span class="close" style="position:absolute; top:10px; right:16px; font-size:20px; cursor:pointer;">&times;</span>
        <h2 style="text-align:center; margin-bottom:16px;">Change Password</h2>
        <form action="{{ route('admin.changePassword') }}" method="POST"> <!-- updated route -->
            @csrf
            <div class="form-group" style="margin-bottom:12px;">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="add-btn" style="width:100%; margin-top:12px;">Save</button>
        </form>
    </div>
</div>

{{-- JS to open/close modal --}}
<script>
const modal = document.getElementById('changePasswordModal');
const link = document.getElementById('changePasswordLink');
const close = modal.querySelector('.close');

link.addEventListener('click', e => {
    e.preventDefault();
    modal.style.display = 'flex';
});

close.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', e => {
    if (e.target == modal) modal.style.display = 'none';
});
</script>

@endsection
