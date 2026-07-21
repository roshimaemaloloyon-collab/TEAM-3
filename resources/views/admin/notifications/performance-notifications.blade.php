@extends('admin.layouts.admin')

@section('title', 'TripWise — Performance Notifications')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.notifications.index') }}">Notifications</a>
    <span>/</span>
    <span>Performance Notifications</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Performance Notifications</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor driver performance activities, KPI alerts, evaluation reminders, and recognition eligibility.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('sendNotificationModal')"><i class="fas fa-plus"></i> Send Notification</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['reviews_due'] }}</h3>
            <p>Performance Reviews Due</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-bullhorn"></i></div>
        <div class="card-info">
            <h3>{{ $stats['kpi_alerts'] }}</h3>
            <p>KPI Alerts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-bell"></i></div>
        <div class="card-info">
            <h3>{{ $stats['evaluation_reminders'] }}</h3>
            <p>Evaluation Reminders</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['performance_warnings'] }}</h3>
            <p>Performance Warnings</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.notifications.performance') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search notifications..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="type" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:160px;">
            <option value="">All Types</option>
            <option value="review_due" {{ request('type') === 'review_due' ? 'selected' : '' }}>Review Due</option>
            <option value="kpi_alert" {{ request('type') === 'kpi_alert' ? 'selected' : '' }}>KPI Alert</option>
            <option value="evaluation_reminder" {{ request('type') === 'evaluation_reminder' ? 'selected' : '' }}>Evaluation Reminder</option>
            <option value="low_performance" {{ request('type') === 'low_performance' ? 'selected' : '' }}>Low Performance</option>
            <option value="recognition_eligibility" {{ request('type') === 'recognition_eligibility' ? 'selected' : '' }}>Recognition Eligibility</option>
            <option value="promotion_readiness" {{ request('type') === 'promotion_readiness' ? 'selected' : '' }}>Promotion Readiness</option>
        </select>
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.notifications.performance') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Notifications Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Performance Notifications</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Notification ID</th>
                    <th>Driver</th>
                    <th>Notification Type</th>
                    <th>Performance Category</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#NTF-{{ str_pad($notification->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $notification->user->name ?? 'All Drivers' }}</strong></td>
                    <td>
                        @php
                            $typeColors = [
                                'review_due' => 'status-pending',
                                'kpi_alert' => 'status-review',
                                'evaluation_reminder' => 'status-pending',
                                'low_performance' => 'status-inactive',
                                'recognition_eligibility' => 'status-success',
                                'promotion_readiness' => 'status-review',
                            ];
                        @endphp
                        <span class="status-badge {{ $typeColors[$notification->type] ?? 'status-pending' }}">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                    </td>
                    <td><span class="status-badge status-review">Performance</span></td>
                    <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                    <td><span class="status-badge status-{{ $notification->status === 'unread' ? 'pending' : ($notification->status === 'read' ? 'success' : 'review') }}">{{ ucfirst($notification->status) }}</span></td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View notification')"><i class="fas fa-eye"></i></button>
                        @if($notification->status === 'unread')
                            <button class="icon-btn" title="Mark as Read" onclick="markAsRead({{ $notification->id }})" style="color:var(--success);"><i class="fas fa-check"></i></button>
                        @endif
                        <button class="icon-btn" title="Archive" onclick="archiveNotification({{ $notification->id }})" style="color:var(--warning);"><i class="fas fa-archive"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No performance notifications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $notifications->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Notification Trends</h3>
        <div class="chart-wrapper">
            <canvas id="notificationTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> KPI Alert Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="kpiAlertChart"></canvas>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div id="sendNotificationModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-bell"></i> Send Performance Notification</h2>
            <button class="icon-btn" onclick="closeModal('sendNotificationModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.notifications.performance.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Title</label>
                    <input type="text" name="title" required placeholder="e.g., Performance Review Due" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Message</label>
                    <textarea name="message" rows="3" required placeholder="Enter notification message..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Notification Type</label>
                        <select name="type" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="review_due">Review Due</option>
                            <option value="kpi_alert">KPI Alert</option>
                            <option value="evaluation_reminder">Evaluation Reminder</option>
                            <option value="low_performance">Low Performance</option>
                            <option value="recognition_eligibility">Recognition Eligibility</option>
                            <option value="promotion_readiness">Promotion Readiness</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Priority</label>
                        <select name="priority" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="low">Low</option>
                            <option value="normal" selected>Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('sendNotificationModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Notification</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function markAsRead(id) {
    if (confirm('Mark this notification as read?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.performance') }}/" + id + "/read";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function archiveNotification(id) {
    if (confirm('Archive this notification?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.performance') }}/" + id + "/archive";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const trendCtx = document.getElementById('notificationTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Notifications',
                    data: [15, 22, 18, 25, 30, 28],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
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

    const kpiCtx = document.getElementById('kpiAlertChart');
    if (kpiCtx) {
        new Chart(kpiCtx, {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low', 'Urgent'],
                datasets: [{
                    data: [25, 40, 25, 10],
                    backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6'],
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
