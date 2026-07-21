@extends('admin.layouts.admin')

@section('title', 'TripWise — Career Path')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Career Path</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Career Path</h1>
        <p>Track and visualize career progression of every driver.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Add Career Path modal coming soon')"><i class="fas fa-plus"></i> Add Career Path</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" id="searchDriver" placeholder="Search driver..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select id="filterLevel" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Career Levels</option>
            <option value="entry">Entry Level</option>
            <option value="intermediate">Intermediate</option>
            <option value="senior">Senior</option>
            <option value="lead">Lead</option>
            <option value="manager">Manager</option>
        </select>
        <select id="filterStatus" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Status</option>
            <option value="on-track">On Track</option>
            <option value="at-risk">At Risk</option>
            <option value="completed">Completed</option>
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
        <div class="card-icon blue"><i class="fas fa-route"></i></div>
        <div class="card-info">
            <h3>42</h3>
            <p>Active Career Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +8 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>12</h3>
            <p>Drivers Ready for Next Level</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-minus"></i> Stable</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-bullseye"></i></div>
        <div class="card-info">
            <h3>8</h3>
            <p>Career Goals Completed</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +3 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>68%</h3>
            <p>Career Progress Rate</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +5%</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Career Progress</h3>
        <div class="chart-wrapper">
            <canvas id="careerProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Promotion Pipeline</h3>
        <div class="chart-wrapper">
            <canvas id="promotionPipelineChart"></canvas>
        </div>
    </div>
</div>

<!-- Career Path Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Current Position</th>
                    <th>Recommended Position</th>
                    <th>Required Skills</th>
                    <th>Required Competencies</th>
                    <th>Required Trainings</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td>Senior Driver</td>
                    <td>Route Supervisor</td>
                    <td>Leadership, Planning</td>
                    <td>Decision Making, Communication</td>
                    <td>Supervisory Training</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: 75%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">75%</span>
                        </div>
                    </td>
                    <td><span class="status-badge status-active">On Track</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View career path')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit career path')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Update Career Plan" onclick="showToast('Update career plan')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Maria Santos</strong></td>
                    <td>Driver</td>
                    <td>Senior Driver</td>
                    <td>Customer Service, Safety</td>
                    <td>Customer Service, Safety</td>
                    <td>Advanced Driving</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: 45%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">45%</span>
                        </div>
                    </td>
                    <td><span class="status-badge status-pending">At Risk</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View career path')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit career path')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Update Career Plan" onclick="showToast('Update career plan')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Pedro Reyes</strong></td>
                    <td>Senior Driver</td>
                    <td>Fleet Supervisor</td>
                    <td>Fleet Management, Operations</td>
                    <td>Fleet Management, Operations</td>
                    <td>Fleet Management Training</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: 90%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">90%</span>
                        </div>
                    </td>
                    <td><span class="status-badge status-active">On Track</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View career path')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit career path')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Update Career Plan" onclick="showToast('Update career plan')"><i class="fas fa-sync-alt"></i></button>
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
    document.getElementById('searchDriver').value = '';
    document.getElementById('filterLevel').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterBranch').value = '';
    showToast('Filters reset.');
}

document.addEventListener('DOMContentLoaded', function() {
    const progressCtx = document.getElementById('careerProgressChart');
    if (progressCtx) {
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'Avg Progress %',
                    data: [30, 45, 60, 75],
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
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const pipelineCtx = document.getElementById('promotionPipelineChart');
    if (pipelineCtx) {
        new Chart(pipelineCtx, {
            type: 'bar',
            data: {
                labels: ['Senior Driver', 'Route Supervisor', 'Fleet Supervisor', 'Operations Manager'],
                datasets: [{
                    label: 'Drivers Ready',
                    data: [18, 8, 5, 2],
                    backgroundColor: '#F44336',
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
});
</script>
@endsection
