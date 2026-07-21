@extends('admin.layouts.admin')

@section('title', 'TripWise — Evaluation Analytics')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <span>Evaluation Analytics</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Evaluation Analytics</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Analyze peer evaluation trends and performance insights across the organization.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('excel')"><i class="fas fa-download"></i> Export Analytics</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['avg_peer_score'] ?? 0, 2) }}</h3>
            <p>Average Peer Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3>{{ $stats['highest_rated_category'] }}</h3>
            <p>Highest Rated Category</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-calendar-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['monthly_evaluations'] }}</h3>
            <p>Monthly Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-tasks"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['completion_rate'] ?? 0, 1) }}%</h3>
            <p>Evaluation Completion Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.evaluation.analytics') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <select name="period" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="monthly" {{ request('period') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="quarterly" {{ request('period') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
            <option value="yearly" {{ request('period') === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
        <select name="driver_id" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:180px;">
            <option value="">All Drivers</option>
            @foreach($drivers as $driver)
                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
            @endforeach
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Apply</button>
            <a href="{{ route('admin.evaluation.analytics') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Monthly Evaluation Trend</h3>
        <div class="chart-wrapper">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Peer Score Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="peerScoreDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Category Comparison</h3>
        <div class="chart-wrapper">
            <canvas id="categoryCompChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-ranking-star"></i> Driver Ranking</h3>
        <div class="chart-wrapper">
            <canvas id="driverRankingChart"></canvas>
        </div>
    </div>
</div>

<!-- Driver Ranking Table -->
<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-trophy"></i> Driver Performance Ranking</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Driver</th>
                    <th>Evaluations</th>
                    <th>Average Score</th>
                    <th>Trend</th>
                </tr>
            </thead>
            <tbody>
                @forelse($driverRanking as $index => $driver)
                <tr>
                    <td>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:{{ $index < 3 ? ['#fef3c7','#dbeafe','#f0fdf4'][$index] : 'var(--beige-dark)' }};color:{{ $index < 3 ? ['#92400e','#1e40af','#166534'][$index] : 'var(--text-muted)' }};font-weight:700;font-size:0.85rem;">{{ $index + 1 }}</span>
                    </td>
                    <td><strong>Driver #{{ $driver->evaluated_driver_id }}</strong></td>
                    <td>{{ $driver->evaluation_count }}</td>
                    <td><strong style="color:{{ $driver->avg_score >= 4 ? '#10b981' : ($driver->avg_score >= 3 ? '#f59e0b' : '#ef4444') }};">{{ number_format($driver->avg_score, 2) }}</strong></td>
                    <td>
                        @if($index < 3)
                            <i class="fas fa-arrow-up" style="color:var(--success);"></i>
                        @elseif($index < 6)
                            <i class="fas fa-minus" style="color:var(--text-muted);"></i>
                        @else
                            <i class="fas fa-arrow-down" style="color:var(--danger);"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">No ranking data available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
function exportReport(format) {
    showToast('Exporting analytics as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
    if (monthlyTrendCtx) {
        new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyTrend->pluck('month')->map(fn($m) => date('M Y', mktime(0, 0, 0, $m, 1)))->reverse()->values()) !!},
                datasets: [{
                    label: 'Evaluations',
                    data: {!! json_encode($monthlyTrend->pluck('count')->reverse()->values()) !!},
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    const peerScoreDistCtx = document.getElementById('peerScoreDistChart');
    if (peerScoreDistCtx) {
        new Chart(peerScoreDistCtx, {
            type: 'bar',
            data: {
                labels: ['1-2', '2-3', '3-4', '4-5'],
                datasets: [{
                    label: 'Evaluations',
                    data: [5, 12, 45, 89],
                    backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    const categoryCompCtx = document.getElementById('categoryCompChart');
    if (categoryCompCtx) {
        new Chart(categoryCompCtx, {
            type: 'radar',
            data: {
                labels: ['Professionalism', 'Communication', 'Teamwork', 'Safety', 'Reliability', 'Respectfulness'],
                datasets: [{
                    label: 'Average Score',
                    data: [4.5, 4.3, 4.6, 4.8, 4.4, 4.7],
                    backgroundColor: 'rgba(244, 67, 54, 0.2)',
                    borderColor: '#F44336',
                    pointBackgroundColor: '#F44336',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 5,
                    }
                }
            }
        });
    }

    const driverRankingCtx = document.getElementById('driverRankingChart');
    if (driverRankingCtx) {
        new Chart(driverRankingCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($driverRanking->pluck('evaluated_driver_id')->map(fn($id) => 'Driver #' . $id)->reverse()->values()) !!},
                datasets: [{
                    label: 'Average Score',
                    data: {!! json_encode($driverRanking->pluck('avg_score')->reverse()->values()) !!},
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: false, min: 3, max: 5 }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
