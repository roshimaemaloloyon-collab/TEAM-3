@extends('admin.layouts.admin')

@section('title', 'TripWise — Learning Analytics')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Learning Analytics</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Learning Analytics</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Analyze learning performance and progress.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export Report</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completion_rate'] }}</h3>
            <p>Overall Completion Rate</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-chart-bar"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_quiz_score'] }}</h3>
            <p>Average Quiz Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-tasks"></i></div>
        <div class="card-info">
            <h3>{{ $stats['learning_progress'] }}</h3>
            <p>Learning Progress</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-area"></i></div>
        <div class="card-info">
            <h3>{{ $stats['module_effectiveness'] }}</h3>
            <p>Module Effectiveness</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.learning.analytics') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
            <select name="driver" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Drivers</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Learning Module</label>
            <select name="module" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ request('module') == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
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
        <h3><i class="fas fa-chart-bar"></i> Learning Progress</h3>
        <div class="chart-wrapper">
            <canvas id="learningProgressAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Module Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="moduleDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Completion Trend</h3>
        <div class="chart-wrapper">
            <canvas id="completionTrendAnalyticsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Learning Effectiveness</h3>
        <div class="chart-wrapper">
            <canvas id="learningEffectivenessChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Comparative Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="learningComparativeChart"></canvas>
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

    new Chart(document.getElementById('learningProgressAnalyticsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($progressAnalytics->pluck('module.title')->toArray()) !!},
            datasets: [{
                label: 'Completion %',
                data: {!! json_encode($progressAnalytics->pluck('avg_progress')->toArray()) !!},
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    new Chart(document.getElementById('moduleDistChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($moduleDist->pluck('module.title')->toArray()) !!},
            datasets: [{ data: {!! json_encode($moduleDist->pluck('total')->toArray()) !!}, backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#8b5cf6'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('completionTrendAnalyticsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($completionTrend->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Completion %',
                data: {!! json_encode($completionTrend->pluck('total')->toArray()) !!},
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('learningEffectivenessChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($effectiveness->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Effectiveness %',
                data: {!! json_encode($effectiveness->pluck('avg_progress')->toArray()) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#3b82f6'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('learningComparativeChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($comparative->keys()->map(fn($id) => $drivers->firstWhere('id', $id)?->name ?? 'Driver')->toArray()) !!},
            datasets: [
                { label: 'Completion %', data: {!! json_encode($comparative->pluck('completion')->toArray()) !!}, backgroundColor: '#F44336', borderRadius: 8 },
                { label: 'Quiz Score', data: {!! json_encode($comparative->pluck('quiz')->toArray()) !!}, backgroundColor: '#3b82f6', borderRadius: 8 }
            ]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });
});
</script>
@endsection
