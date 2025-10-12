@extends('layouts.app')

@section('title', 'PawTulong | Home')

@php
    $layoutCss = 'landing.css';
    $page = 'home';
@endphp

@section('content')

@auth
@if(Auth::user()->user_type === 'admin')
    {{-- Admin Dashboard Charts/Stats --}}
    <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; margin:30px 0;">
    {{-- Total Users --}}
    <div class="dashboard-header" 
     style="display:flex; justify-content:flex-start; align-items:center; gap:12px; flex-wrap:wrap; margin:20px 0;">

    {{-- Total Users --}}
 <div class="dashboard-header" 
     style="display:flex; justify-content:center; align-items:center; gap:12px; flex-wrap:wrap; margin:20px 0;">

    {{-- Total Users --}}
    <div class="dashboard-header" 
     style="display:flex; justify-content:center; align-items:center; gap:12px; flex-wrap:wrap; margin:20px 0;">

    {{-- Total Users --}}
 <div class="dashboard-header" 
     style="display:flex; justify-content:center; align-items:center; gap:12px; flex-wrap:wrap; margin:20px; padding:0 10px; overflow-x:hidden;">

    {{-- Total Users --}}
    <div class="card shadow" 
         style="background:linear-gradient(145deg,#fff,#f9f5fa);
                border-radius:16px;
                padding:14px 16px;
                min-width:160px;
                max-width:220px;
                flex: 1 1 auto;
                text-align:center;
                box-shadow:0 4px 15px rgba(107,74,107,0.2);">
        <div style="font-weight:900; color:#6b4a6b; font-size:1rem; margin-bottom:6px;">
            Total Users
        </div>
        <div style="font-size:1.5rem; font-weight:900; color:#a86ca8;">
            {{ $totalUsers ?? 0 }}
        </div>
    </div>

    {{-- Export Buttons --}}
    <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:center; flex: 1 1 auto; max-width:600px;">
        <a href="{{ route('admin.export', ['type' => 'top_questions']) }}" class="btn" style="padding:10px 16px; border-radius:8px; background:#6b4a6b; color:#fff; text-decoration:none; font-weight:800; font-size:0.95rem; white-space:nowrap;">Export Top Questions</a>
        <a href="{{ route('admin.export', ['type' => 'top_users']) }}" class="btn" style="padding:10px 16px; border-radius:8px; background:#28a745; color:#fff; text-decoration:none; font-weight:800; font-size:0.95rem; white-space:nowrap;">Export Top Users</a>
        <a href="{{ route('admin.export', ['type' => 'trends']) }}" class="btn" style="padding:10px 16px; border-radius:8px; background:#ff9800; color:#fff; text-decoration:none; font-weight:800; font-size:0.95rem; white-space:nowrap;">Export Trends</a>
    </div>
</div>



    {{-- Dashboard Sections --}}
    <div class="products-section" style="display:flex;justify-content:center;align-items:flex-start;gap:24px;flex-wrap:wrap;">
        
        {{-- Top Users --}}
       <div class="card shadow" 
     style="background:linear-gradient(145deg,#fff,#f9f5fa);
            border-radius:20px;
            overflow:hidden;
            min-height:260px;
            max-height:370px;
            display:flex;
            flex-direction:column;
            width:420px;
            box-shadow:0 6px 25px rgba(107,74,107,0.2);">

            
            <div class="card-header" 
            style="padding:14px 18px;
            background:#e6b6d6;
            font-weight:900;
            text-align:center;
            font-size:1.1rem;">Top Users
            
        </div>
            <div class="card-body"
             style="padding:16px;
             overflow-y:auto;flex:1;">

                <ul 
                style="margin:0;
                padding-left:0;
                list-style:none;">

                    @forelse(($topUsers ?? []) as $tu)
                        <li style="margin-bottom:10px;">
                            <div style="padding:10px 12px;
                            border:1px solid #eee;
                            border-radius:8px;
                            background:#f9f9f9;
                            font-size:1rem;
                            text-align:center;">
                                @php
                                    $user = is_object($tu->user ?? null) ? $tu->user : null;
                                @endphp
                                {{ $user->username ?? $user->email ?? 'Unknown User' }} — <strong>{{ $tu->conversations_count ?? 0 }}</strong> questions
                            </div>
                        </li>
                    @empty
                        <li style="text-align:center;">No top users yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        
        {{-- Most Asked Questions --}}
