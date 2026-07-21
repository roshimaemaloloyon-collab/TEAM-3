@extends('admin.layouts.admin')

@section('title', 'TripWise — Development Plan')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Development Plan</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Development Plan</h1>
        <p>Create and manage Individual Development Plans (IDP) for drivers.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Create Development Plan modal coming soon')"><i class="fas fa-plus"></i> Create Plan</button>
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
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="on-hold">On Hold</option>
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
        <div class="card-icon blue"><i class="fas fa-file-alt"></i></div>
        <div class="card-info">
            <h3>38</h3>
            <p>Active Development Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +6 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>24</h3>
            <p>Completed Development Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +5 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-book"></i></div>
        <div class="card-info">
            <h3>124</h3>
            <p>Assigned Learning Modules</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--info);"><i class="fas fa-minus"></i> No change</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="card-info">
            <h3>56</h3>
            <p>Assigned Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +12 this quarter</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Development Progress</h3>
        <div class="chart-wrapper">
            <canvas id="developmentProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Learning Completion</h3>
        <div class="chart-wrapper">
            <canvas id="learningCompletionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompletionChart"></canvas>
        </div>
    </div>
</div>

<!-- Development Plan Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Development Plan</th>
                    <th>Learning Modules</th>
                    <th>Assigned Trainings</th>
                    <th>Progress</th>
                    <th>Completion %</th>
                    <th>Target Date</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td>Leadership Development</td>
                    <td>5</td>
                    <td>3</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: 75%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">75%</span>
                        </div>
                    </td>
                    <td><strong>75%</strong></td>
                    <td>Dec 31, 2026</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View development plan')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit development plan')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Update Progress" onclick="showToast('Update progress')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Maria Santos</strong></td>
                    <td>Customer Service Excellence</td>
                    <td>3</td>
                    <td>2</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: 45%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">45%</span>
                        </div>
                    </td>
                    <td><strong>45%</strong></td>
                    <td>Mar 15, 2027</td>
                    <td><span class="status-badge status-pending">Pending</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View development plan')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit development plan')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Update Progress" onclick="showToast('Update progress')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Pedro Reyes</strong></td>
                    <td>Safety & Compliance</td>
                    <td>4</td>
                    <td>4</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: 90%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">90%</span>
                        </div>
                    </td>
                    <td><strong>90%</strong></td>
                    <td>Aug 20, 2026</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View development plan')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit development plan')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Update Progress" onclick="showToast('Update progress')"><i class="fas fa-sync-alt"></i></button>
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
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterBranch').value = '';
    showToast('Filters reset.');
}

document.addEventListener('DOMContentLoaded', function() {
    const progressCtx = document.getElementById('developmentProgressChart');
    if (progressCtx) {
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Completion %',
                    data: [20, 30, 45, 50, 60, 65, 68],
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

    const learningCtx = document.getElementById('learningCompletionChart');
    if (learningCtx) {
        new Chart(learningCtx, {
            type: 'bar',
            data: {
                labels: ['Leadership', 'Communication', 'Safety', 'Customer Service', 'Operations'],
                datasets: [{
                    label: 'Completion %',
                    data: [85, 72, 90, 68, 55],
                    backgroundColor: '#F44336',
                    borderRadius: 8
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

    const trainingCtx = document.getElementById('trainingCompletionChart');
    if (trainingCtx) {
        new Chart(trainingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Not Started'],
                datasets: [{
                    data: [45, 30, 25],
                    backgroundColor: ['#F44336', '#1c1c1e', '#faf9f6'],
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
