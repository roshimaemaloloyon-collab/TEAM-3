@extends('admin.layouts.admin')

@section('title', 'TripWise — Audit Logs')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <span>Audit Logs</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Audit Logs</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Complete records of administrative actions performed within the system. User changes, role changes, permission changes, and system activity logs.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-clipboard-list"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total'] }}</h3>
            <p>Total Audit Records</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-edit"></i></div>
        <div class="card-info">
            <h3>{{ $stats['user_changes'] }}</h3>
            <p>User Changes</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-key"></i></div>
        <div class="card-info">
            <h3>{{ $stats['permission_changes'] }}</h3>
            <p>Permission Changes</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-cog"></i></div>
        <div class="card-info">
            <h3>{{ $stats['admin_actions'] }}</h3>
            <p>Administrative Actions</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.users.audit-logs') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search audit logs..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="module" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Modules</option>
            <option value="users" {{ request('module') === 'users' ? 'selected' : '' }}>Users</option>
            <option value="roles" {{ request('module') === 'roles' ? 'selected' : '' }}>Roles</option>
            <option value="training" {{ request('module') === 'training' ? 'selected' : '' }}>Training</option>
            <option value="evaluation" {{ request('module') === 'evaluation' ? 'selected' : '' }}>Evaluation</option>
        </select>
        <select name="action" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Actions</option>
            <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
            <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
            <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.users.audit-logs') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Audit Logs Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Audit Logs</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Audit ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Date & Time</th>
                    <th>Performed By</th>
                    <th>Details</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#AUD-{{ str_pad($log->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $log->user->name ?? 'System' }}</strong></td>
                    <td>
                        @php
                            $actionColors = [
                                'created' => 'status-success',
                                'updated' => 'status-warning',
                                'deleted' => 'status-inactive',
                            ];
                        @endphp
                        <span class="status-badge {{ $actionColors[$log->action] ?? 'status-pending' }}">{{ ucfirst($log->action) }}</span>
                    </td>
                    <td><span class="status-badge status-review">{{ ucfirst($log->module) }}</span></td>
                    <td>{{ $log->performed_at->format('M d, Y H:i') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $log->description }}">{{ $log->description ?? '-' }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View Details" onclick="showToast('View audit details')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Export" onclick="exportReport('excel')"><i class="fas fa-download"></i></button>
                        <button class="icon-btn" title="Archive" onclick="showToast('Archive log')" style="color:var(--warning);"><i class="fas fa-archive"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No audit logs found.</td>
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
        <h3><i class="fas fa-chart-area"></i> Audit Activity Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="auditTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Administrative Actions by Module</h3>
        <div class="chart-wrapper">
            <canvas id="adminActionsChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function exportReport(format) {
    showToast('Exporting audit logs as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const timelineCtx = document.getElementById('auditTimelineChart');
    if (timelineCtx) {
        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    { label: 'User Changes', data: [12, 19, 15, 25, 22, 30], borderColor: '#F44336', backgroundColor: 'rgba(244, 67, 54, 0.1)', fill: true, tension: 0.4 },
                    { label: 'Role Changes', data: [5, 8, 6, 10, 7, 12], borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.1)', fill: true, tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: true } } }
        });
    }

    const actionsCtx = document.getElementById('adminActionsChart');
    if (actionsCtx) {
        new Chart(actionsCtx, {
            type: 'bar',
            data: {
                labels: ['Users', 'Roles', 'Training', 'Evaluation', 'Reports', 'Notifications'],
                datasets: [{
                    label: 'Actions',
                    data: [45, 32, 28, 22, 18, 15],
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    }
});
</script>
@endpush
