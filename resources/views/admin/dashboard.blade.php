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

{{-- Summary Box --}}
<div class="products-section" style="display:flex;justify-content:center;align-items:center;margin-bottom:30px;gap:20px;flex-wrap:wrap;">
    <div class="card shadow" style="background:#fff;border-radius:15px;padding:20px;width:400px;text-align:center;">
        <div style="font-weight:700;color:#231f20;font-size:1rem;margin-bottom:10px;">Total Questions</div>
        <div style="font-size:1.5rem;font-weight:700;color:#6b4a6b;">{{ $totalQuestions }}</div>
    </div>
    <div class="card shadow" style="background:#fff;border-radius:15px;padding:20px;width:400px;text-align:center;">
        <div style="font-weight:700;color:#231f20;font-size:1rem;margin-bottom:10px;">Total Users</div>
        <div style="font-size:1.5rem;font-weight:700;color:#6b4a6b;">{{ $totalUsers }}</div>
    </div>
</div>
{{-- ================= Export / Filter Section ================= --}}
<div class="products-section" 
     style="display:flex;justify-content:center;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:20px;">

    <a href="{{ route('admin.export', ['type' => 'top_questions']) }}" 
       class="btn"
       style="padding:10px 16px;border-radius:8px;background:#6b4a6b;color:#fff;text-decoration:none;font-weight:700;">
       📤 Export Top Questions
    </a>

    <a href="{{ route('admin.export', ['type' => 'top_users']) }}" 
       class="btn"
       style="padding:10px 16px;border-radius:8px;background:#28a745;color:#fff;text-decoration:none;font-weight:700;">
       📤 Export Top Users
    </a>

    <a href="{{ route('admin.export', ['type' => 'trends']) }}" 
       class="btn"
       style="padding:10px 16px;border-radius:8px;background:#ff9800;color:#fff;text-decoration:none;font-weight:700;">
       📤 Export Trends
    </a>

</div>

{{-- ================= Top Users + Charts Horizontal Layout ================= --}}
<div class="products-section" 
     style="display:flex;justify-content:center;align-items:flex-start;gap:20px;flex-wrap:wrap;">

    {{-- Top Users --}}
    <div class="card shadow" style="background:#fff;border-radius:15px;overflow:hidden;min-height:250px;max-height:350px;display:flex;flex-direction:column;width:400px;">
        <div class="card-header" style="padding:10px 14px;background:#e6b6d6;font-weight:700;text-align:center;">🏆 Top Users</div>
        <div class="card-body" style="padding:12px;overflow-y:auto;flex:1;">
            <ul style="margin:0;padding-left:0;list-style:none;">
                @forelse($topUsers as $tu)
                    <li style="margin-bottom:8px;">
                        <div style="padding:8px 10px;border:1px solid #eee;border-radius:6px;background:#f9f9f9;font-size:0.95rem;text-align:center;">
                            {{ optional($tu->user)->username ?? optional($tu->user)->email ?? 'Unknown User' }}
                            — <strong>{{ $tu->conversations_count }}</strong> questions
                        </div>
                    </li>
                @empty
                    <li style="text-align:center;">No top users yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Most Asked Questions Chart (Horizontal Bar) --}}
    <div class="card shadow" style="background:#fff;border-radius:15px;overflow:hidden;display:flex;flex-direction:column;width:500px;">
        <div class="card-header" style="padding:12px 16px;background:#e6b6d6;font-weight:700;text-align:center;">🔥 Most Asked Questions</div>
        <div class="card-body" style="padding:16px;display:flex;justify-content:center;align-items:center;">
            <canvas id="mostAskedChart" height="220"></canvas>
            <div id="mostAskedEmpty" style="display:none;color:#6c757d;margin-top:12px;text-align:center;">No data yet.</div>
        </div>
    </div>

    {{-- Daily Trends Chart (Area + 7-day Moving Average) --}}
    <div class="card shadow" style="background:#fff;border-radius:15px;overflow:hidden;display:flex;flex-direction:column;width:500px;">
        <div class="card-header" style="padding:12px 16px;background:#e6b6d6;font-weight:700;text-align:center;">📊 Daily Trends (Last 7 Days)</div>
        <div class="card-body" style="padding:16px;display:flex;justify-content:center;align-items:center;">
            <canvas id="trendChart" height="220"></canvas>
            <div id="trendEmpty" style="display:none;color:#6c757d;margin-top:12px;text-align:center;">No data yet.</div>
        </div>
    </div>

    {{-- Top Users Pareto (Bars + Cumulative %) --}}
    <div class="card shadow" style="background:#fff;border-radius:15px;overflow:hidden;display:flex;flex-direction:column;width:500px;">
        <div class="card-header" style="padding:12px 16px;background:#e6b6d6;font-weight:700;text-align:center;">📈 Top Users Pareto</div>
        <div class="card-body" style="padding:16px;display:flex;justify-content:center;align-items:center;">
            <canvas id="topUsersPareto" height="220"></canvas>
            <div id="paretoEmpty" style="display:none;color:#6c757d;margin-top:12px;text-align:center;">No data yet.</div>
        </div>
    </div>

    
