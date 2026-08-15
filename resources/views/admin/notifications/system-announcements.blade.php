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
                        <button type="button" class="icon-btn" title="View Details" style="color:#ef4444;" onclick="openViewAnnouncementModal({{ json_encode([
                            'id' => '#ANN-' . str_pad($announcement->id, 6, '0', STR_PAD_LEFT),
                            'title' => $announcement->title,
                            'category' => ucfirst(str_replace('_', ' ', $announcement->type)),
                            'content' => $announcement->message ?? $announcement->title,
                            'publish_date' => $announcement->created_at->format('M d, Y'),
                            'expiry_date' => $announcement->expires_at ? $announcement->expires_at->format('M d, Y') : 'No Expiry',
                            'status' => ucfirst($announcement->status)
                        ]) }})"><i class="fas fa-eye"></i></button>

                        <button type="button" class="icon-btn" title="Edit Announcement" style="color:#3b82f6;" onclick="openEditAnnouncementModal({{ json_encode([
                            'id' => $announcement->id,
                            'title' => $announcement->title,
                            'message' => $announcement->message ?? $announcement->title,
                            'type' => $announcement->type
                        ]) }})"><i class="fas fa-edit"></i></button>

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

<!-- View Announcement Modal -->
<div id="viewAnnouncementModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-bullhorn" style="margin-right:0.5rem;"></i> System Announcement Details</h2>
            <button type="button" onclick="closeModal('viewAnnouncementModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Announcement ID</span>
                    <strong id="vAnnId" style="font-size:0.95rem;color:var(--primary);">#ANN-000050</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Status</span>
                    <span id="vAnnStatus" class="status-badge status-active">Published</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Title</span>
                    <strong id="vAnnTitle" style="font-size:1rem;color:#c2410c;">Announcement Title</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Category</span>
                    <span id="vAnnCategory" class="status-badge status-pending">Training</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Expiration Date</span>
                    <span id="vAnnExpiry" style="font-size:0.85rem;font-weight:600;">No Expiry</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Announcement Content</span>
                    <p id="vAnnContent" style="font-size:0.9rem;margin:0.25rem 0 0;color:var(--text-dark);line-height:1.5;background:#ffffff;padding:0.75rem;border-radius:0.4rem;border:1px solid #cbd5e1;">Content text...</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewAnnouncementModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div id="editAnnouncementModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editAnnouncementForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit System Announcement</h2>
                <button type="button" onclick="closeModal('editAnnouncementModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Title</label>
                    <input type="text" name="title" id="editAnnTitle" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Message</label>
                    <textarea name="message" id="editAnnMessage" rows="3" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAnnouncementModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
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
window.openModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'flex';
        el.style.visibility = 'visible';
        el.style.opacity = '1';
    }
};

window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'none';
    }
};

window.openViewAnnouncementModal = function(data) {
    if (!data) return;
    const idEl = document.getElementById('vAnnId');
    const titleEl = document.getElementById('vAnnTitle');
    const catEl = document.getElementById('vAnnCategory');
    const contentEl = document.getElementById('vAnnContent');
    const expEl = document.getElementById('vAnnExpiry');
    const statEl = document.getElementById('vAnnStatus');

    if (idEl) idEl.innerText = data.id || 'N/A';
    if (titleEl) titleEl.innerText = data.title || 'Announcement';
    if (catEl) catEl.innerText = data.category || 'System';
    if (contentEl) contentEl.innerText = data.content || '';
    if (expEl) expEl.innerText = data.expiry_date || 'No Expiry';
    if (statEl) statEl.innerText = data.status || 'Published';

    window.openModal('viewAnnouncementModal');
};

window.openEditAnnouncementModal = function(data) {
    if (!data) return;
    const form = document.getElementById('editAnnouncementForm');
    if (form) form.action = "{{ route('admin.notifications.announcements') }}/" + data.id;

    const titleEl = document.getElementById('editAnnTitle');
    const msgEl = document.getElementById('editAnnMessage');

    if (titleEl) titleEl.value = data.title || '';
    if (msgEl) msgEl.value = data.message || '';

    window.openModal('editAnnouncementModal');
};

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
