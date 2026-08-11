@extends('admin.layouts.admin')

@section('title', 'TripWise — Leadership Potential')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Leadership Potential</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Leadership Potential</h1>
        <p>Evaluate leadership capability and future leadership potential of every driver.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Add Assessment modal coming soon')"><i class="fas fa-plus"></i> Add Assessment</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" id="searchDriver" placeholder="Search driver..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select id="filterStatus" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Status</option>
            <option value="ready">Ready Now</option>
            <option value="1-2">1-2 Years</option>
            <option value="2-3">2-3 Years</option>
            <option value="not-ready">Not Ready</option>
        </select>
        <select id="filterCompetency" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Competencies</option>
            <option value="decision-making">Decision Making</option>
            <option value="communication">Communication</option>
            <option value="team-building">Team Building</option>
            <option value="coaching">Coaching</option>
            <option value="problem-solving">Problem Solving</option>
        </select>
        <select id="filterBranch" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 140px;">
            <option value="">All Branches</option>
            <option value="north">North Branch</option>
            <option value="south">South Branch</option>
            <option value="east">East Branch</option>
            <option value="west">West Branch</option>
        </select>
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button class="btn btn-primary" onclick="applyFilters()" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-search"></i> Search</button>
            <button class="btn btn-secondary" onclick="resetFilters()" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-undo"></i> Reset</button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>4.3/5</h3>
            <p>Average Leadership Score</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +0.3 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>8</h3>
            <p>High Potential Drivers</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +2 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-edit"></i></div>
        <div class="card-info">
            <h3>15</h3>
            <p>Requiring Leadership Development</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fas fa-minus"></i> No change</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>42</h3>
            <p>Assessments Completed</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +8 this quarter</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Leadership Score Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="leadershipScoreChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Leadership Trend</h3>
        <div class="chart-wrapper">
            <canvas id="leadershipTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Top Leadership Candidates</h3>
        <div class="chart-wrapper">
            <canvas id="topCandidatesChart"></canvas>
        </div>
    </div>
</div>

<!-- Leadership Assessment Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Leadership Score</th>
                    <th>Leadership Competency</th>
                    <th>Recommended Role</th>
                    <th>Readiness Level</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $c)
                <tr>
                    <td><strong>#DRV-2026-{{ sprintf('%04d', $c['driver']->id ?? 1) }}</strong></td>
                    <td><strong>{{ $c['driver']->full_name ?? 'Driver' }}</strong></td>
                    <td><strong>{{ $c['performance_score'] }}/5</strong></td>
                    <td>{{ $c['competency_score'] }}% Average</td>
                    <td>{{ $c['recommended_role'] }}</td>
                    <td><span class="status-badge {{ $c['readiness'] === 'High Potential' ? 'badge-success' : 'status-pending' }}">{{ $c['readiness'] }}</span></td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View leadership assessment for {{ $c['driver']->full_name }}')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit assessment')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive assessment')"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">No leadership potential candidates found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
            <span>Rows per page:</span>
            <select style="padding: 0.4rem 0.6rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem;">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.4rem; align-items: center;">
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Previous</button>
            <button class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; min-width: 36px;">1</button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; min-width: 36px;">2</button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; min-width: 36px;">3</button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Next</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function applyFilters() {
    showToast('Filters applied.');
}

function resetFilters() {
    document.getElementById('searchDriver').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterCompetency').value = '';
    document.getElementById('filterBranch').value = '';
    showToast('Filters reset.');
}

document.addEventListener('DOMContentLoaded', function() {
    const scoreCtx = document.getElementById('leadershipScoreChart');
    if (scoreCtx) {
        new Chart(scoreCtx, {
            type: 'bar',
            data: {
                labels: ['4.5-5.0', '4.0-4.4', '3.5-3.9', '3.0-3.4', '<3.0'],
                datasets: [{
                    label: 'Drivers',
                    data: [12, 18, 15, 8, 3],
                    backgroundColor: ['#10b981', '#34d399', '#F44336', '#f59e0b', '#ef4444'],
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

    const trendCtx = document.getElementById('leadershipTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Avg Leadership Score',
                    data: [3.9, 4.0, 4.1, 4.1, 4.2, 4.2, 4.3],
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
                    y: { beginAtZero: false, min: 3.5, max: 5.0, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const candidatesCtx = document.getElementById('topCandidatesChart');
    if (candidatesCtx) {
        new Chart(candidatesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Juan Dela Cruz', 'Maria Santos', 'Pedro Reyes', 'Ana Lim', 'Others'],
                datasets: [{
                    data: [4.8, 4.5, 3.9, 4.6, 3.2],
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
});
</script>
@endsection
