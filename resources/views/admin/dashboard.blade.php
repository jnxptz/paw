@extends('layouts.app')

@section('title', 'Admin Profile | PawTulong')

@php
    $layoutCss = 'admin_products.css';
    $layoutJs  = 'admin.js';
    $page      = 'profile';
@endphp

@section('content')
<h1 class="page-title" style="text-align:center;margin-bottom:30px;">Profile</h1>

<div style="
    display:flex;
    justify-content:center;
    align-items:flex-start;
    margin-bottom:60px;
    padding:0 20px;
">
    <div style="
        background:#fff;
        border-radius:16px;
        box-shadow:0 4px 24px rgba(0,0,0,0.08);
        padding:40px 48px;
        width:100%;
        max-width:720px;
        display:flex;
        flex-direction:column;
        gap:28px;
    ">
        {{-- Header --}}
        <div style="
            display:flex;
            align-items:center;
            gap:12px;
            border-bottom:3px solid #e6b6d6;
            padding-bottom:12px;
        ">
            <i class="fas fa-user" style="font-size:24px;color:#6b4a6b;"></i>
            <h2 style="font-size:1.4rem;margin:0;color:#231f20;">Profile Information</h2>
        </div>

        {{-- Profile Form --}}
        <form action="{{ route('admin.updateProfile') }}" method="POST" 
            style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            @csrf
            @method('PUT')

            <div>
                <label style="font-weight:600;color:#444;margin-bottom:6px;display:block;">Full Name</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                    style="border:1px solid #ddd;border-radius:6px;padding:10px 12px;font-size:1rem;width:100%;outline:none;">
            </div>

            <div>
                <label style="font-weight:600;color:#444;margin-bottom:6px;display:block;">Email Address</label>
                <input type="text" readonly value="{{ $user->email }}"
                    style="border:1px solid #eee;background:#fafafa;border-radius:6px;padding:10px 12px;font-size:1rem;width:100%;color:#6b4a6b;">
            </div>

            <div>
                <label style="font-weight:600;color:#444;margin-bottom:6px;display:block;">User Type</label>
                <input type="text" readonly value="{{ ucfirst($user->user_type) }}"
                    style="border:1px solid #eee;background:#fafafa;border-radius:6px;padding:10px 12px;font-size:1rem;width:100%;">
            </div>

            <div>
                <label style="font-weight:600;color:#444;margin-bottom:6px;display:block;">Account Created</label>
                <input type="text" readonly value="{{ $user->created_at->format('M d, Y') }}"
                    style="border:1px solid #eee;background:#fafafa;border-radius:6px;padding:10px 12px;font-size:1rem;width:100%;">
            </div>

            <div style="grid-column:span 2; text-align:center; margin-top:10px;">
                <button type="submit" class="add-btn" 
                    style="padding:10px 24px; font-size:1rem; border-radius:8px;">
                    Save Changes
                </button>
            </div>
        </form>

        {{-- Change Password --}}
        <div style="text-align:center;margin-top:10px;padding-top:16px;border-top:1px solid #eee;">
            <a href="#" id="changePasswordLink" 
               style="color:#6b4a6b;font-weight:600;text-decoration:underline;cursor:pointer;">
               Change Password
            </a>
        </div>
    </div>
</div>

{{-- Password Modal --}}
<div id="changePasswordModal" class="modal" style="
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:999;
">
    <div class="modal-content" style="
        background:#fff;
        padding:28px;
        border-radius:12px;
        max-width:420px;
        width:90%;
        position:relative;
        box-shadow:0 6px 24px rgba(0,0,0,0.15);
    ">
        <span class="close" style="position:absolute;top:10px;right:16px;font-size:22px;cursor:pointer;">&times;</span>
        <h2 style="text-align:center;margin-bottom:20px;">Change Password</h2>

        <form action="{{ route('admin.changePassword') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:14px;">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" required
                       style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control" required
                       style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required
                       style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
            </div>

            <button type="submit" class="add-btn" 
                style="width:100%;margin-top:16px;padding:10px;font-size:1rem;border-radius:8px;">
                Save
            </button>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('changePasswordModal');
const link = document.getElementById('changePasswordLink');
const close = modal.querySelector('.close');

link.addEventListener('click', e => {
    e.preventDefault();
    modal.style.display = 'flex';
});
close.addEventListener('click', () => modal.style.display = 'none');
window.addEventListener('click', e => { if (e.target == modal) modal.style.display = 'none'; });
</script>
@endsection
