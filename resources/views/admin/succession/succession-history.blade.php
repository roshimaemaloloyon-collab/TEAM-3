@extends('admin.layouts.admin')

@section('title', 'TripWise — Succession History')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Succession History</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Succession History</h1>
        <p>View historical records of succession planning activities, assessments, and promotions.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" id="searchHistory" placeholder="Search history..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select id="filterActivityType" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Activities</option>
            <option value="assessment">Leadership Assessment</option>
            <option value="development">Development Plan</option>
            <option value="promotion">Promotion</option>
            <option value="recommendation">Recommendation</option>
        </select>
        <select id="filterYear" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 100px;">
            <option value="">All Years</option>
            <option value="2026">2026</option>
            <option value="2025">2025</option>
            <option value="2024">2024</option>
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
            <h3>45</h3>
            <p>Promotion Records</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +12 this year</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>128</h3>
            <p>Assessment Records</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--info);"><i class="fas fa-minus"></i> No change</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-file-alt"></i></div>
        <div class="card-info">
            <h3>67</h3>
            <p>Development Records</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +18 this year</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-archive"></i></div>
        <div class="card-info">
            <h3>23</h3>
            <p>Archived Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);"><i class="fas fa-minus"></i> Stable</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Promotion Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="promotionTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Historical Trends</h3>
        <div class="chart-wrapper">
            <canvas id="historicalTrendsChart"></canvas>
        </div>
    </div>
</div>

<!-- Succession History Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Activity Type</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Updated By</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td>Promotion</td>
                    <td>Promoted from Driver to Senior Driver</td>
                    <td>July 10, 2026</td>
                    <td>HR Manager</td>
                    <td><span class="status-badge status-active">Completed</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View history details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Restore" onclick="showToast('Restore record')"><i class="fas fa-undo"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive record')"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Maria Santos</strong></td>
                    <td>Assessment</td>
                    <td>Leadership Assessment - Q2 2026</td>
                    <td>July 12, 2026</td>
                    <td>HR Manager</td>
                    <td><span class="status-badge status-active">Completed</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View history details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Restore" onclick="showToast('Restore record')"><i class="fas fa-undo"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive record')"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Pedro Reyes</strong></td>
                    <td>Development Plan</td>
                    <td>Safety & Compliance Development Plan</td>
                    <td>July 8, 2026</td>
                    <td>HR Manager</td>
                    <td><span class="status-badge status-pending">In Progress</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View history details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Restore" onclick="showToast('Restore record')"><i class="fas fa-undo"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive record')"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Ana Lim</strong></td>
                    <td>Recommendation</td>
                    <td>Recommended for Route Supervisor position</td>
                    <td>June 25, 2026</td>
                    <td>HR Manager</td>
                    <td><span class="status-badge status-review">Pending</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View history details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Restore" onclick="showToast('Restore record')"><i class="fas fa-undo"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive record')"><i class="fas fa-archive"></i></button>
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
    document.getElementById('searchHistory').value = '';
    document.getElementById('filterActivityType').value = '';
    document.getElementById('filterYear').value = '';
    showToast('Filters reset.');
}

document.addEventListener('DOMContentLoaded', function() {
    const timelineCtx = document.getElementById('promotionTimelineChart');
    if (timelineCtx) {
        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Promotions',
                    data: [1, 2, 1, 3, 2, 3, 4],
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
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const trendsCtx = document.getElementById('historicalTrendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'bar',
            data: {
                labels: ['2024', '2025', '2026'],
                datasets: [
                    {
                        label: 'Assessments',
                        data: [85, 102, 128],
                        backgroundColor: '#F44336',
                        borderRadius: 8
                    },
                    {
                        label: 'Promotions',
                        data: [12, 18, 22],
                        backgroundColor: '#1c1c1e',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } } },
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
