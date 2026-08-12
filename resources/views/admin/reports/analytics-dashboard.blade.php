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
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.5rem;color:var(--primary);margin:0 0 0.25rem;">Analytics Dashboard</h1>
        <p style="color:var(--text-muted);font-size:0.85rem;margin:0;">Real-time analytics across all system modules. Monitor KPIs, trends, and performance metrics.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('excel')"><i class="fas fa-download"></i> Export Analytics</button>
</div>
<!-- Unified System Analytics Module Filter Tabs -->
<div class="table-card" style="margin-bottom:1.5rem;padding:0.75rem 1rem;">
    <div style="display:flex;gap:0.5rem;overflow-x:auto;align-items:center;">
        <button type="button" class="btn btn-primary analytics-tab-btn active" onclick="filterAnalytics('all', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-th-large"></i> All System Analytics
        </button>
        <button type="button" class="btn btn-secondary analytics-tab-btn" onclick="filterAnalytics('performance', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-chart-line" style="color:#F44336;"></i> Performance Analytics
        </button>
        <button type="button" class="btn btn-secondary analytics-tab-btn" onclick="filterAnalytics('competency', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-brain" style="color:#3b82f6;"></i> Competency Analytics
        </button>
        <button type="button" class="btn btn-secondary analytics-tab-btn" onclick="filterAnalytics('learning', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-book-open" style="color:#f59e0b;"></i> Learning Analytics
        </button>
        <button type="button" class="btn btn-secondary analytics-tab-btn" onclick="filterAnalytics('training', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-chalkboard-teacher" style="color:#10b981;"></i> Training Analytics
        </button>
        <button type="button" class="btn btn-secondary analytics-tab-btn" onclick="filterAnalytics('evaluation', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-users" style="color:#8b5cf6;"></i> Evaluation Analytics
        </button>
        <button type="button" class="btn btn-secondary analytics-tab-btn" onclick="filterAnalytics('recognition', this)" style="padding:0.6rem 1.25rem;font-size:0.85rem;border-radius:8px;">
            <i class="fas fa-trophy" style="color:#ec4899;"></i> Recognition Analytics
        </button>
    </div>
</div>

<!-- Top Summary Grid (Full Width, 6 Columns) -->
<div class="summary-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:0.75rem;margin-bottom:1rem;">
    <div class="summary-card" data-module="performance" style="padding:0.85rem 1rem;">
        <div class="card-icon blue" style="width:40px;height:40px;font-size:1rem;border-radius:10px;"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3 style="font-size:1.25rem;margin:0;">{{ number_format($stats['avg_performance'], 2) }}</h3>
            <p style="font-size:0.75rem;margin:0;">Avg Performance</p>
        </div>
    </div>
    <div class="summary-card" data-module="competency" style="padding:0.85rem 1rem;">
        <div class="card-icon green" style="width:40px;height:40px;font-size:1rem;border-radius:10px;"><i class="fas fa-brain"></i></div>
        <div class="card-info">
            <h3 style="font-size:1.25rem;margin:0;">{{ $stats['competency_completion'] }}%</h3>
            <p style="font-size:0.75rem;margin:0;">Competency Rate</p>
        </div>
    </div>
    <div class="summary-card" data-module="learning" style="padding:0.85rem 1rem;">
        <div class="card-icon orange" style="width:40px;height:40px;font-size:1rem;border-radius:10px;"><i class="fas fa-book-open"></i></div>
        <div class="card-info">
            <h3 style="font-size:1.25rem;margin:0;">{{ $stats['learning_completion'] }}%</h3>
            <p style="font-size:0.75rem;margin:0;">Learning Rate</p>
        </div>
    </div>
    <div class="summary-card" data-module="training" style="padding:0.85rem 1rem;">
        <div class="card-icon purple" style="width:40px;height:40px;font-size:1rem;border-radius:10px;"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="card-info">
            <h3 style="font-size:1.25rem;margin:0;">{{ $stats['training_completion'] }}%</h3>
            <p style="font-size:0.75rem;margin:0;">Training Rate</p>
        </div>
    </div>
    <div class="summary-card" data-module="evaluation" style="padding:0.85rem 1rem;">
        <div class="card-icon teal" style="width:40px;height:40px;font-size:1rem;border-radius:10px;"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3 style="font-size:1.25rem;margin:0;">{{ number_format($stats['peer_evaluation_score'], 2) }}</h3>
            <p style="font-size:0.75rem;margin:0;">Peer Evaluation</p>
        </div>
    </div>
    <div class="summary-card" data-module="recognition" style="padding:0.85rem 1rem;">
        <div class="card-icon gold" style="width:40px;height:40px;font-size:1rem;border-radius:10px;"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3 style="font-size:1.25rem;margin:0;">{{ $stats['recognition_count'] }}</h3>
            <p style="font-size:0.75rem;margin:0;">Recognition Badges</p>
        </div>
    </div>
</div>

<!-- Charts Grid (Compact Full Width, 3 Columns) -->
<div class="charts-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:0.75rem;margin-bottom:1rem;">
    <div class="chart-card" data-module="performance" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-line"></i> Monthly Performance Trend</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="performanceTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card" data-module="performance" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-bar"></i> KPI Distribution</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="kpiDistributionChart"></canvas>
        </div>
    </div>
    <div class="chart-card" data-module="competency" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-area"></i> Competency Growth</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="competencyGrowthChart"></canvas>
        </div>
    </div>
    <div class="chart-card" data-module="learning" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-pie"></i> Learning Progress</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="learningProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card" data-module="training" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-line"></i> Training Progress</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="trainingProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card" data-module="recognition" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-bar"></i> Recognition Trend</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="recognitionTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card" data-module="evaluation" style="padding:0.75rem 1rem;">
        <h3 style="font-size:0.9rem;margin-bottom:0.5rem;"><i class="fas fa-chart-line"></i> Peer Evaluation Trend</h3>
        <div class="chart-wrapper" style="height:175px;">
            <canvas id="peerEvaluationTrendChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterAnalytics(moduleName, btn) {
    document.querySelectorAll('.analytics-tab-btn').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-secondary');
    });
    btn.classList.remove('btn-secondary');
    btn.classList.add('btn-primary', 'active');

    const summaryCards = document.querySelectorAll('.summary-card[data-module]');
    const chartCards = document.querySelectorAll('.chart-card[data-module]');

    summaryCards.forEach(card => {
        if (moduleName === 'all' || card.getAttribute('data-module') === moduleName) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });

    chartCards.forEach(card => {
        if (moduleName === 'all' || card.getAttribute('data-module') === moduleName) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

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
