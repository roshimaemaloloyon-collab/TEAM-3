@extends('admin.layouts.admin')

@section('title', 'TripWise — Assessment Results')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Assessment Results</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Assessment Results</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Display competency assessment results and identify areas for improvement.</p>
    </div>
    <button class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print Assessment</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3>{{ $stats['high_competency'] }}</h3>
            <p>High Competency Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-exclamation-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['needs_improvement'] }}</h3>
            <p>Drivers Requiring Improvement</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Assessment Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-puzzle-piece"></i></div>
        <div class="card-info">
            <h3>{{ $stats['skill_gaps'] }}</h3>
            <p>Skill Gap Count</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.results') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="assessed" {{ request('status') === 'assessed' ? 'selected' : '' }}>Assessed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Results Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Assessment Results</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Competency Score</th>
                    <th>Strengths</th>
                    <th>Weaknesses</th>
                    <th>Skill Gaps</th>
                    <th>Assessment Date</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr>
                        <td><strong>{{ $result->driver->name ?? 'N/A' }}</strong></td>
                        <td><strong>{{ $result->score ?? 'N/A' }}</strong></td>
                        <td>
                            @php
                                $strengths = ['Safe Driving', 'Professionalism'];
                                echo implode(', ', $strengths);
                            @endphp
                        </td>
                        <td>
                            @php
                                $weaknesses = ['Time Management'];
                                echo implode(', ', $weaknesses);
                            @endphp
                        </td>
                        <td>
                            @php
                                $gaps = ['Navigation'];
                                echo implode(', ', $gaps);
                            @endphp
                        </td>
                        <td>{{ $result->assessed_at ? \Carbon\Carbon::parse($result->assessed_at)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="item-badge {{ $result->status === 'assessed' ? 'badge-success' : ($result->status === 'pending' ? 'badge-warning' : 'badge-info') }}">
                                {{ ucfirst($result->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-secondary" title="Print" onclick="window.print()"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessment results found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $results->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Skill Gap Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="skillGapChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Competency Trend</h3>
        <div class="chart-wrapper">
            <canvas id="compTrendChart"></canvas>
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

    new Chart(document.getElementById('skillGapChart'), {
        type: 'doughnut',
        data: {
            labels: ['Safe Driving', 'Customer Service', 'Communication', 'Navigation', 'Professionalism', 'Time Management', 'Vehicle Care'],
            datasets: [{
                data: [90, 86, 80, 84, 88, 78, 82],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#8b5cf6', '#f97316', '#14b8a6']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('compTrendChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Average Score',
                data: [82, 83, 84, 83, 85, 86],
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });
});
</script>
@endsection
