@extends('admin.layouts.admin')

@section('title', 'TripWise — Competency Analytics')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Competency Analytics</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Competency Analytics</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Analyze competency performance across all drivers.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export Report</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Competency Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3>{{ $stats['highest'] }}</h3>
            <p>Highest Competency</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-arrow-down"></i></div>
        <div class="card-info">
            <h3>{{ $stats['lowest'] }}</h3>
            <p>Lowest Competency</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['growth_rate'] }}</h3>
            <p>Competency Growth Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.analytics') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency</label>
            <select name="competency_id" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Competencies</option>
                @foreach($competencies as $comp)
                    <option value="{{ $comp->id }}" {{ request('competency_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Year</label>
            <select name="year" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="2026" {{ request('year') == '2026' ? 'selected' : '' }}>2026</option>
                <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Apply Filters</button>
    </form>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Competency Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="compDistAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Skill Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="skillDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Competency Trends</h3>
        <div class="chart-wrapper">
            <canvas id="compTrendAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Competency Growth</h3>
        <div class="chart-wrapper">
            <canvas id="compGrowthChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Comparative Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="compComparativeChart"></canvas>
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

    new Chart(document.getElementById('compDistAnalyticsChart'), {
        type: 'bar',
        data: {
            labels: ['Safe Driving', 'Customer Service', 'Communication', 'Navigation', 'Professionalism', 'Time Management', 'Vehicle Care'],
            datasets: [{
                label: 'Average Score',
                data: [90, 86, 80, 84, 88, 78, 82],
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    new Chart(document.getElementById('skillDistChart'), {
        type: 'pie',
        data: {
            labels: ['Safe Driving', 'Customer Service', 'Communication', 'Navigation', 'Professionalism', 'Time Management', 'Vehicle Care'],
            datasets: [{ data: [90, 86, 80, 84, 88, 78, 82], backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#8b5cf6', '#f97316', '#14b8a6'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('compTrendAnalyticsChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Average Score',
                data: [82, 83, 84, 83, 85, 86],
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('compGrowthChart'), {
        type: 'line',
        data: {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'Growth %',
                data: [82, 84, 85, 86],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('compComparativeChart'), {
        type: 'bar',
        data: {
            labels: ['Juan Dela Cruz', 'Maria Santos', 'Pedro Reyes', 'Ana Lim', 'Rosa Garcia'],
            datasets: [
                { label: 'Current', data: [90, 92, 88, 86, 72], backgroundColor: '#F44336', borderRadius: 8 },
                { label: 'Target', data: [95, 95, 90, 90, 80], backgroundColor: '#3b82f6', borderRadius: 8 }
            ]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });
});
</script>
@endsection
