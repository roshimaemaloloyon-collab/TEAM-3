@extends('admin.layouts.admin')

@section('title', 'TripWise — Data Visualization')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.reports.index') }}">Reports & Analytics</a>
    <span>/</span>
    <span>Data Visualization</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Data Visualization</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Interactive charts and graphs for driver performance, competency, training, learning, recognition, and evaluation data.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-database"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total_datasets'] }}</h3>
            <p>Total Datasets</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-layer-group"></i></div>
        <div class="card-info">
            <h3>{{ $stats['categories'] }}</h3>
            <p>Categories</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-calendar"></i></div>
        <div class="card-info">
            <h3>{{ $stats['date_range'] ?? 'N/A' }}</h3>
            <p>Date Range</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.reports.data-visualization') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <select name="chart_type" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="bar" {{ $chartType === 'bar' ? 'selected' : '' }}>Bar Chart</option>
            <option value="pie" {{ $chartType === 'pie' ? 'selected' : '' }}>Pie Chart</option>
            <option value="line" {{ $chartType === 'line' ? 'selected' : '' }}>Line Chart</option>
            <option value="area" {{ $chartType === 'area' ? 'selected' : '' }}>Area Chart</option>
        </select>
        <select name="period" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
        <select name="driver_id" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:180px;">
            <option value="">All Drivers</option>
            @foreach($drivers as $driver)
                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
            @endforeach
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Apply</button>
            <a href="{{ route('admin.reports.data-visualization') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Driver Performance</h3>
        <div class="chart-wrapper">
            <canvas id="driverPerformanceChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Competency Comparison</h3>
        <div class="chart-wrapper">
            <canvas id="competencyComparisonChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompletionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Learning Progress</h3>
        <div class="chart-wrapper">
            <canvas id="learningProgressVizChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Recognition Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="recognitionDistributionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Evaluation Scores</h3>
        <div class="chart-wrapper">
            <canvas id="evaluationScoresChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function exportReport(format) {
    showToast('Exporting visualization as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const driverCtx = document.getElementById('driverPerformanceChart');
    if (driverCtx) {
        new Chart(driverCtx, {
            type: '{{ $chartType }}',
            data: {
                labels: ['Juan Dela Cruz', 'Maria Santos', 'Pedro Reyes', 'Ana Lim', 'Rosa Garcia'],
                datasets: [{
                    label: 'Performance Score',
                    data: [4.8, 4.6, 4.4, 4.7, 4.2],
                    backgroundColor: ['#F44336', '#EF5350', '#D32F2F', '#FFCDD2', '#B71C1C'],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: false, min: 3.5, max: 5 } },
                plugins: { legend: { display: false } }
            }
        });
    }

    const competencyCtx = document.getElementById('competencyComparisonChart');
    if (competencyCtx) {
        new Chart(competencyCtx, {
            type: 'radar',
            data: {
                labels: ['Technical', 'Communication', 'Teamwork', 'Safety', 'Reliability', 'Professionalism'],
                datasets: [{
                    label: 'Average Score',
                    data: [4.5, 4.3, 4.6, 4.8, 4.4, 4.7],
                    backgroundColor: 'rgba(244, 67, 54, 0.2)',
                    borderColor: '#F44336',
                    pointBackgroundColor: '#F44336',
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, max: 5 } } }
        });
    }

    const trainingCtx = document.getElementById('trainingCompletionChart');
    if (trainingCtx) {
        new Chart(trainingCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [
                    { label: 'Completed', data: [20, 35, 50, 78], borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', fill: true, tension: 0.4 },
                    { label: 'Remaining', data: [80, 65, 50, 22], borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.1)', fill: true, tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } }, plugins: { legend: { display: true } } }
        });
    }

    const learningCtx = document.getElementById('learningProgressVizChart');
    if (learningCtx) {
        new Chart(learningCtx, {
            type: 'bar',
            data: {
                labels: ['Module 1', 'Module 2', 'Module 3', 'Module 4', 'Module 5'],
                datasets: [{
                    label: 'Progress %',
                    data: [100, 80, 60, 40, 20],
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } }, plugins: { legend: { display: false } } }
        });
    }

    const recognitionCtx = document.getElementById('recognitionDistributionChart');
    if (recognitionCtx) {
        new Chart(recognitionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Awards', 'Badges', 'Certificates', 'Leaderboard'],
                datasets: [{ data: [30, 45, 15, 10], backgroundColor: ['#F44336', '#EF5350', '#D32F2F', '#FFCDD2'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }

    const evalCtx = document.getElementById('evaluationScoresChart');
    if (evalCtx) {
        new Chart(evalCtx, {
            type: 'bar',
            data: {
                labels: ['Professionalism', 'Communication', 'Teamwork', 'Safety', 'Reliability', 'Respectfulness'],
                datasets: [{
                    label: 'Average Score',
                    data: [4.5, 4.3, 4.6, 4.8, 4.4, 4.7],
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: false } } }
        });
    }
});
</script>
@endpush
