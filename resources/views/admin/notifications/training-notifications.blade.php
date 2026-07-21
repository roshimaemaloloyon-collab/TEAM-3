@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Notifications')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.notifications.index') }}">Notifications</a>
    <span>/</span>
    <span>Training Notifications</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training Notifications</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage notifications related to driver training activities, schedules, attendance, and certificates.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('sendNotificationModal')"><i class="fas fa-plus"></i> Send Notification</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['upcoming'] }}</h3>
            <p>Upcoming Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-sync-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['schedule_changes'] }}</h3>
            <p>Schedule Changes</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['attendance_reminders'] }}</h3>
            <p>Attendance Reminders</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-certificate"></i></div>
        <div class="card-info">
            <h3>{{ $stats['certificates'] }}</h3>
            <p>Certificates Ready</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.notifications.training') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search notifications..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="type" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:160px;">
            <option value="">All Types</option>
            <option value="upcoming" {{ request('type') === 'upcoming' ? 'selected' : '' }}>Upcoming Training</option>
            <option value="schedule_change" {{ request('type') === 'schedule_change' ? 'selected' : '' }}>Schedule Change</option>
            <option value="attendance_reminder" {{ request('type') === 'attendance_reminder' ? 'selected' : '' }}>Attendance Reminder</option>
            <option value="certificate" {{ request('type') === 'certificate' ? 'selected' : '' }}>Certificate</option>
            <option value="missed_training" {{ request('type') === 'missed_training' ? 'selected' : '' }}>Missed Training</option>
        </select>
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.notifications.training') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Notifications Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Training Notifications</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Notification ID</th>
                    <th>Driver</th>
                    <th>Notification Type</th>
                    <th>Message</th>
                    <th>Date & Time</th>
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
                                'upcoming' => 'status-review',
                                'schedule_change' => 'status-warning',
                                'attendance_reminder' => 'status-pending',
                                'certificate' => 'status-success',
                                'missed_training' => 'status-inactive',
                            ];
                        @endphp
                        <span class="status-badge {{ $typeColors[$notification->type] ?? 'status-pending' }}">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                    </td>
                    <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $notification->message }}">{{ $notification->message }}</td>
                    <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                    <td><span class="status-badge status-{{ $notification->status === 'unread' ? 'pending' : ($notification->status === 'read' ? 'success' : 'review') }}">{{ ucfirst($notification->status) }}</span></td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View notification')"><i class="fas fa-eye"></i></button>
                        @if($notification->status === 'unread')
                            <button class="icon-btn" title="Mark as Read" onclick="markAsRead({{ $notification->id }})" style="color:var(--success);"><i class="fas fa-check"></i></button>
                        @endif
                        <button class="icon-btn" title="Archive" onclick="archiveNotification({{ $notification->id }})" style="color:var(--warning);"><i class="fas fa-archive"></i></button>
                        <button class="icon-btn" title="Delete" onclick="deleteNotification({{ $notification->id }})" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No training notifications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $notifications->links() }}
    </div>
</div>

<!-- Send Notification Modal -->
<div id="sendNotificationModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-bell"></i> Send Training Notification</h2>
            <button class="icon-btn" onclick="closeModal('sendNotificationModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.notifications.training.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Title</label>
                    <input type="text" name="title" required placeholder="e.g., Upcoming Training: Defensive Driving" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Message</label>
                    <textarea name="message" rows="3" required placeholder="Enter notification message..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Notification Type</label>
                        <select name="type" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="upcoming">Upcoming Training</option>
                            <option value="schedule_change">Schedule Change</option>
                            <option value="attendance_reminder">Attendance Reminder</option>
                            <option value="certificate">Certificate Available</option>
                            <option value="missed_training">Missed Training Alert</option>
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
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Channel</label>
                        <select name="channel" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="in-app">In-App</option>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Expires At</label>
                        <input type="date" name="expires_at" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
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
        form.action = "{{ route('admin.notifications.training') }}/" + id + "/read";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function archiveNotification(id) {
    if (confirm('Archive this notification?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.training') }}/" + id + "/archive";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function deleteNotification(id) {
    if (confirm('Delete this notification permanently?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.training') }}/" + id;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
