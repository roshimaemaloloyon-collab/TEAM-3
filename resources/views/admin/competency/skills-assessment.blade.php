@extends('admin.layouts.admin')

@section('title', 'TripWise — Skills Assessment')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Skills Assessment</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Skills Assessment</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Assess every driver's competencies based on predefined competency standards.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('assessModal')"><i class="fas fa-plus"></i> New Assessment</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Competency Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $stats['drivers_assessed'] }}</h3>
            <p>Drivers Assessed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Assessments Pending</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completion_rate'] }}</h3>
            <p>Competency Completion Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.assessments') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency</label>
            <select name="competency_id" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Competencies</option>
                @foreach($competencies as $comp)
                    <option value="{{ $comp->id }}" {{ request('competency_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="assessed" {{ request('status') === 'assessed' ? 'selected' : '' }}>Assessed</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Assessment Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-tasks"></i> Skills Assessment</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Competency</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Assessment Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    <tr>
                        <td>#DRV-{{ str_pad($assessment->driver_id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $assessment->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->competency->name ?? 'N/A' }}</td>
                        <td><strong>{{ $assessment->score ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="item-badge {{ $assessment->status === 'assessed' ? 'badge-success' : ($assessment->status === 'pending' ? 'badge-warning' : 'badge-info') }}">
                                {{ ucfirst($assessment->status) }}
                            </span>
                        </td>
                        <td>{{ $assessment->assessed_at ? \Carbon\Carbon::parse($assessment->assessed_at)->format('M d, Y') : 'N/A' }}</td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-primary" title="Assess"><i class="fas fa-clipboard-check"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessments found.</td></tr>
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
        <h3><i class="fas fa-chart-pie"></i> Competency Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="compDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Skills Comparison</h3>
        <div class="chart-wrapper">
            <canvas id="skillsCompChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
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

    new Chart(document.getElementById('compDistChart'), {
        type: 'pie',
        data: {
            labels: ['Excellent', 'Proficient', 'Developing', 'Needs Coaching'],
            datasets: [{
                data: [3, 2, 1, 1],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('skillsCompChart'), {
        type: 'bar',
        data: {
            labels: ['Safe Driving', 'Customer Service', 'Communication', 'Navigation', 'Professionalism', 'Time Mgmt', 'Vehicle Care'],
            datasets: [{
                label: 'Average Score',
                data: [90, 86, 80, 84, 88, 78, 82],
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 100 } } }
    });
});
</script>
@endsection
