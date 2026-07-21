@extends('admin.layouts.admin')

@section('title', 'TripWise — Driver Performance')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.performance.index') }}">Performance Management</a>
    <span>/</span>
    <span>Driver Performance</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Driver Performance</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor and evaluate each driver's operational performance.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Performance Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3>{{ $stats['top_drivers'] }}</h3>
            <p>Top Performing Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-exclamation-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['needs_improvement'] }}</h3>
            <p>Drivers Needing Improvement</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-star"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_rating'] }}</h3>
            <p>Average Customer Rating</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.performance.drivers') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="excellent" {{ request('status') === 'excellent' ? 'selected' : '' }}>Excellent</option>
                <option value="good" {{ request('status') === 'good' ? 'selected' : '' }}>Good</option>
                <option value="average" {{ request('status') === 'average' ? 'selected' : '' }}>Average</option>
                <option value="needs_improvement" {{ request('status') === 'needs_improvement' ? 'selected' : '' }}>Needs Improvement</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Driver Performance Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-users"></i> Driver Performance Overview</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Customer Rating</th>
                    <th>Peer Evaluation</th>
                    <th>Attendance</th>
                    <th>Trip Completion</th>
                    <th>Cancellation Rate</th>
                    <th>Safety Score</th>
                    <th>Overall Score</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($performances as $perf)
                    <tr>
                        <td>#DRV-{{ str_pad($perf->driver_id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $perf->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $perf->customer_rating ?? 'N/A' }}</td>
                        <td>{{ $perf->peer_evaluation_score ?? 'N/A' }}</td>
                        <td>{{ $perf->attendance_rate ?? 'N/A' }}%</td>
                        <td>{{ $perf->trip_completion_rate ?? 'N/A' }}%</td>
                        <td>{{ $perf->cancellation_rate ?? 'N/A' }}%</td>
                        <td>{{ $perf->safety_score ?? 'N/A' }}/5</td>
                        <td><strong>{{ $perf->overall_score ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="item-badge {{ $perf->performance_status === 'excellent' ? 'badge-success' : ($perf->performance_status === 'good' ? 'badge-info' : ($perf->performance_status === 'needs_improvement' ? 'badge-danger' : 'badge-warning')) }}">
                                {{ ucfirst(str_replace('_', ' ', $perf->performance_status)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" style="text-align:center;color:var(--text-muted);padding:2rem;">No performance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $performances->links() }}
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;background:var(--charcoal);color:#fff;padding:0.75rem 1.25rem;border-radius:0.75rem;box-shadow:0 8px 20px rgba(0,0,0,0.2);z-index:3000;align-items:center;gap:0.75rem;font-size:0.85rem;font-family:'Inter',sans-serif;">
    <i class="fas fa-check-circle" style="color:var(--success);"></i>
    <span id="toastMessage"></span>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Performance Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="perfDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Driver Ranking</h3>
        <div class="chart-wrapper">
            <canvas id="driverRankChart"></canvas>
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

    new Chart(document.getElementById('perfDistChart'), {
        type: 'pie',
        data: {
            labels: ['Excellent', 'Good', 'Average', 'Needs Improvement'],
            datasets: [{
                data: [{{ $stats['top_drivers'] }}, 5, 3, {{ $stats['needs_improvement'] }}],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('driverRankChart'), {
        type: 'bar',
        data: {
            labels: ['Juan Dela Cruz', 'Maria Santos', 'Pedro Reyes', 'Ana Lim', 'Rosa Garcia'],
            datasets: [{
                label: 'Overall Score',
                data: [4.9, 4.8, 4.6, 4.5, 4.3],
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 5.0 } } }
    });
});
</script>
@endsection
