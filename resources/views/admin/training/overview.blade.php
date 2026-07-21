@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Overview')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <span>Training Management</span>
    <span>/</span>
    <span>Overview</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Training Overview</h1>
        <p>Monitor training programs, registrations, attendance, evaluations, certificates, and training history.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Create Training modal coming soon')"><i class="fas fa-plus"></i> Create Training</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- KPI Dashboard -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-graduation-cap"></i></div>
        <div class="card-info">
            <h3>42</h3>
            <p>Total Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +12% vs last month</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-calendar-check"></i></div>
        <div class="card-info">
            <h3>8</h3>
            <p>Upcoming Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fas fa-clock"></i> Next 7 days</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-spinner"></i></div>
        <div class="card-info">
            <h3>3</h3>
            <p>Ongoing Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--info);"><i class="fas fa-circle"></i> In progress</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>31</h3>
            <p>Completed Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +5 this month</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon teal"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>156</h3>
            <p>Registered Drivers</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +23 new</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon gold"><i class="fas fa-percentage"></i></div>
        <div class="card-info">
            <h3>94%</h3>
            <p>Attendance Rate</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +2.1%</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-certificate"></i></div>
        <div class="card-info">
            <h3>142</h3>
            <p>Certificates Issued</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +18 this month</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>4.5/5</h3>
            <p>Average Evaluation Score</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +0.3</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Monthly Training Trend</h3>
        <div class="chart-wrapper">
            <canvas id="trainingTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Training Categories</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCategoriesChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Attendance Rate</h3>
        <div class="chart-wrapper">
            <canvas id="attendanceRateChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-tasks"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompletionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-certificate"></i> Certificates Issued</h3>
        <div class="chart-wrapper">
            <canvas id="certificatesChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-building"></i> Branch Training Comparison</h3>
        <div class="chart-wrapper">
            <canvas id="branchComparisonChart"></canvas>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('admin.training.programs') }}" class="action-btn">
        <i class="fas fa-book"></i>
        <span>Training Programs</span>
    </a>
    <a href="{{ route('admin.training.schedule') }}" class="action-btn">
        <i class="fas fa-calendar-alt"></i>
        <span>Training Schedule</span>
    </a>
    <a href="{{ route('admin.training.registrations') }}" class="action-btn">
        <i class="fas fa-user-plus"></i>
        <span>Registrations</span>
    </a>
    <a href="{{ route('admin.training.attendance') }}" class="action-btn">
        <i class="fas fa-clipboard-check"></i>
        <span>Attendance</span>
    </a>
    <a href="{{ route('admin.training.evaluations') }}" class="action-btn">
        <i class="fas fa-star"></i>
        <span>Evaluations</span>
    </a>
    <a href="{{ route('admin.training.certificates') }}" class="action-btn">
        <i class="fas fa-certificate"></i>
        <span>Certificates</span>
    </a>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trainingTrendChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Training Sessions',
                    data: [5, 7, 6, 8, 9, 7, 8],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F44336'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const catCtx = document.getElementById('trainingCategoriesChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: ['Defensive Driving', 'Customer Service', 'Safety', 'Company Policies', 'Vehicle Maintenance'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: ['#F44336', '#1c1c1e', '#2c2c2e', '#faf9f6', '#f1efe9'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                    }
                }
            }
        });
    }

    const attCtx = document.getElementById('attendanceRateChart');
    if (attCtx) {
        new Chart(attCtx, {
            type: 'bar',
            data: {
                labels: ['Defensive Driving', 'Customer Service', 'Safety', 'Company Policies', 'Vehicle Maintenance'],
                datasets: [{
                    label: 'Attendance %',
                    data: [95, 88, 92, 85, 90],
                    backgroundColor: '#F44336',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const compCtx = document.getElementById('trainingCompletionChart');
    if (compCtx) {
        new Chart(compCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Ongoing', 'Upcoming'],
                datasets: [{
                    data: [31, 3, 8],
                    backgroundColor: ['#F44336', '#1c1c1e', '#faf9f6'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                    }
                }
            }
        });
    }

    const certCtx = document.getElementById('certificatesChart');
    if (certCtx) {
        new Chart(certCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Certificates Issued',
                    data: [12, 15, 18, 20, 22, 25, 28],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F44336'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const branchCtx = document.getElementById('branchComparisonChart');
    if (branchCtx) {
        new Chart(branchCtx, {
            type: 'bar',
            data: {
                labels: ['North', 'South', 'East', 'West', 'Central'],
                datasets: [{
                    label: 'Completed Trainings',
                    data: [12, 10, 8, 7, 9],
                    backgroundColor: '#F44336',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endsection
