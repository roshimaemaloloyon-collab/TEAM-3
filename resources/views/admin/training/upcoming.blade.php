@extends('admin.layouts.admin')

@section('title', 'TripWise — Upcoming Trainings')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Upcoming Trainings</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Upcoming Trainings</h1>
        <p>View all scheduled and upcoming training sessions.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Create Training modal coming soon')"><i class="fas fa-plus"></i> Create Training</button>
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
        <select id="filterMonth" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 120px;">
            <option value="">All Months</option>
            <option value="1">January</option><option value="2">February</option><option value="3">March</option>
            <option value="4">April</option><option value="5">May</option><option value="6">June</option>
            <option value="7">July</option><option value="8">August</option><option value="9">September</option>
            <option value="10">October</option><option value="11">November</option><option value="12">December</option>
        </select>
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button class="btn btn-primary" onclick="applyFilters()" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-search"></i> Search</button>
            <button class="btn btn-secondary" onclick="resetFilters()" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-undo"></i> Reset</button>
        </div>
    </div>
</div>

<!-- Upcoming Trainings Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Training ID</th>
                    <th>Training Title</th>
                    <th>Category</th>
                    <th>Instructor</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#TRN-2026-0001</strong></td>
                    <td><strong>Defensive Driving Workshop</strong></td>
                    <td>Defensive Driving</td>
                    <td>Juan Dela Cruz</td>
                    <td>Training Room A</td>
                    <td>July 15, 2026</td>
                    <td>9:00 AM - 12:00 PM</td>
                    <td>25</td>
                    <td><span class="status-badge status-active">Upcoming</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View training details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit training')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive training')"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#TRN-2026-0002</strong></td>
                    <td><strong>Customer Service Excellence</strong></td>
                    <td>Customer Service</td>
                    <td>Maria Santos</td>
                    <td>Conference Room B</td>
                    <td>July 22, 2026</td>
                    <td>1:00 PM - 4:00 PM</td>
                    <td>30</td>
                    <td><span class="status-badge status-active">Upcoming</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View training details')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit training')"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Archive" onclick="showToast('Archive training')"><i class="fas fa-archive"></i></button>
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
    document.getElementById('filterMonth').value = '';
    showToast('Filters reset.');
}
</script>
@endsection
