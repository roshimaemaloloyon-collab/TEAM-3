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
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Update Attendance"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-secondary" title="Export"><i class="fas fa-download"></i></button>
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

@endsection

@section('scripts')
<script>
function exportReport(format) { showToast('Exporting ' + format.toUpperCase() + ' report...'); }
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('attendanceTrendChart'), {
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

    new Chart(document.getElementById('attendanceDistChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($attendanceDist->pluck('status')->toArray()) !!},
            datasets: [{ data: {!! json_encode($attendanceDist->pluck('total')->toArray()) !!}, backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });
});
</script>
@endsection
