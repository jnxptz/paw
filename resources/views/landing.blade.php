@extends('layouts.app')

@section('title', 'PawTulong | Home')

@php 
use Illuminate\Support\Str;
$layoutCss = 'landing.css'; 
$page = 'home'; 
@endphp

@section('content')
@auth
@if(Auth::user()->user_type === 'admin')

{{-- 💡 Narrative Insight --}}
<div style="background:#fdf4fa;border:1px solid #eed3f3;border-radius:12px;padding:18px;margin:25px auto;max-width:950px;text-align:center;">
  <p style="margin:0;font-size:1.05rem;font-weight:600;color:#6b4a6b;">
    💡 This week, user activity increased by <strong>12%</strong>.
    <strong>{{ $topUsers[0]->user->username ?? 'Unknown' }}</strong> was most active,
    most asked question: <strong>“{{ Str::limit($mostAsked[0]->question ?? 'No data', 40) }}”</strong>
    ({{ $mostAsked[0]->count ?? 0 }}x).
  </p>
</div>

{{-- 📊 Summary Cards --}}
<div style="display:flex;justify-content:center;flex-wrap:wrap;gap:20px;margin-bottom:30px;">
  <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:16px 20px;min-width:160px;text-align:center;">
    <div style="font-size:0.9rem;color:#777;">Users</div>
    <div style="font-size:1.4rem;font-weight:700;color:#6b4a6b;">{{ $totalUsers ?? 0 }}</div>
  </div>
  <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:16px 20px;min-width:160px;text-align:center;">
    <div style="font-size:0.9rem;color:#777;">Chats</div>
    <div style="font-size:1.4rem;font-weight:700;color:#6b4a6b;">{{ $totalChats ?? 0 }}</div>
  </div>
</div>

{{-- 🔘 Export Buttons --}}
<div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-bottom:25px;">
  <a href="{{ route('admin.export', ['type'=>'top_questions']) }}" class="btn" style="padding:10px 16px;border-radius:8px;background:#6b4a6b;color:#fff;text-decoration:none;font-weight:700;">Top Qs</a>
  <a href="{{ route('admin.export', ['type'=>'top_users']) }}" class="btn" style="padding:10px 16px;border-radius:8px;background:#28a745;color:#fff;text-decoration:none;font-weight:700;">Top Users</a>
  <a href="{{ route('admin.export', ['type'=>'trends']) }}" class="btn" style="padding:10px 16px;border-radius:8px;background:#ff9800;color:#fff;text-decoration:none;font-weight:700;">Trends</a>
</div>

{{-- 🧩 Main Layout --}}
<div style="display:flex;justify-content:center;align-items:flex-start;gap:24px;flex-wrap:wrap;">

  {{-- 🏆 Top Users + Chat Trends --}}
  <div style="background:#fff;border:1px solid #eee;border-radius:12px;width:360px;padding:16px;">
    <h3 style="margin:0 0 10px;color:#6b4a6b;text-align:center;">🏆 Top Users</h3>
    <ul style="list-style:none;padding:0;margin:0;">
      @forelse($topUsers ?? [] as $i => $u)
        <li style="padding:8px;margin-bottom:6px;border:1px solid #eee;border-radius:8px;background:#f9f9f9;">
          <strong>#{{ $i+1 }}</strong> — {{ $u->user->username ?? $u->user->email ?? 'Unknown' }}
          <span style="float:right;color:#6b4a6b;font-weight:600;">{{ $u->conversations_count ?? 0 }}</span>
        </li>
      @empty
        <li style="text-align:center;">No users</li>
      @endforelse
    </ul>

    {{-- 📈 Chat Trends --}}
    <div style="margin-top:20px;border-top:1px solid #eee;padding-top:14px;">
      <h3 style="margin:0 0 10px;color:#6b4a6b;text-align:center;">📈 Chat Trends</h3>
      <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
        <thead>
          <tr style="background:#f5eaf7;text-align:center;">
            <th>Date</th>
            <th>Chats</th>
            <th>Δ</th>
          </tr>
        </thead>
        <tbody>
          @forelse($trendData ?? [] as $r)
            <tr style="border-bottom:1px solid #eee;text-align:center;">
              <td>{{ $r['date'] ?? '—' }}</td>
              <td>{{ $r['count'] ?? 0 }}</td>
              <td>{{ $r['change'] ?? '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="3" style="text-align:center;">No data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- 💬 Most Asked Questions --}}
  <div style="background:#fff;border:1px solid #eee;border-radius:12px;width:650px;padding:16px;">
    <h3 style="margin:0 0 10px;color:#6b4a6b;text-align:center;">💬 Most Asked Questions</h3>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
      @forelse($mostAsked ?? [] as $i => $q)
        <div 
          style="padding:10px;border:1px solid #eee;border-radius:8px;background:#f9f9f9;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:default;"
          title="{{ $q->question ?? 'Unknown' }}">
          <strong style="color:#ff6b81;">#{{ $i+1 }}</strong> — {{ Str::limit($q->question ?? 'Unknown', 45) }}
          <div style="text-align:right;color:#6b4a6b;font-weight:600;">{{ $q->count ?? 0 }}</div>
        </div>
      @empty
        <div style="grid-column:span 2;text-align:center;">No questions</div>
      @endforelse
    </div>
  </div>

</div>

@endif
@endauth
@endsection
