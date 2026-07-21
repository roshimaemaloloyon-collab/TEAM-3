@extends('admin.layouts.admin')

@section('title', 'TripWise — Assessments')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Assessments</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Assessments</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Evaluate driver knowledge after completing learning modules.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Assessments Completed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Quiz Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-thumbs-up"></i></div>
        <div class="card-info">
            <h3>{{ $stats['passed'] }}</h3>
            <p>Passed Assessments</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['failed'] }}</h3>
            <p>Failed Assessments</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.learning.assessments') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Assessments Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Assessment Records</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Assessment ID</th>
                    <th>Driver</th>
                    <th>Learning Module</th>
                    <th>Score</th>
                    <th>Passing Score</th>
                    <th>Attempt</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    <tr>
                        <td>#ASM-{{ str_pad($assessment->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $assessment->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->module->title ?? 'N/A' }}</td>
                        <td><strong>{{ $assessment->score ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->passing_score }}</td>
                        <td>{{ $assessment->attempt }} / {{ $assessment->max_attempts }}</td>
                        <td>
                            <span class="item-badge {{ $assessment->status === 'passed' ? 'badge-success' : ($assessment->status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View Results"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Retake Assessment"><i class="fas fa-redo"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $assessments->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Quiz Performance</h3>
        <div class="chart-wrapper">
            <canvas id="quizPerformanceChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Pass vs Fail</h3>
        <div class="chart-wrapper">
            <canvas id="passFailChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
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

    new Chart(document.getElementById('quizPerformanceChart'), {
        type: 'bar',
        data: {
            labels: ['Road Safety', 'Defensive Driving', 'Customer Service', 'Company Policies', 'Traffic Rules'],
            datasets: [{
                label: 'Average Score',
                data: [85, 78, 82, 90, 75],
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    new Chart(document.getElementById('passFailChart'), {
        type: 'doughnut',
        data: {
            labels: ['Passed', 'Failed'],
            datasets: [{ data: [75, 25], backgroundColor: ['#10b981', '#ef4444'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });
});
</script>
@endsection