<div class="card shadow" 
     style="background:linear-gradient(145deg,#fff,#f9f5fa);
            border-radius:20px;
            overflow:hidden;
            display:flex;
            flex-direction:column;
            width:520px;
            box-shadow:0 6px 25px rgba(107,74,107,0.2);">

    <div class="card-header" 
         style="padding:18px 20px;
                background:linear-gradient(90deg,#6b4a6b,#a86ca8);
                color:#fff;
                font-weight:900;
                text-align:center;
                font-size:1.15rem;
                letter-spacing:0.5px;">
        Most Asked Questions
    </div>

    <div class="card-body" 
         style="padding:24px;display:flex;justify-content:center;align-items:center;">
        <div style="width:100%;max-width:460px;">
            <canvas id="mostAskedChart" height="300"></canvas>
            <div id="mostAskedEmpty" 
                 style="display:none;color:#777;margin-top:14px;text-align:center;font-style:italic;">
                No data yet.
            </div>
        </div>
    </div>
</div>


       {{-- Trends Over Time --}}
<div class="card shadow" 
     style="background:linear-gradient(145deg,#fff,#f9f5fa);
            border-radius:20px;
            overflow:hidden;
            display:flex;
            flex-direction:column;
            width:520px;
            box-shadow:0 6px 25px rgba(107,74,107,0.2);">

    <div class="card-header" 
         style="padding:18px 20px;
                background:linear-gradient(90deg,#8a3d70,#d48abf);
                color:#fff;
                font-weight:900;
                text-align:center;
                font-size:1.15rem;
                letter-spacing:0.5px;">
        Chat Trends
    </div>

    <div class="card-body" 
         style="padding:24px;display:flex;justify-content:center;align-items:center;">
        <div style="width:100%;max-width:460px;">
            <canvas id="trendChart" height="300"></canvas>
            <div id="trendEmpty" 
                 style="display:none;color:#777;margin-top:14px;text-align:center;font-style:italic;">
                No data yet.
            </div>
        </div>
    </div>
</div>


    {{-- Chart Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Most Asked Questions Chart
        // Most Asked Questions Chart
const mostLabels = @json(($mostAsked ?? collect())->pluck('question')->filter());
const mostSeries = @json(($mostAsked ?? collect())->pluck('count')->filter());
const mostCanvas = document.getElementById('mostAskedChart');
const mostEmpty = document.getElementById('mostAskedEmpty');

if (mostCanvas && mostLabels.length && mostSeries.length) {
    const ctx = mostCanvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(107,74,107,0.9)');
    gradient.addColorStop(1, 'rgba(168,108,168,0.6)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: mostLabels,
            datasets: [{
                label: 'Times Asked',
                data: mostSeries,
                backgroundColor: gradient,
                borderRadius: 12,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(107,74,107,1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#333',
                    bodyColor: '#6b4a6b',
                    borderColor: '#e6b6d6',
                    borderWidth: 1,
                    bodyFont: { size: 13 },
                    padding: 10,
                    boxPadding: 4
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#444', font: { size: 13 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#555' },
                    grid: { color: 'rgba(240,240,240,0.5)' }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });
} else {
    mostCanvas.style.display = 'none';
    mostEmpty.style.display = 'block';
}



        // Daily Trends Chart
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
            trendCanvas.style.display = 'none';
            trendEmpty.style.display = 'block';
        }
    });
    </script>
@endif
@endauth
@endsection
