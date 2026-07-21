@extends('admin.layouts.admin')

@section('title', 'TripWise — Notification History')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.notifications.index') }}">Notifications</a>
    <span>/</span>
    <span>Notification History</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Notification History</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Complete history of all notifications sent by the system, including read, archived, and deleted records.</p>
    </div>
    <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-download"></i> Export History</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bell"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total'] }}</h3>
            <p>Total Notifications</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['read'] }}</h3>
            <p>Read Notifications</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-envelope-open"></i></div>
        <div class="card-info">
            <h3>{{ $stats['unread'] }}</h3>
            <p>Unread Notifications</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-archive"></i></div>
        <div class="card-info">
            <h3>{{ $stats['archived'] }}</h3>
            <p>Archived Notifications</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.notifications.history') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search history..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.notifications.history') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- History Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Notification History</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $history->firstItem() ?? 0 }} to {{ $history->lastItem() ?? 0 }} of {{ $history->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Notification ID</th>
                    <th>Recipient</th>
                    <th>Notification Type</th>
                    <th>Date Sent</th>
                    <th>Read Status</th>
                    <th>Delivery Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $entry)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#NTF-{{ str_pad($entry->notification_id ?? 0, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $entry->recipient ?? $entry->notification->user->name ?? 'N/A' }}</strong></td>
                    <td><span class="status-badge status-review">{{ ucfirst($entry->notification->type ?? 'N/A') }}</span></td>
                    <td>{{ $entry->sent_at ? $entry->sent_at->format('M d, Y H:i') : '-' }}</td>
                    <td>
                        @php
                            $readStatus = $entry->notification->read_at ? 'Read' : 'Unread';
                            $readColor = $entry->notification->read_at ? 'status-success' : 'status-pending';
                        @endphp
                        <span class="status-badge {{ $readColor }}">{{ $readStatus }}</span>
                    </td>
                    <td>
                        @php
                            $deliveryColors = [
                                'sent' => 'status-review',
                                'delivered' => 'status-success',
                                'read' => 'status-success',
                                'failed' => 'status-inactive',
                                'archived' => 'status-pending',
                            ];
                        @endphp
                        <span class="status-badge {{ $deliveryColors[$entry->status] ?? 'status-pending' }}">{{ ucfirst($entry->status) }}</span>
                    </td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View notification')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Restore" onclick="restoreNotification({{ $entry->id }})" style="color:var(--success);"><i class="fas fa-undo"></i></button>
                        <button class="icon-btn" title="Delete" onclick="deleteNotification({{ $entry->id }})" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No history records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $history->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Notification Activity</h3>
        <div class="chart-wrapper">
            <canvas id="notificationActivityChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Read vs Unread</h3>
        <div class="chart-wrapper">
            <canvas id="readUnreadChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function restoreNotification(id) {
    if (confirm('Restore this notification?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.history') }}/" + id + "/restore";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function deleteNotification(id) {
    if (confirm('Delete this notification permanently?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.history') }}/" + id;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}
function exportReport(format) {
    showToast('Exporting history as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const activityCtx = document.getElementById('notificationActivityChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    { label: 'Sent', data: [45, 52, 48, 60, 55, 70], backgroundColor: '#F44336', borderRadius: 8 },
                    { label: 'Read', data: [35, 45, 38, 50, 48, 60], backgroundColor: '#10b981', borderRadius: 8 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: true } }
            }
        });
    }

    const readUnreadCtx = document.getElementById('readUnreadChart');
    if (readUnreadCtx) {
        new Chart(readUnreadCtx, {
            type: 'doughnut',
            data: {
                labels: ['Read', 'Unread', 'Archived'],
                datasets: [{
                    data: [{{ $stats['read'] }}, {{ $stats['unread'] }}, {{ $stats['archived'] }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#94a3b8'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
@endpush
