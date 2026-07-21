@extends('admin.layouts.admin')

@section('title', 'TripWise — Login & Activity Logs')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <span>Login & Activity Logs</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Login & Activity Logs</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor user logins, logout history, activity logs, and failed login attempts.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-sign-in-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['today_logins'] }}</h3>
            <p>Today's Logins</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-exclamation-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['failed_logins'] }}</h3>
            <p>Failed Logins</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-desktop"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active_sessions'] }}</h3>
            <p>Active Sessions</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['user_activities'] }}</h3>
            <p>User Activities</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.users.login-logs') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Actions</option>
            <option value="logged_in" {{ request('status') === 'logged_in' ? 'selected' : '' }}>Login</option>
            <option value="logged_out" {{ request('status') === 'logged_out' ? 'selected' : '' }}>Logout</option>
            <option value="created" {{ request('status') === 'created' ? 'selected' : '' }}>Created</option>
            <option value="updated" {{ request('status') === 'updated' ? 'selected' : '' }}>Updated</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.users.login-logs') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Activity Logs Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Activity Logs</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>IP Address</th>
                    <th>Device</th>
                    <th>Browser</th>
                    <th>Activity</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><strong>{{ $log->user->name ?? 'System' }}</strong></td>
                    <td>{{ $log->performed_at->format('M d, Y H:i') }}</td>
                    <td>{{ $log->performed_at->addHours(1)->format('M d, Y H:i') }}</td>
                    <td><span style="font-family:monospace;font-size:0.85rem;">{{ $log->ip_address ?? '-' }}</span></td>
                    <td>{{ $log->user_agent ? 'Desktop' : '-' }}</td>
                    <td>Chrome / Windows</td>
                    <td>
                        @php
                            $actionColors = [
                                'logged_in' => 'status-success',
                                'logged_out' => 'status-pending',
                                'created' => 'status-review',
                                'updated' => 'status-warning',
                                'deleted' => 'status-inactive',
                            ];
                        @endphp
                        <span class="status-badge {{ $actionColors[$log->action] ?? 'status-pending' }}">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View Details" onclick="showToast('View log details')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Export" onclick="exportReport('excel')"><i class="fas fa-download"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No activity logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $logs->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Daily Login Activity</h3>
        <div class="chart-wrapper">
            <canvas id="dailyLoginChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Login Trends</h3>
        <div class="chart-wrapper">
            <canvas id="loginTrendsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> User Activity Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="activityDistChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function exportReport(format) {
    showToast('Exporting logs as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const dailyCtx = document.getElementById('dailyLoginChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Logins',
                    data: [45, 52, 38, 65, 48, 25, 20],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    const trendsCtx = document.getElementById('loginTrendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    { label: 'Successful', data: [120, 135, 128, 145, 138, 150], backgroundColor: '#10b981', borderRadius: 8 },
                    { label: 'Failed', data: [5, 8, 3, 6, 4, 7], backgroundColor: '#ef4444', borderRadius: 8 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: true } } }
        });
    }

    const distCtx = document.getElementById('activityDistChart');
    if (distCtx) {
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: ['Login', 'Logout', 'Update', 'Create', 'Delete'],
                datasets: [{ data: [35, 25, 20, 15, 5], backgroundColor: ['#F44336', '#EF5350', '#D32F2F', '#FFCDD2', '#B71C1C'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }
});
</script>
@endpush
