@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Management')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <span>Training Management</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Training Management</h1>
        <p>Manage training schedules, monitor attendance, evaluate training effectiveness, issue certificates, and track the complete training history of every TripWise driver.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Create Training modal coming soon')"><i class="fas fa-plus"></i> Create Training</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Advanced Filter Toolbar -->
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

<!-- Enhanced KPI Summary Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-graduation-cap"></i></div>
        <div class="card-info">
            <h3>42</h3>
            <p>Total Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +12% vs last month</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-calendar-check"></i></div>
        <div class="card-info">
            <h3>8</h3>
            <p>Upcoming Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fas fa-clock"></i> Next 7 days</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-spinner"></i></div>
        <div class="card-info">
            <h3>3</h3>
            <p>Ongoing Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--info);"><i class="fas fa-circle"></i> In progress</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>31</h3>
            <p>Completed Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +5 this month</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon teal"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>156</h3>
            <p>Registered Drivers</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +23 new</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon gold"><i class="fas fa-percentage"></i></div>
        <div class="card-info">
            <h3>94%</h3>
            <p>Attendance Rate</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +2.1%</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-certificate"></i></div>
        <div class="card-info">
            <h3>142</h3>
            <p>Certificates Issued</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +18 this month</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>4.5/5</h3>
            <p>Average Evaluation Score</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +0.3</span>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="nav-tabs" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button class="nav-tab active" onclick="switchTab(this, 'overview')"><i class="fas fa-th-large" style="margin-right: 0.4rem;"></i> Overview</button>
        <button class="nav-tab" onclick="switchTab(this, 'schedule')"><i class="fas fa-calendar-alt" style="margin-right: 0.4rem;"></i> Training Schedule</button>
        <button class="nav-tab" onclick="switchTab(this, 'calendar')"><i class="fas fa-calendar" style="margin-right: 0.4rem;"></i> Calendar</button>
        <button class="nav-tab" onclick="switchTab(this, 'registration')"><i class="fas fa-user-plus" style="margin-right: 0.4rem;"></i> Registration</button>
        <button class="nav-tab" onclick="switchTab(this, 'attendance')"><i class="fas fa-clipboard-check" style="margin-right: 0.4rem;"></i> Attendance</button>
        <div style="position: relative;">
            <button class="nav-tab" onclick="toggleMore(this)">More <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 0.25rem;"></i></button>
            <div class="more-dropdown" id="moreDropdown" style="display: none; position: absolute; top: 100%; left: 0; margin-top: 0.5rem; background: var(--white); border: 1px solid var(--border); border-radius: 0.75rem; box-shadow: 0 8px 24px rgba(0,0,0,0.1); min-width: 180px; z-index: 200; overflow: hidden;">
                <button class="nav-tab" onclick="switchTab(this, 'evaluation')" style="width: 100%; border-radius: 0; justify-content: flex-start; padding: 0.75rem 1rem;"><i class="fas fa-star" style="margin-right: 0.5rem;"></i> Evaluation</button>
                <button class="nav-tab" onclick="switchTab(this, 'history')" style="width: 100%; border-radius: 0; justify-content: flex-start; padding: 0.75rem 1rem;"><i class="fas fa-history" style="margin-right: 0.5rem;"></i> Training History</button>
                <button class="nav-tab" onclick="switchTab(this, 'certificates')" style="width: 100%; border-radius: 0; justify-content: flex-start; padding: 0.75rem 1rem;"><i class="fas fa-certificate" style="margin-right: 0.5rem;"></i> Certificates</button>
                <button class="nav-tab" onclick="switchTab(this, 'reports')" style="width: 100%; border-radius: 0; justify-content: flex-start; padding: 0.75rem 1rem;"><i class="fas fa-file-alt" style="margin-right: 0.5rem;"></i> Reports</button>
            </div>
        </div>
    </div>
</div>

<!-- Tab Contents -->
<div id="tab-overview" class="tab-content active">
    <!-- Charts Grid -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Monthly Training Trend</h3>
            <div class="chart-wrapper">
                <canvas id="trainingTrendChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Training Categories</h3>
            <div class="chart-wrapper">
                <canvas id="trainingCategoriesChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Attendance Rate</h3>
            <div class="chart-wrapper">
                <canvas id="attendanceRateChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-tasks"></i> Training Completion</h3>
            <div class="chart-wrapper">
                <canvas id="trainingCompletionChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-certificate"></i> Certificates Issued</h3>
            <div class="chart-wrapper">
                <canvas id="certificatesChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-building"></i> Branch Training Comparison</h3>
            <div class="chart-wrapper">
                <canvas id="branchComparisonChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div id="tab-schedule" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-calendar-alt"></i> Training Schedule</h3>
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn btn-primary" onclick="showToast('Create training')"><i class="fas fa-plus"></i> Create Training</button>
                <button class="btn btn-secondary" onclick="showToast('Exporting...')"><i class="fas fa-download"></i> Export</button>
            </div>
        </div>
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
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete training')" style="color: var(--danger);"><i class="fas fa-trash"></i></button>
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
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete training')" style="color: var(--danger);"><i class="fas fa-trash"></i></button>
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
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete training')" style="color: var(--danger);"><i class="fas fa-trash"></i></button>
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
</div>

