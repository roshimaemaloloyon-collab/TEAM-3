@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Attendance')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Training Attendance</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training Attendance</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Track attendance for every training session.</p>
    </div>
    <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Attendance</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $stats['registered'] }}</h3>
            <p>Registered Participants</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['present'] }}</h3>
            <p>Present</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['absent'] }}</h3>
            <p>Absent</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['attendance_rate'] }}</h3>
            <p>Attendance Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.training.attendance') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>Excused</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Attendance Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Attendance Records</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Training</th>
                    <th>Attendance Status</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Attendance %</th>
                    <th>Remarks</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $record)
                    <tr>
                        <td><strong>{{ $record->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $record->training->title ?? 'N/A' }}</td>
                        <td>
                            <span class="item-badge {{ $record->status === 'present' ? 'badge-success' : ($record->status === 'late' ? 'badge-warning' : ($record->status === 'absent' ? 'badge-danger' : 'badge-info')) }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td>{{ $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') : 'N/A' }}</td>
                        <td>{{ $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') : 'N/A' }}</td>
                        <td>
                            @php
                                $percentage = $record->status === 'present' ? 100 : ($record->status === 'late' ? 75 : 0);
                            @endphp
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div class="progress-bar" style="width:80px;height:8px;">
                                    <div class="progress-fill" style="width:{{ $percentage }}%;"></div>
                                </div>
                                <span style="font-size:0.85rem;font-weight:600;">{{ $percentage }}%</span>
                            </div>
                        </td>
                        <td>{{ $record->remarks ?? 'N/A' }}</td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <button type="button" class="btn btn-sm btn-secondary" title="View Attendance Details" style="color:#dc2626;border-color:#fca5a5;" onclick="openViewAttendanceModal({{ json_encode([
                                    'driver' => $record->driver->name ?? 'N/A',
                                    'training' => $record->training->title ?? 'N/A',
                                    'status' => ucfirst($record->status),
                                    'check_in' => $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') : 'N/A',
                                    'check_out' => $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') : 'N/A',
                                    'percentage' => $percentage . '%',
                                    'remarks' => $record->remarks ?? 'No special remarks recorded.'
                                ]) }})"><i class="fas fa-eye"></i></button>

                                <button type="button" class="btn btn-sm btn-primary" title="Update Attendance Status" style="background:#ef4444;border-color:#ef4444;" onclick="openEditAttendanceModal({{ json_encode([
                                    'id' => $record->id,
                                    'driver' => $record->driver->name ?? 'N/A',
                                    'training' => $record->training->title ?? 'N/A',
                                    'status' => $record->status,
                                    'remarks' => $record->remarks
                                ]) }})"><i class="fas fa-edit"></i></button>

                                <a href="{{ route('admin.training.attendance.export', ['id' => $record->id]) }}" target="_blank" class="btn btn-sm btn-secondary" style="color:#dc2626;border-color:#fca5a5;" title="Download Attendance Verification Slip"><i class="fas fa-download"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $attendance->links() }}
    </div>
</div>

<!-- View Attendance Modal -->
<div id="viewAttendanceModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-user-check" style="margin-right:0.5rem;"></i> Attendance Record Details</h2>
            <button type="button" onclick="closeModal('viewAttendanceModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="attModalDriver" style="font-size:0.95rem;color:var(--primary);">Juan Dela Cruz</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Attendance Status</span>
                    <span id="attModalStatusBadge" class="item-badge badge-success">Present</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Training Program</span>
                    <strong id="attModalTraining" style="font-size:1rem;color:#c2410c;">Training Title</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Check-In Time</span>
                    <span id="attModalCheckIn" style="font-size:0.85rem;font-weight:600;">12:00 AM</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Check-Out Time</span>
                    <span id="attModalCheckOut" style="font-size:0.85rem;font-weight:600;">12:00 AM</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Attendance % Rate</span>
                    <span id="attModalPercentage" style="font-size:0.9rem;font-weight:700;color:#059669;">100%</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Remarks & Notes</span>
                    <p id="attModalRemarks" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">No special remarks.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewAttendanceModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:500px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editAttendanceForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Update Attendance Record</h2>
                <button type="button" onclick="closeModal('editAttendanceModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editAttDriver" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Training Title</label>
                    <input type="text" id="editAttTraining" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Attendance Status</label>
                    <select name="status" id="editAttStatus" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="present">Present (100%)</option>
                        <option value="late">Late (75%)</option>
                        <option value="absent">Absent (0%)</option>
                        <option value="excused">Excused (0%)</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Remarks & Notes</label>
                    <textarea name="remarks" id="editAttRemarks" rows="3" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAttendanceModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Attendance Trend</h3>
        <div class="chart-wrapper">
            <canvas id="attendanceTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Attendance Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="attendanceDistChart"></canvas>
        </div>
    </div>
