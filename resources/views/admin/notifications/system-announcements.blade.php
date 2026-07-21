@extends('admin.layouts.admin')

@section('title', 'TripWise — System Announcements')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.notifications.index') }}">Notifications</a>
    <span>/</span>
    <span>System Announcements</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">System Announcements</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage organization-wide announcements, system updates, maintenance notices, and policy updates.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('createAnnouncementModal')"><i class="fas fa-plus"></i> Create Announcement</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bullhorn"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Announcements</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['scheduled'] }}</h3>
            <p>Scheduled Announcements</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-tools"></i></div>
        <div class="card-info">
            <h3>{{ $stats['maintenance'] }}</h3>
            <p>Maintenance Notices</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-sync-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['system_updates'] }}</h3>
            <p>System Updates</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.notifications.announcements') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search announcements..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="category" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:160px;">
            <option value="">All Categories</option>
            <option value="agency" {{ request('category') === 'agency' ? 'selected' : '' }}>Agency</option>
            <option value="system_update" {{ request('category') === 'system_update' ? 'selected' : '' }}>System Update</option>
            <option value="maintenance" {{ request('category') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            <option value="policy" {{ request('category') === 'policy' ? 'selected' : '' }}>Policy</option>
            <option value="emergency" {{ request('category') === 'emergency' ? 'selected' : '' }}>Emergency</option>
            <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General</option>
        </select>
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.notifications.announcements') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Announcements Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> System Announcements</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $announcements->firstItem() ?? 0 }} to {{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Announcement ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Publish Date</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#ANN-{{ str_pad($announcement->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $announcement->title }}</strong></td>
                    <td><span class="status-badge status-review">{{ ucfirst(str_replace('_', ' ', $announcement->type)) }}</span></td>
                    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                    <td>{{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y') : 'No Expiry' }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'unread' => 'status-pending',
                                'published' => 'status-success',
                                'archived' => 'status-review',
                            ];
                        @endphp
                        <span class="status-badge {{ $statusColors[$announcement->status] ?? 'status-pending' }}">{{ ucfirst($announcement->status) }}</span>
                    </td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View announcement')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Edit" onclick="showToast('Edit announcement')"><i class="fas fa-edit"></i></button>
                        @if($announcement->status !== 'published')
                            <button class="icon-btn" title="Publish" onclick="publishAnnouncement({{ $announcement->id }})" style="color:var(--success);"><i class="fas fa-check"></i></button>
                        @endif
                        <button class="icon-btn" title="Archive" onclick="archiveAnnouncement({{ $announcement->id }})" style="color:var(--warning);"><i class="fas fa-archive"></i></button>
                        <button class="icon-btn" title="Delete" onclick="deleteAnnouncement({{ $announcement->id }})" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No announcements found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $announcements->links() }}
    </div>
</div>

<!-- Create Announcement Modal -->
<div id="createAnnouncementModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-bullhorn"></i> Create Announcement</h2>
            <button class="icon-btn" onclick="closeModal('createAnnouncementModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.notifications.announcements.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Title</label>
                    <input type="text" name="title" required placeholder="e.g., System Maintenance Notice" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Message</label>
                    <textarea name="message" rows="4" required placeholder="Enter announcement message..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Category</label>
                        <select name="type" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="agency">Agency Announcement</option>
                            <option value="system_update">System Update</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="policy">Policy Update</option>
                            <option value="emergency">Emergency</option>
                            <option value="general">General</option>
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
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Expiration Date</label>
                    <input type="date" name="expires_at" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createAnnouncementModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Create Announcement</button>
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
function publishAnnouncement(id) {
    if (confirm('Publish this announcement?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.announcements') }}/" + id + "/publish";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function archiveAnnouncement(id) {
    if (confirm('Archive this announcement?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.announcements') }}/" + id + "/archive";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function deleteAnnouncement(id) {
    if (confirm('Delete this announcement permanently?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.notifications.announcements') }}/" + id;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