<div id="tab-calendar" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-calendar"></i> Interactive Training Calendar</h3>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-secondary" onclick="showToast('Monthly view')"><i class="fas fa-th"></i> Monthly</button>
                <button class="btn btn-secondary" onclick="showToast('Weekly view')"><i class="fas fa-list"></i> Weekly</button>
                <button class="btn btn-secondary" onclick="showToast('Daily view')"><i class="fas fa-calendar-day"></i> Daily</button>
                <button class="btn btn-primary" onclick="showToast('Add training')"><i class="fas fa-plus"></i> Add Training</button>
            </div>
        </div>
        <div style="background: var(--white); border-radius: 1rem; padding: 2rem; text-align: center; border: 2px dashed var(--border);">
            <i class="fas fa-calendar-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem; opacity: 0.5;"></i>
            <h4 style="color: var(--primary); margin-bottom: 0.5rem;">Interactive Calendar</h4>
            <p style="color: var(--text-muted); max-width: 500px; margin: 0 auto;">Full calendar integration with monthly, weekly, and daily views. Color-coded events, drag-and-drop rescheduling, and quick-add training sessions will be implemented here.</p>
        </div>
    </div>
</div>

<div id="tab-registration" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-user-plus"></i> Driver Registration</h3>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Single registration')"><i class="fas fa-user-plus"></i> Register Driver</button>
                <button class="btn btn-secondary" onclick="showToast('Bulk registration')"><i class="fas fa-users"></i> Bulk Register</button>
                <button class="btn btn-secondary" onclick="showToast('Waiting list')"><i class="fas fa-list"></i> Waiting List</button>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Driver Name</th>
                        <th>Training Assigned</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Juan Dela Cruz</strong></td>
                        <td>Defensive Driving Workshop</td>
                        <td>July 10, 2026</td>
                        <td><span class="status-badge status-active">Approved</span></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View registration')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Remove" onclick="showToast('Remove driver')" style="color: var(--danger);"><i class="fas fa-user-minus"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Maria Santos</strong></td>
                        <td>Customer Service Excellence</td>
                        <td>July 12, 2026</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View registration')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Approve" onclick="showToast('Approve registration')" style="color: var(--success);"><i class="fas fa-check"></i></button>
                                <button class="icon-btn" title="Reject" onclick="showToast('Reject registration')" style="color: var(--danger);"><i class="fas fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Pedro Reyes</strong></td>
                        <td>Safety Protocols Training</td>
                        <td>July 8, 2026</td>
                        <td><span class="status-badge status-review">Waitlisted</span></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View registration')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Remove" onclick="showToast('Remove driver')" style="color: var(--danger);"><i class="fas fa-user-minus"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-attendance" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-clipboard-check"></i> Attendance Monitoring</h3>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('QR Code Check-in')"><i class="fas fa-qrcode"></i> QR Check-in</button>
                <button class="btn btn-secondary" onclick="showToast('Manual attendance')"><i class="fas fa-edit"></i> Manual Attendance</button>
                <button class="btn btn-secondary" onclick="showToast('Export attendance')"><i class="fas fa-download"></i> Export</button>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: var(--beige); padding: 1rem; border-radius: 0.75rem; text-align: center;">
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--success); margin: 0;">238</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Present</p>
            </div>
            <div style="background: var(--beige); padding: 1rem; border-radius: 0.75rem; text-align: center;">
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--warning); margin: 0;">12</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Late</p>
            </div>
            <div style="background: var(--beige); padding: 1rem; border-radius: 0.75rem; text-align: center;">
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--danger); margin: 0;">5</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Absent</p>
            </div>
            <div style="background: var(--beige); padding: 1rem; border-radius: 0.75rem; text-align: center;">
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--info); margin: 0;">3</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Excused</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Driver Name</th>
                        <th>Training</th>
                        <th>Status</th>
                        <th>Check-in Time</th>
                        <th>Check-out Time</th>
                        <th>Remarks</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Juan Dela Cruz</strong></td>
                        <td>Defensive Driving Workshop</td>
                        <td><span class="status-badge status-active">Present</span></td>
                        <td>8:55 AM</td>
                        <td>12:05 PM</td>
                        <td>On time</td>
                        <td style="text-align: center;">
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit attendance')"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Maria Santos</strong></td>
                        <td>Customer Service Excellence</td>
                        <td><span class="status-badge status-pending">Late</span></td>
                        <td>1:15 PM</td>
                        <td>4:00 PM</td>
                        <td>15 minutes late</td>
                        <td style="text-align: center;">
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit attendance')"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Pedro Reyes</strong></td>
                        <td>Safety Protocols Training</td>
                        <td><span class="status-badge status-inactive">Absent</span></td>
                        <td>—</td>
                        <td>—</td>
                        <td>No show</td>
                        <td style="text-align: center;">
                            <button class="icon-btn" title="Edit" onclick="showToast('Edit attendance')"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-evaluation" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-star"></i> Training Evaluation</h3>
            <button class="btn btn-primary" onclick="showToast('Create evaluation')"><i class="fas fa-plus"></i> New Evaluation</button>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Driver Name</th>
                        <th>Training</th>
                        <th>Overall Rating</th>
                        <th>Knowledge</th>
                        <th>Instructor Feedback</th>
                        <th>Effectiveness</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Juan Dela Cruz</strong></td>
                        <td>Defensive Driving Workshop</td>
                        <td><strong>4.5/5</strong></td>
                        <td>4/5</td>
                        <td>4.5/5</td>
                        <td>5/5</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View evaluation')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit evaluation')"><i class="fas fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Maria Santos</strong></td>
                        <td>Customer Service Excellence</td>
                        <td><strong>4.8/5</strong></td>
                        <td>4.5/5</td>
                        <td>5/5</td>
                        <td>4.8/5</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View evaluation')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit evaluation')"><i class="fas fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-history" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-history"></i> Training History</h3>
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn btn-secondary" onclick="showToast('Export history')"><i class="fas fa-download"></i> Export</button>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Training Name</th>
                        <th>Date Completed</th>
                        <th>Attendance</th>
                        <th>Evaluation Score</th>
                        <th>Certificate Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Safety Protocols Training</strong></td>
                        <td>June 10, 2026</td>
                        <td>Present</td>
                        <td>4.5/5</td>
                        <td><span class="status-badge status-active">Issued</span></td>
                        <td style="text-align: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View history')"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Defensive Driving Workshop</strong></td>
                        <td>May 15, 2026</td>
                        <td>Present</td>
                        <td>4.8/5</td>
                        <td><span class="status-badge status-active">Issued</span></td>
                        <td style="text-align: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View history')"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-certificates" class="tab-content">
    <div class="table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
            <h3 style="margin: 0;"><i class="fas fa-certificate"></i> Certificates</h3>
            <button class="btn btn-primary" onclick="showToast('Issue new certificate')"><i class="fas fa-certificate"></i> Issue Certificate</button>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Certificate Number</th>
                        <th>Driver Name</th>
                        <th>Training Name</th>
                        <th>Date Issued</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#CERT-2026-0001</strong></td>
                        <td>Juan Dela Cruz</td>
                        <td>Defensive Driving Workshop</td>
                        <td>July 15, 2026</td>
                        <td><span class="status-badge status-active">Issued</span></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View certificate')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Download" onclick="showToast('Downloading PDF...')"><i class="fas fa-download"></i></button>
                                <button class="icon-btn" title="Print" onclick="showToast('Printing certificate...')"><i class="fas fa-print"></i></button>
                                <button class="icon-btn" title="Reissue" onclick="showToast('Reissue certificate')"><i class="fas fa-redo"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>#CERT-2026-0002</strong></td>
                        <td>Maria Santos</td>
                        <td>Customer Service Excellence</td>
                        <td>July 18, 2026</td>
                        <td><span class="status-badge status-active">Issued</span></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <button class="icon-btn" title="View" onclick="showToast('View certificate')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Download" onclick="showToast('Downloading PDF...')"><i class="fas fa-download"></i></button>
                                <button class="icon-btn" title="Print" onclick="showToast('Printing certificate...')"><i class="fas fa-print"></i></button>
                                <button class="icon-btn" title="Reissue" onclick="showToast('Reissue certificate')"><i class="fas fa-redo"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-reports" class="tab-content">
    <div class="section-grid">
        <div class="section-card">
            <h3><i class="fas fa-file-alt"></i> Training Schedule Report</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Generate comprehensive training schedule reports with date ranges and filters.</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Generating PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-secondary" onclick="showToast('Generating Excel...')"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-secondary" onclick="showToast('Generating CSV...')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
        <div class="section-card">
            <h3><i class="fas fa-clipboard-check"></i> Attendance Report</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Generate attendance reports for all training sessions with present, late, absent, and excused breakdowns.</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Generating PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-secondary" onclick="showToast('Generating Excel...')"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-secondary" onclick="showToast('Generating CSV...')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
        <div class="section-card">
            <h3><i class="fas fa-chart-bar"></i> Evaluation Report</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Generate training evaluation and feedback reports with instructor ratings and recommendations.</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Generating PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-secondary" onclick="showToast('Generating Excel...')"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-secondary" onclick="showToast('Generating CSV...')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
        <div class="section-card">
            <h3><i class="fas fa-certificate"></i> Certificate Report</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Generate certificate issuance reports with driver details and validity status.</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Generating PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-secondary" onclick="showToast('Generating Excel...')"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-secondary" onclick="showToast('Generating CSV...')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
        <div class="section-card">
            <h3><i class="fas fa-check-double"></i> Completion Report</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Generate training completion rate reports with pass/fail statistics and trends.</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Generating PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-secondary" onclick="showToast('Generating Excel...')"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-secondary" onclick="showToast('Generating CSV...')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
        <div class="section-card">
            <h3><i class="fas fa-building"></i> Branch Training Report</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Generate branch-wise training comparison reports with attendance and completion metrics.</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="showToast('Generating PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-secondary" onclick="showToast('Generating Excel...')"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-secondary" onclick="showToast('Generating CSV...')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" style="position: fixed; bottom: 2rem; right: 2rem; background: var(--charcoal); color: #fff; padding: 0.85rem 1.5rem; border-radius: 0.75rem; box-shadow: 0 8px 24px rgba(0,0,0,0.2); font-size: 0.9rem; font-weight: 500; display: none; align-items: center; gap: 0.75rem; z-index: 9999; animation: slideUp 0.3s ease;">
    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1rem;"></i>
    <span id="toastMessage">Action completed.</span>
