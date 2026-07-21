@extends('admin.layouts.admin')

@section('title', 'TripWise — Learning Management')

@section('content')
<div class="page-header">
    <div>
        <h1>Learning Management</h1>
        <p>Create, manage, and track learning modules and courses for drivers.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Create Module</a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-book-open"></i></div>
        <div class="card-info">
            <h3>18</h3>
            <p>Total Modules</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>245</h3>
            <p>Active Learners</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-percentage"></i></div>
        <div class="card-info">
            <h3>78%</h3>
            <p>Avg Completion Rate</p>
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Module Categories</h3>
        <div class="chart-wrapper">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Enrollment by Month</h3>
        <div class="chart-wrapper">
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> Learning Modules</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Category</th>
                    <th>Duration</th>
                    <th>Enrolled</th>
                    <th>Completion</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Defensive Driving Basics</strong></td>
                    <td>Safety</td>
                    <td>2 hours</td>
                    <td>120</td>
                    <td>85%</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage</button></td>
                </tr>
                <tr>
                    <td><strong>Customer Service Excellence</strong></td>
                    <td>Soft Skills</td>
                    <td>3 hours</td>
                    <td>98</td>
                    <td>72%</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage</button></td>
                </tr>
                <tr>
                    <td><strong>Vehicle Inspection 101</strong></td>
                    <td>Technical</td>
                    <td>1.5 hours</td>
                    <td>85</td>
                    <td>90%</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx1 = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Safety', 'Technical', 'Soft Skills', 'Compliance'],
            datasets: [{
                data: [6, 5, 4, 3],
                 backgroundColor: ['#1c1c1e', '#2c2c2e', '#faf9f6', '#f1efe9'],
                borderWidth: 2, borderColor: '#FFFFFF'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } } }
        }
    });

    const ctx2 = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Enrollments',
                data: [45, 52, 48, 61, 55, 68],
                 backgroundColor: '#F44336', borderRadius: 8
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
