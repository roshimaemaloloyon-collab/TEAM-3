@extends('admin.layouts.admin')

@section('title', 'TripWise — Security Monitoring')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <span>Security Monitoring</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Security Monitoring</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor account security and suspicious activities. Track failed logins, suspicious attempts, and account lock events.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['failed_logins'] }}</h3>
            <p>Failed Logins</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-user-secret"></i></div>
        <div class="card-info">
            <h3>{{ $stats['suspicious_activities'] }}</h3>
            <p>Suspicious Activities</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-lock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['locked_accounts'] }}</h3>
            <p>Locked Accounts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bell"></i></div>
        <div class="card-info">
            <h3>{{ $stats['security_alerts'] }}</h3>
            <p>Security Alerts</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.users.security') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="event_type" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Event Types</option>
            <option value="failed" {{ request('event_type') === 'failed' ? 'selected' : '' }}>Failed Login</option>
            <option value="blocked" {{ request('event_type') === 'blocked' ? 'selected' : '' }}>Blocked</option>
            <option value="success" {{ request('event_type') === 'success' ? 'selected' : '' }}>Success</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.users.security') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Security Events Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Security Events</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event Type</th>
                    <th>Date & Time</th>
                    <th>IP Address</th>
                    <th>Device</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><strong>{{ $log->user->name ?? 'Unknown' }}</strong></td>
                    <td>
                        @php
                            $eventColors = [
                                'failed' => 'status-inactive',
                                'blocked' => 'status-pending',
                                'success' => 'status-success',
                            ];
                        @endphp
                        <span class="status-badge {{ $eventColors[$log->status] ?? 'status-pending' }}">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td>{{ $log->login_at ? $log->login_at->format('M d, Y H:i') : '-' }}</td>
                    <td><span style="font-family:monospace;font-size:0.85rem;">{{ $log->ip_address ?? '-' }}</span></td>
                    <td>{{ $log->device ?? '-' }}</td>
                    <td>
                        @php
                            $severityColors = [
                                'failed' => 'status-inactive',
                                'blocked' => 'status-pending',
                                'success' => 'status-success',
                            ];
                        @endphp
                        <span class="status-badge {{ $severityColors[$log->status] ?? 'status-pending' }}">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td><span class="status-badge status-{{ $log->failure_reason ? 'inactive' : 'success' }}">{{ $log->failure_reason ? 'Resolved' : 'Open' }}</span></td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View event')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Investigate" onclick="showToast('Investigate event')"><i class="fas fa-search"></i></button>
                        <button class="icon-btn" title="Resolve" onclick="showToast('Resolve event')" style="color:var(--success);"><i class="fas fa-check"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No security events found.</td>
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
        <h3><i class="fas fa-chart-line"></i> Failed Login Trend</h3>
        <div class="chart-wrapper">
            <canvas id="failedLoginTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Security Events</h3>
        <div class="chart-wrapper">
            <canvas id="securityEventsChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Alert Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="alertDistChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const failedCtx = document.getElementById('failedLoginTrendChart');
    if (failedCtx) {
        new Chart(failedCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Failed Logins',
                    data: [5, 8, 3, 6, 4, 7],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }

    const eventsCtx = document.getElementById('securityEventsChart');
    if (eventsCtx) {
        new Chart(eventsCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    { label: 'Failed', data: [3, 5, 2, 4, 3, 1, 2], backgroundColor: '#ef4444', borderRadius: 8 },
                    { label: 'Blocked', data: [1, 2, 1, 3, 1, 0, 1], backgroundColor: '#f59e0b', borderRadius: 8 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: true } } }
        });
    }

    const alertCtx = document.getElementById('alertDistChart');
    if (alertCtx) {
        new Chart(alertCtx, {
            type: 'doughnut',
            data: {
                labels: ['Failed Login', 'Blocked', 'Suspicious', 'Other'],
                datasets: [{ data: [45, 25, 20, 10], backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#94a3b8'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }
});
</script>
@endpush
