@extends('admin.layouts.admin')

@section('title', 'TripWise — Completed Trainings')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Completed Trainings</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Completed Trainings</h1>
        <p>View all completed training sessions and their results.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" id="searchTraining" placeholder="Search training..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select id="filterCategory" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Categories</option>
            <option value="Defensive Driving">Defensive Driving</option>
            <option value="Customer Service">Customer Service</option>
            <option value="Road Safety">Road Safety</option>
            <option value="Company Policies">Company Policies</option>
            <option value="Vehicle Maintenance">Vehicle Maintenance</option>
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

<!-- Completed Trainings Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Training ID</th>
                    <th>Training Title</th>
                    <th>Category</th>
                    <th>Instructor</th>
                    <th>Date Completed</th>
                    <th>Attendance</th>
                    <th>Evaluation Score</th>
                    <th>Certificate Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#TRN-2026-0003</strong></td>
                    <td><strong>Safety Protocols Training</strong></td>
                    <td>Safety</td>
                    <td>Pedro Reyes</td>
                    <td>June 10, 2026</td>
                    <td><span class="status-badge status-active">Present</span></td>
                    <td>4.5/5</td>
                    <td><span class="status-badge status-active">Issued</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View training details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="View Certificate" onclick="showToast('View certificate')"><i class="fas fa-certificate"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#TRN-2026-0004</strong></td>
                    <td><strong>Defensive Driving Workshop</strong></td>
                    <td>Defensive Driving</td>
                    <td>Juan Dela Cruz</td>
                    <td>May 15, 2026</td>
                    <td><span class="status-badge status-active">Present</span></td>
                    <td>4.8/5</td>
                    <td><span class="status-badge status-active">Issued</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View training details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="View Certificate" onclick="showToast('View certificate')"><i class="fas fa-certificate"></i></button>
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
    document.getElementById('searchTraining').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterYear').value = '';
    showToast('Filters reset.');
}
</script>
@endsection
