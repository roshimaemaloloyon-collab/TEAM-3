@extends('admin.layouts.admin')

@section('title', 'TripWise — Succession Planning')

@section('content')
<div class="page-header">
    <div>
        <h1>Succession Planning</h1>
        <p>Map critical roles, manage talent pools, and mitigate organisational risk.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Add Role</a>
</div>

<div class="section-grid">
    <div class="section-card">
        <h3><i class="fas fa-exclamation-circle"></i> High Risk Roles</h3>
        <div class="list-item">
            <div class="item-icon red" style="background:#fee2e2; color:#991b1b;"><i class="fas fa-hard-hat"></i></div>
            <div class="item-content">
                <div class="item-title">Senior Route Supervisor</div>
                <div class="item-subtitle">Incumbent: Roberto Cruz • 3 successors identified</div>
            </div>
            <span class="item-badge badge-warning">High Risk</span>
        </div>
        <div class="list-item">
            <div class="item-icon blue"><i class="fas fa-bus"></i></div>
            <div class="item-content">
                <div class="item-title">Fleet Operations Manager</div>
                <div class="item-subtitle">Incumbent: Carmen Dee • 2 successors identified</div>
            </div>
            <span class="item-badge badge-info">Key Role</span>
        </div>
    </div>

    <div class="section-card">
        <h3><i class="fas fa-chart-line"></i> Succession Readiness</h3>
        <div class="chart-wrapper" style="height: 220px;">
            <canvas id="successionChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('successionChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ready Now', '1-2 Years', '2-3 Years', 'Not Ready'],
            datasets: [{
                label: 'Drivers',
                data: [8, 15, 22, 5],
                backgroundColor: ['#10b981', '#FCF5EB', '#f5e6c8', '#ef4444'],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });
});
</script>
@endsection
