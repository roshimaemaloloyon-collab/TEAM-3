@extends('admin.layouts.admin')

@section('title', 'TripWise — Talent Pool')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Talent Pool</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Talent Pool</h1>
        <p>Identify and manage high-potential drivers for leadership and promotion opportunities.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Add to Talent Pool modal coming soon')"><i class="fas fa-plus"></i> Add Driver</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" id="searchTalent" placeholder="Search driver..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select id="filterTalentCategory" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Categories</option>
            <option value="high-potential">High Potential</option>
            <option value="promotion-candidate">Promotion Candidate</option>
            <option value="future-leader">Future Leader</option>
            <option value="ready-now">Ready Now</option>
        </select>
        <select id="filterReadiness" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 140px;">
            <option value="">All Readiness</option>
            <option value="ready">Ready Now</option>
            <option value="1-2">1-2 Years</option>
            <option value="2-3">2-3 Years</option>
            <option value="not-ready">Not Ready</option>
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
        <div class="card-icon blue"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>24</h3>
            <p>High Potential Drivers</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +6 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
        <div class="card-info">
            <h3>18</h3>
            <p>Promotion Candidates</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +4 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-crown"></i></div>
        <div class="card-info">
            <h3>12</h3>
            <p>Future Leaders</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fas fa-minus"></i> No change</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>4.3/5</h3>
            <p>Average Readiness Score</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +0.2</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Talent Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="talentDistributionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Readiness Level</h3>
        <div class="chart-wrapper">
            <canvas id="readinessLevelChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Talent Ranking</h3>
        <div class="chart-wrapper">
            <canvas id="talentRankingChart"></canvas>
        </div>
    </div>
</div>

<!-- Talent Pool Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Leadership Score</th>
                    <th>Readiness Score</th>
                    <th>Talent Category</th>
                    <th>Recommended Position</th>
                    <th>Priority Ranking</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td><strong>4.8/5</strong></td>
                    <td><strong>4.8/5</strong></td>
                    <td><span class="status-badge badge-success">High Potential</span></td>
                    <td>Route Supervisor</td>
                    <td><span class="rank-badge rank-1">1</span></td>
                    <td><span class="status-badge status-active">Ready Now</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View talent profile')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Update" onclick="showToast('Update talent info')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Promote Recommendation" onclick="showToast('Promote recommendation generated')"><i class="fas fa-file-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Maria Santos</strong></td>
                    <td><strong>4.5/5</strong></td>
                    <td><strong>4.2/5</strong></td>
                    <td><span class="status-badge badge-info">Promotion Candidate</span></td>
                    <td>Senior Driver</td>
                    <td><span class="rank-badge rank-2">2</span></td>
                    <td><span class="status-badge status-pending">1-2 Years</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View talent profile')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Update" onclick="showToast('Update talent info')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Promote Recommendation" onclick="showToast('Promote recommendation generated')"><i class="fas fa-file-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Pedro Reyes</strong></td>
                    <td><strong>3.9/5</strong></td>
                    <td><strong>3.5/5</strong></td>
                    <td><span class="status-badge badge-warning">Future Leader</span></td>
                    <td>Fleet Supervisor</td>
                    <td><span class="rank-badge rank-3">3</span></td>
                    <td><span class="status-badge status-review">2-3 Years</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View talent profile')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Update" onclick="showToast('Update talent info')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Promote Recommendation" onclick="showToast('Promote recommendation generated')"><i class="fas fa-file-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Ana Lim</strong></td>
                    <td><strong>4.6/5</strong></td>
                    <td><strong>4.5/5</strong></td>
                    <td><span class="status-badge badge-success">High Potential</span></td>
                    <td>Operations Manager</td>
                    <td><span class="rank-badge rank-other">4</span></td>
                    <td><span class="status-badge status-active">Ready Now</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View talent profile')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Update" onclick="showToast('Update talent info')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Promote Recommendation" onclick="showToast('Promote recommendation generated')"><i class="fas fa-file-alt"></i></button>
                        </div>
                    </td>
                </tr>
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
    document.getElementById('searchTalent').value = '';
    document.getElementById('filterTalentCategory').value = '';
    document.getElementById('filterReadiness').value = '';
    document.getElementById('filterBranch').value = '';
    showToast('Filters reset.');
}

document.addEventListener('DOMContentLoaded', function() {
    const distributionCtx = document.getElementById('talentDistributionChart');
    if (distributionCtx) {
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['High Potential', 'Promotion Candidate', 'Future Leader', 'Ready Now'],
                datasets: [{
                    data: [24, 18, 12, 15],
                    backgroundColor: ['#F44336', '#1c1c1e', '#2c2c2e', '#faf9f6'],
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

    const readinessCtx = document.getElementById('readinessLevelChart');
    if (readinessCtx) {
        new Chart(readinessCtx, {
            type: 'bar',
            data: {
                labels: ['Ready Now', '1-2 Years', '2-3 Years', 'Not Ready'],
                datasets: [{
                    label: 'Drivers',
                    data: [15, 22, 18, 8],
                    backgroundColor: ['#10b981', '#FCF5EB', '#f5e6c8', '#ef4444'],
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

    const rankingCtx = document.getElementById('talentRankingChart');
    if (rankingCtx) {
        new Chart(rankingCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Avg Leadership Score',
                    data: [4.0, 4.1, 4.2, 4.2, 4.3, 4.3, 4.3],
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
});
</script>
@endsection