</div>




{{-- Chart Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Most Asked Questions Chart (Horizontal Bar)
    const mostLabels = @json($mostAsked->pluck('question') ?? []);
    const mostSeries = @json($mostAsked->pluck('count') ?? []);
    const mostCanvas = document.getElementById('mostAskedChart');
    const mostEmpty = document.getElementById('mostAskedEmpty');

    if (mostCanvas && mostLabels.length && mostSeries.length) {
        new Chart(mostCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: mostLabels,
                datasets: [{
                    label: 'Count',
                    data: mostSeries,
                    backgroundColor: 'rgba(107, 74, 107, 0.85)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                indexAxis: 'y',
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 }, grid: { display: false } },
                    y: { grid: { display: false } }
                }
            }
        });
    } else {
        if (mostCanvas) mostCanvas.style.display = 'none';
        if (mostEmpty) mostEmpty.style.display = 'block';
    }

    // Daily Trends Chart (Area + MA)
    const trendLabels = @json($trendLabels ?? []);
    const trendSeries = @json($trendSeries ?? []);
    const trendCanvas = document.getElementById('trendChart');
    const trendEmpty = document.getElementById('trendEmpty');

    if (trendCanvas && trendLabels.length && trendSeries.length) {
        const maWindow = 7;
        const maSeries = trendSeries.map((_, i) => {
            const start = Math.max(0, i - maWindow + 1);
            const subset = trendSeries.slice(start, i + 1);
            const avg = subset.reduce((a, b) => a + b, 0) / subset.length;
            return Math.round(avg * 100) / 100;
        });

        new Chart(trendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Questions per Day',
                        data: trendSeries,
                        borderColor: 'rgba(107, 74, 107, 1)',
                        backgroundColor: 'rgba(107, 74, 107, 0.20)',
                        fill: true,
                        tension: 0.25,
                        borderWidth: 2,
                        pointRadius: 2
                    },
                    {
                        label: '7-day MA',
                        data: maSeries,
                        borderColor: 'rgba(40, 167, 69, 1)',
                        backgroundColor: 'rgba(40, 167, 69, 0.0)',
                        fill: false,
                        tension: 0.25,
                        borderDash: [6, 4],
                        borderWidth: 2,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    } else {
        if (trendCanvas) trendCanvas.style.display = 'none';
        if (trendEmpty) trendEmpty.style.display = 'block';
    }

    // Top Users Pareto
    const paretoCanvas = document.getElementById('topUsersPareto');
    const paretoEmpty = document.getElementById('paretoEmpty');
    const userLabels = @json($topUsers->map(fn($u) => optional($u->user)->username ?? optional($u->user)->email ?? 'Unknown')->values() ?? []);
    const userCounts = @json($topUsers->pluck('conversations_count') ?? []);

    if (paretoCanvas && userLabels.length && userCounts.length) {
        const total = userCounts.reduce((a, b) => a + b, 0) || 1;
        const cumulative = [];
        let sum = 0;
        for (let i = 0; i < userCounts.length; i++) {
            sum += userCounts[i];
            cumulative.push(Math.round((sum / total) * 100));
        }

        new Chart(paretoCanvas.getContext('2d'), {
            data: {
                labels: userLabels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Conversations',
                        data: userCounts,
                        backgroundColor: 'rgba(107, 74, 107, 0.85)',
                        yAxisID: 'y',
                        borderRadius: 4
                    },
                    {
                        type: 'line',
                        label: 'Cumulative %',
                        data: cumulative,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.0)',
                        yAxisID: 'y1',
                        tension: 0.25,
                        pointRadius: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    y1: { position: 'right', min: 0, max: 100, ticks: { callback: (v) => v + '%' } },
                    x: { grid: { display: false } }
                }
            }
        });
    } else {
        if (paretoCanvas) paretoCanvas.style.display = 'none';
        if (paretoEmpty) paretoEmpty.style.display = 'block';
    }

});
</script>
@endsection