</div>

<script>
window.openModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'flex';
        el.style.visibility = 'visible';
        el.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'none';
        document.body.style.overflow = '';
    }
};

window.openViewAttendanceModal = function(data) {
    if (!data) return;
    const driverEl = document.getElementById('attModalDriver');
    const statusEl = document.getElementById('attModalStatusBadge');
    const trnEl = document.getElementById('attModalTraining');
    const inEl = document.getElementById('attModalCheckIn');
    const outEl = document.getElementById('attModalCheckOut');
    const pctEl = document.getElementById('attModalPercentage');
    const remEl = document.getElementById('attModalRemarks');

    if (driverEl) driverEl.innerText = data.driver || 'Juan Dela Cruz';
    if (trnEl) trnEl.innerText = data.training || 'Training Program';
    if (inEl) inEl.innerText = data.check_in || 'N/A';
    if (outEl) outEl.innerText = data.check_out || 'N/A';
    if (pctEl) pctEl.innerText = data.percentage || '0%';
    if (remEl) remEl.innerText = data.remarks || 'No special remarks.';

    if (statusEl) {
        statusEl.innerText = data.status || 'Present';
        statusEl.className = 'item-badge';
        const sLower = (data.status || '').toLowerCase();
        if (sLower === 'present') statusEl.classList.add('badge-success');
        else if (sLower === 'late') statusEl.classList.add('badge-warning');
        else if (sLower === 'absent') statusEl.classList.add('badge-danger');
        else statusEl.classList.add('badge-info');
    }

    window.openModal('viewAttendanceModal');
};

window.openEditAttendanceModal = function(data) {
    if (!data) return;
    const form = document.getElementById('editAttendanceForm');
    if (form) form.action = '/admin/training/attendance/' + data.id;

    const driverEl = document.getElementById('editAttDriver');
    const trnEl = document.getElementById('editAttTraining');
    const statEl = document.getElementById('editAttStatus');
    const remEl = document.getElementById('editAttRemarks');

    if (driverEl) driverEl.value = data.driver || '';
    if (trnEl) trnEl.value = data.training || '';
    if (statEl) statEl.value = data.status || 'present';
    if (remEl) remEl.value = data.remarks || '';

    window.openModal('editAttendanceModal');
};

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    const trendEl = document.getElementById('attendanceTrendChart');
    if (trendEl && typeof Chart !== 'undefined') {
        new Chart(trendEl, {
            type: 'line',
            data: {
                labels: {!! json_encode($attendanceTrend->pluck('month_num')->toArray()) !!},
                datasets: [{
                    label: 'Attendance %',
                    data: {!! json_encode($attendanceTrend->pluck('total')->toArray()) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
                }]
            },
            options: { ...chartDefaults, plugins: { legend: { display: false } } }
        });
    }

    const distEl = document.getElementById('attendanceDistChart');
    if (distEl && typeof Chart !== 'undefined') {
        new Chart(distEl, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($attendanceDist->pluck('status')->toArray()) !!},
                datasets: [{ data: {!! json_encode($attendanceDist->pluck('total')->toArray()) !!}, backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6'] }]
            },
            options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
        });
    }
});
</script>

@endsection
