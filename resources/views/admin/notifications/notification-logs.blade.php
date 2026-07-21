@extends('admin.layouts.admin')

@section('title', 'TripWise — Notification Logs')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.notifications.index') }}">Notifications</a>
    <span>/</span>
    <span>Notification Logs</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Notification Logs</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Track all notification activities for auditing and troubleshooting. Monitor delivery success rates and errors.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportLogs('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportLogs('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-list"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total_logs'] }}</h3>
            <p>Total Logs</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['successful_deliveries'] }}</h3>
            <p>Successful Deliveries</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['failed_deliveries'] }}</h3>
            <p>Failed Deliveries</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending_notifications'] }}</h3>
            <p>Pending Notifications</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.notifications.logs') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search logs..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="action" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Actions</option>
            <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
            <option value="sent" {{ request('action') === 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="delivered" {{ request('action') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="failed" {{ request('action') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="retried" {{ request('action') === 'retried' ? 'selected' : '' }}>Retried</option>
        </select>
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.notifications.logs') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Notification Logs</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Notification</th>
                    <th>Recipient</th>
                    <th>Delivery Status</th>
                    <th>Timestamp</th>
                    <th>Error Message</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#LOG-{{ str_pad($log->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $log->notification->title ?? 'N/A' }}</strong></td>
                    <td>{{ $log->recipient ?? 'N/A' }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'success' => 'status-success',
                                'failed' => 'status-inactive',
                                'pending' => 'status-pending',
                            ];
                        @endphp
                        <span class="status-badge {{ $statusColors[$log->status] ?? 'status-pending' }}">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td>{{ $log->performed_at->format('M d, Y H:i') }}</td>
                    <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:{{ $log->error_message ? 'var(--danger)' : 'var(--text-muted)' }};" title="{{ $log->error_message ?? '' }}">{{ $log->error_message ?? '-' }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View Log" onclick="showToast('View log')"><i class="fas fa-eye"></i></button>
                        @if($log->status === 'failed')
                            <button class="icon-btn" title="Retry" onclick="retryNotification({{ $log->id }})" style="color:var(--success);"><i class="fas fa-redo"></i></button>
                        @endif
                        <button class="icon-btn" title="Export" onclick="exportLogs('excel')"><i class="fas fa-download"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No logs found.</td>
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
        <h3><i class="fas fa-chart-line"></i> Delivery Success Rate</h3>
        <div class="chart-wrapper">
            <canvas id="deliveryRateChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Notification Activity Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="activityTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Error Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="errorDistributionChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function retryNotification(id) {
    if (confirm('Retry this notification?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.logs') }}/" + id + "/retry";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function exportLogs(format) {
    showToast('Exporting logs as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const deliveryCtx = document.getElementById('deliveryRateChart');
    if (deliveryCtx) {
        new Chart(deliveryCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Success Rate %',
                    data: [95, 96, 94, 97, 95, 98],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false, min: 90, max: 100 } },
                plugins: { legend: { display: false } }
            }
        });
    }

    const activityCtx = document.getElementById('activityTimelineChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Notifications',
                    data: [45, 52, 38, 65, 48, 25, 20],
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    }

    const errorCtx = document.getElementById('errorDistributionChart');
    if (errorCtx) {
        new Chart(errorCtx, {
            type: 'doughnut',
            data: {
                labels: ['Network Error', 'Invalid Email', 'SMS Failed', 'Other'],
                datasets: [{
                    data: [40, 25, 20, 15],
                    backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#94a3b8'],
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
