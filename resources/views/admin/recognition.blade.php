@extends('admin.layouts.admin')

@section('title', 'TripWise — Social Recognition')

@section('content')
<div class="page-header">
    <div>
        <h1>Social Recognition</h1>
        <p>View and manage recognition posts, shout-outs, and achievements across the organization.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Create Recognition</a>
</div>

<div class="section-grid">
    <div class="section-card">
        <h3><i class="fas fa-trophy"></i> Recent Recognition</h3>
        <div class="list-item">
            <div class="item-icon gold"><i class="fas fa-medal"></i></div>
            <div class="item-content">
                <div class="item-title">Safe Driver of the Month</div>
                <div class="item-subtitle">Awarded to Juan Dela Cruz • June 2026</div>
            </div>
            <span class="item-badge badge-purple">Badge</span>
        </div>
        <div class="list-item">
            <div class="item-icon orange"><i class="fas fa-star"></i></div>
            <div class="item-content">
                <div class="item-title">Customer Satisfaction Award</div>
                <div class="item-subtitle">Awarded to Maria Santos • Q2 2026</div>
            </div>
            <span class="item-badge badge-warning">Award</span>
        </div>
        <div class="list-item">
            <div class="item-icon blue"><i class="fas fa-clock"></i></div>
            <div class="item-content">
                <div class="item-title">Perfect Attendance Streak</div>
                <div class="item-subtitle">Awarded to Pedro Reyes • 6 months</div>
            </div>
            <span class="item-badge badge-info">Streak</span>
        </div>
    </div>

    <div class="section-card">
        <h3><i class="fas fa-chart-bar"></i> Recognition by Category</h3>
        <div class="chart-wrapper" style="height: 220px;">
            <canvas id="recognitionChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('recognitionChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Safety', 'Customer Focus', 'Teamwork', 'Innovation', 'Punctuality'],
            datasets: [{
                label: 'Recognition Count',
                data: [45, 38, 32, 18, 28],
                 backgroundColor: '#F44336',
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
