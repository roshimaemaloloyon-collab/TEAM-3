@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Programs')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Training Programs</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Training Programs</h1>
        <p>View and manage all training programs offered to drivers.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Create Training Program modal coming soon')"><i class="fas fa-plus"></i> Create Program</button>
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
        <select id="filterBranch" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 140px;">
            <option value="">All Branches</option>
            <option value="north">North Branch</option>
            <option value="south">South Branch</option>
            <option value="east">East Branch</option>
            <option value="west">West Branch</option>
        </select>
        <select id="filterInstructor" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 140px;">
            <option value="">All Instructors</option>
            <option value="Juan Dela Cruz">Juan Dela Cruz</option>
            <option value="Maria Santos">Maria Santos</option>
            <option value="Pedro Reyes">Pedro Reyes</option>
        </select>
        <select id="filterMonth" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 120px;">
            <option value="">All Months</option>
            <option value="1">January</option><option value="2">February</option><option value="3">March</option>
            <option value="4">April</option><option value="5">May</option><option value="6">June</option>
            <option value="7">July</option><option value="8">August</option><option value="9">September</option>
            <option value="10">October</option><option value="11">November</option><option value="12">December</option>
        </select>
        <select id="filterYear" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 100px;">
            <option value="">All Years</option>
            <option value="2026">2026</option>
            <option value="2025">2025</option>
            <option value="2024">2024</option>
        </select>
        <select id="filterStatus" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 130px;">
            <option value="">All Status</option>
            <option value="upcoming">Upcoming</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button class="btn btn-primary" onclick="applyFilters()" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-search"></i> Search</button>
            <button class="btn btn-secondary" onclick="resetFilters()" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-undo"></i> Reset</button>
        </div>
    </div>
</div>

<!-- Training Programs Table -->
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
                <tr>
                    <td><strong>#TRN-2026-0003</strong></td>
                    <td><strong>Safety Protocols Training</strong></td>
                    <td>Safety</td>
                    <td>Pedro Reyes</td>
                    <td>Auditorium</td>
                    <td>June 10, 2026</td>
                    <td>8:00 AM - 5:00 PM</td>
                    <td>50</td>
                    <td><span class="status-badge status-pending">Completed</span></td>
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
    document.getElementById('filterBranch').value = '';
    document.getElementById('filterInstructor').value = '';
    document.getElementById('filterMonth').value = '';
    document.getElementById('filterYear').value = '';
    document.getElementById('filterStatus').value = '';
    showToast('Filters reset.');
}
</script>
@endsection