</div>
@endsection

@section('scripts')
<script>
function switchTab(btn, tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));
    const target = document.getElementById('tab-' + tabId);
    if (target) target.classList.add('active');
    if (btn) btn.classList.add('active');
    document.getElementById('moreDropdown').style.display = 'none';
}

function toggleMore(btn) {
    const dropdown = document.getElementById('moreDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function applyFilters() {
    showToast('Filters applied successfully.');
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

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('moreDropdown');
    if (dropdown && !e.target.closest('.more-dropdown') && !e.target.closest('.nav-tab')) {
        dropdown.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const defaultTab = @json($tab ?? 'overview');
    const tabBtn = document.querySelector(`.nav-tab[onclick*="'${defaultTab}'"]`);
    if (tabBtn) {
        switchTab(tabBtn, defaultTab);
    } else {
        const firstTab = document.querySelector('.nav-tab');
        if (firstTab) firstTab.classList.add('active');
        const firstContent = document.querySelector('.tab-content');
        if (firstContent) firstContent.classList.add('active');
    }

    const ctx = document.getElementById('trainingTrendChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Training Sessions',
                    data: [5, 7, 6, 8, 9, 7, 8],
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

    const catCtx = document.getElementById('trainingCategoriesChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: ['Defensive Driving', 'Customer Service', 'Safety', 'Company Policies', 'Vehicle Maintenance'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
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

    const attCtx = document.getElementById('attendanceRateChart');
    if (attCtx) {
        new Chart(attCtx, {
            type: 'bar',
            data: {
                labels: ['Defensive Driving', 'Customer Service', 'Safety', 'Company Policies', 'Vehicle Maintenance'],
                datasets: [{
                    label: 'Attendance %',
                    data: [95, 88, 92, 85, 90],
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

    const compCtx = document.getElementById('trainingCompletionChart');
    if (compCtx) {
        new Chart(compCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Ongoing', 'Upcoming'],
                datasets: [{
                    data: [31, 3, 8],
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

    const certCtx = document.getElementById('certificatesChart');
    if (certCtx) {
        new Chart(certCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Certificates Issued',
                    data: [12, 15, 18, 20, 22, 25, 28],
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

    const branchCtx = document.getElementById('branchComparisonChart');
    if (branchCtx) {
        new Chart(branchCtx, {
            type: 'bar',
            data: {
                labels: ['North', 'South', 'East', 'West', 'Central'],
                datasets: [{
                    label: 'Completed Trainings',
                    data: [12, 10, 8, 7, 9],
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

<style>
.nav-tab {
    padding: 0.6rem 1.25rem;
    border-radius: 2rem;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--text-muted);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    font-family: 'Inter', sans-serif;
}

.nav-tab:hover {
    background: var(--beige);
    color: var(--primary);
}

.nav-tab.active {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.35);
}

.more-dropdown {
    display: none;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
