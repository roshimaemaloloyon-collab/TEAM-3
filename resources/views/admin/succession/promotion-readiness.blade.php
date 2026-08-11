@extends('admin.layouts.admin')

@section('title', 'TripWise — Promotion Readiness')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Promotion Readiness</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Promotion Readiness</h1>
        <p>Evaluate whether a driver is qualified and ready for promotion.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Generate Recommendation modal coming soon')"><i class="fas fa-plus"></i> Generate Recommendation</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" id="searchDriver" placeholder="Search driver..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select id="filterEligibility" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Eligibility</option>
            <option value="eligible">Eligible</option>
            <option value="not-eligible">Not Eligible</option>
            <option value="under-review">Under Review</option>
        </select>
        <select id="filterApproval" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Approval Status</option>
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="rejected">Rejected</option>
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
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['ready'] ?? 12 }}</h3>
            <p>Promotion Ready</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +4 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-hourglass-half"></i></div>
        <div class="card-info">
            <h3>{{ $stats['nearly_ready'] ?? 8 }}</h3>
            <p>Nearly Ready</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fas fa-minus"></i> Active evaluation</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['developing'] ?? 15 }}</h3>
            <p>Developing Drivers</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--danger);"><i class="fas fa-arrow-down"></i> Training in progress</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] ?? '4.2/5' }}</h3>
            <p>Average Readiness Score</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +0.3</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Readiness Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="readinessDistributionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Promotion Readiness Trend</h3>
        <div class="chart-wrapper">
            <canvas id="promotionReadinessTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Eligibility Breakdown</h3>
        <div class="chart-wrapper">
            <canvas id="eligibilityBreakdownChart"></canvas>
        </div>
    </div>
</div>

<!-- Promotion Readiness Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Readiness Score</th>
                    <th>Eligibility Status</th>
                    <th>Performance Score</th>
                    <th>Competency Score</th>
                    <th>Target Position</th>
                    <th>Recommendation</th>
                    <th>Approval Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $c)
                <tr>
                    <td><strong>{{ $c['name'] }}</strong></td>
                    <td><strong>{{ $c['performance_score'] }}/5</strong></td>
                    <td>
                        <span class="status-badge {{ $c['status'] === 'Ready for Promotion' ? 'status-active' : ($c['status'] === 'Nearly Ready' ? 'status-pending' : 'status-inactive') }}">
                            {{ $c['status'] }}
                        </span>
                    </td>
                    <td>{{ $c['performance_score'] }}/5</td>
                    <td>{{ $c['competency_score'] }}</td>
                    <td><strong>{{ $c['target_position'] }}</strong></td>
                    <td>
                        <span class="status-badge {{ $c['status'] === 'Ready for Promotion' ? 'badge-success' : 'status-review' }}">
                            {{ $c['status'] === 'Ready for Promotion' ? 'Recommended' : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $c['status'] === 'Ready for Promotion' ? 'status-active' : 'status-pending' }}">
                            {{ $c['status'] === 'Ready for Promotion' ? 'Approved' : 'Under Review' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View readiness details for {{ $c['name'] }}')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit readiness')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Generate Recommendation" onclick="showToast('Generating recommendation for {{ $c['name'] }}...')"><i class="fas fa-file-alt"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:2rem; color:var(--text-muted);">No promotion readiness records found.</td>
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
    document.getElementById('filterEligibility').value = '';
    document.getElementById('filterApproval').value = '';
    document.getElementById('filterBranch').value = '';
    showToast('Filters reset.');
}

document.addEventListener('DOMContentLoaded', function() {
    const readinessCtx = document.getElementById('readinessDistributionChart');
    if (readinessCtx) {
        new Chart(readinessCtx, {
            type: 'bar',
            data: {
                labels: ['5', '4.5', '4', '3.5', '3', '<3'],
                datasets: [{
                    label: 'Drivers',
                    data: [5, 8, 12, 10, 6, 3],
                    backgroundColor: ['#10b981', '#34d399', '#F44336', '#f59e0b', '#f97316', '#ef4444'],
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

    const trendCtx = document.getElementById('promotionReadinessTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Avg Readiness Score',
                    data: [3.8, 3.9, 4.0, 4.0, 4.1, 4.1, 4.2],
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

    const eligibilityCtx = document.getElementById('eligibilityBreakdownChart');
    if (eligibilityCtx) {
        new Chart(eligibilityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Eligible', 'Under Review', 'Not Eligible'],
                datasets: [{
                    data: [12, 8, 15],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
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
