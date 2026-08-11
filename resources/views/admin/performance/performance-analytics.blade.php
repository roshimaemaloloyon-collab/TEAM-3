@extends('admin.layouts.admin')

@section('title', 'TripWise — Performance Analytics')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.performance.index') }}">Performance Management</a>
    <span>/</span>
    <span>Performance Analytics</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Performance Analytics</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Analyze driver performance using interactive analytics.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export Report</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Performance Score</p>
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;background:var(--charcoal);color:#fff;padding:0.75rem 1.25rem;border-radius:0.75rem;box-shadow:0 8px 20px rgba(0,0,0,0.2);z-index:3000;align-items:center;gap:0.75rem;font-size:0.85rem;font-family:'Inter',sans-serif;">
    <i class="fas fa-check-circle" style="color:var(--success);"></i>
    <span id="toastMessage"></span>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Performance Trend</h3>
        <div class="chart-wrapper">
            <canvas id="perfTrendAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> KPI Analytics</h3>
        <div class="chart-wrapper">
            <canvas id="kpiAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Safety Analytics</h3>
        <div class="chart-wrapper">
            <canvas id="safetyAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Attendance Analytics</h3>
        <div class="chart-wrapper">
            <canvas id="attendanceAnalyticsChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-star"></i> Customer Rating Analytics</h3>
        <div class="chart-wrapper">
            <canvas id="ratingAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-users"></i> Peer Evaluation Analytics</h3>
        <div class="chart-wrapper">
            <canvas id="peerAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Comparative Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="comparativeChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function exportReport(format) { showToast('Exporting ' + format.toUpperCase() + ' report...'); }
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('perfTrendAnalyticsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($perfTrend->pluck('month')->toArray()) !!},
            datasets: [{
                label: 'Performance Score',
                data: {!! json_encode($perfTrend->pluck('avg_score')->toArray()) !!},
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('kpiAnalyticsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($kpiByCategory->pluck('kpi_category')->toArray()) !!},
            datasets: [{
                label: 'KPI Score',
                data: {!! json_encode($kpiByCategory->pluck('avg_achievement')->toArray()) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    new Chart(document.getElementById('safetyAnalyticsChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($safetyDistribution)) !!},
            datasets: [{ data: {!! json_encode(array_values($safetyDistribution)) !!}, backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('attendanceAnalyticsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($attendanceTrend->pluck('month')->toArray()) !!},
            datasets: [{
                label: 'Attendance %',
                data: {!! json_encode($attendanceTrend->pluck('avg_attendance')->toArray()) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('ratingAnalyticsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($driverLabels) !!},
            datasets: [{
                label: 'Customer Rating',
                data: {!! json_encode($customerByDriver->values()->toArray()) !!},
                backgroundColor: '#f59e0b',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 5.0 } } }
    });

    new Chart(document.getElementById('peerAnalyticsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($driverLabels) !!},
            datasets: [{
                label: 'Peer Score',
                data: {!! json_encode($peerByDriver->values()->toArray()) !!},
                backgroundColor: '#6366f1',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 5.0 } } }
    });

    new Chart(document.getElementById('comparativeChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($comparative->keys()->map(fn($id) => $driverLabels[$id] ?? 'Driver')->toArray()) !!},
            datasets: [
                { label: 'Performance', data: {!! json_encode($comparative->pluck('performance')->toArray()) !!}, backgroundColor: '#F44336', borderRadius: 8 },
                { label: 'KPI', data: {!! json_encode($comparative->pluck('kpi')->toArray()) !!}, backgroundColor: '#3b82f6', borderRadius: 8 }
            ]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });
});
</script>
@endsection
