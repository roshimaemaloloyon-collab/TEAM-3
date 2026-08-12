@extends('admin.layouts.admin')

@section('title', 'TripWise — Analytics Dashboard')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.reports.index') }}">Reports & Analytics</a>
    <span>/</span>
    <span>Analytics Dashboard</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Analytics Dashboard</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Real-time analytics across all system modules. Monitor KPIs, trends, and performance metrics.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('excel')"><i class="fas fa-download"></i> Export Analytics</button>
<!-- Analytics Hub Quick Links -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1.25rem;">
    <h3 style="font-size:1.1rem;margin:0 0 1rem;color:var(--primary);display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-th-large"></i> System Analytics Modules Hub
    </h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:0.85rem;">
        <a href="{{ route('admin.performance.analytics') }}" class="btn btn-secondary" style="display:flex;align-items:center;justify-content:flex-start;gap:0.75rem;padding:0.75rem 1rem;text-decoration:none;border-radius:10px;transition:all 0.2s;">
            <i class="fas fa-chart-line" style="color:#F44336;font-size:1.1rem;"></i>
            <span>Performance Analytics</span>
        </a>
        <a href="{{ route('admin.competency.analytics') }}" class="btn btn-secondary" style="display:flex;align-items:center;justify-content:flex-start;gap:0.75rem;padding:0.75rem 1rem;text-decoration:none;border-radius:10px;transition:all 0.2s;">
            <i class="fas fa-brain" style="color:#3b82f6;font-size:1.1rem;"></i>
            <span>Competency Analytics</span>
        </a>
        <a href="{{ route('admin.learning.analytics') }}" class="btn btn-secondary" style="display:flex;align-items:center;justify-content:flex-start;gap:0.75rem;padding:0.75rem 1rem;text-decoration:none;border-radius:10px;transition:all 0.2s;">
            <i class="fas fa-book-open" style="color:#f59e0b;font-size:1.1rem;"></i>
            <span>Learning Analytics</span>
        </a>
        <a href="{{ route('admin.training.analytics') }}" class="btn btn-secondary" style="display:flex;align-items:center;justify-content:flex-start;gap:0.75rem;padding:0.75rem 1rem;text-decoration:none;border-radius:10px;transition:all 0.2s;">
            <i class="fas fa-chalkboard-teacher" style="color:#10b981;font-size:1.1rem;"></i>
            <span>Training Analytics</span>
        </a>
        <a href="{{ route('admin.evaluation.analytics') }}" class="btn btn-secondary" style="display:flex;align-items:center;justify-content:flex-start;gap:0.75rem;padding:0.75rem 1rem;text-decoration:none;border-radius:10px;transition:all 0.2s;">
            <i class="fas fa-users" style="color:#8b5cf6;font-size:1.1rem;"></i>
            <span>Evaluation Analytics</span>
        </a>
        <a href="{{ route('admin.recognition.analytics') }}" class="btn btn-secondary" style="display:flex;align-items:center;justify-content:flex-start;gap:0.75rem;padding:0.75rem 1rem;text-decoration:none;border-radius:10px;transition:all 0.2s;">
            <i class="fas fa-trophy" style="color:#ec4899;font-size:1.1rem;"></i>
            <span>Recognition Analytics</span>
        </a>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['avg_performance'], 2) }}</h3>
            <p>Average Performance Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-brain"></i></div>
        <div class="card-info">
            <h3>{{ $stats['competency_completion'] }}%</h3>
            <p>Competency Completion</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-book-open"></i></div>
        <div class="card-info">
            <h3>{{ $stats['learning_completion'] }}%</h3>
            <p>Learning Completion</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="card-info">
            <h3>{{ $stats['training_completion'] }}%</h3>
            <p>Training Completion</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon teal"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['peer_evaluation_score'], 2) }}</h3>
            <p>Peer Evaluation Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon gold"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3>{{ $stats['recognition_count'] }}</h3>
            <p>Recognition Count</p>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Monthly Performance Trend</h3>
        <div class="chart-wrapper">
            <canvas id="performanceTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> KPI Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="kpiDistributionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Competency Growth</h3>
        <div class="chart-wrapper">
            <canvas id="competencyGrowthChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Learning Progress</h3>
        <div class="chart-wrapper">
            <canvas id="learningProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Training Progress</h3>
        <div class="chart-wrapper">
            <canvas id="trainingProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Recognition Trend</h3>
        <div class="chart-wrapper">
            <canvas id="recognitionTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Peer Evaluation Trend</h3>
        <div class="chart-wrapper">
            <canvas id="peerEvaluationTrendChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function exportReport(format) {
    showToast('Exporting analytics as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const createLineChart = (id, label, data, color) => {
        const ctx = document.getElementById(id);
        if (!ctx) return;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{ label, data, borderColor: color, backgroundColor: color + '20', fill: true, tension: 0.4 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: false, min: 3, max: 5 } },
                plugins: { legend: { display: false } }
            }
        });
    };

    const createBarChart = (id, label, data, color) => {
        const ctx = document.getElementById(id);
        if (!ctx) return;
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{ label, data, backgroundColor: color, borderRadius: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    };

    createLineChart('performanceTrendChart', 'Performance', [4.2, 4.3, 4.1, 4.4, 4.5, 4.6], '#F44336');
    createBarChart('kpiDistributionChart', 'KPI', [85, 78, 92, 88, 76, 90], '#F44336');

    const competencyCtx = document.getElementById('competencyGrowthChart');
    if (competencyCtx) {
        new Chart(competencyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    { label: 'Technical', data: [3.5, 3.8, 4.0, 4.2, 4.3, 4.5], borderColor: '#F44336', tension: 0.4 },
                    { label: 'Soft Skills', data: [3.2, 3.5, 3.7, 3.9, 4.0, 4.2], borderColor: '#3b82f6', tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 5 } }, plugins: { legend: { display: true } } }
        });
    }

    const learningCtx = document.getElementById('learningProgressChart');
    if (learningCtx) {
        new Chart(learningCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Not Started'],
                datasets: [{ data: [65, 25, 10], backgroundColor: ['#10b981', '#f59e0b', '#e2e8f0'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }

    createLineChart('trainingProgressChart', 'Training', [70, 75, 78, 80, 82, 85], '#F44336');
    createBarChart('recognitionTrendChart', 'Recognition', [8, 12, 10, 15, 18, 14], '#F44336');
    createLineChart('peerEvaluationTrendChart', 'Peer Eval', [4.0, 4.1, 4.2, 4.3, 4.4, 4.5], '#F44336');
});
</script>
@endpush
