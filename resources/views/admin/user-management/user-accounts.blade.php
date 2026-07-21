@extends('admin.layouts.admin')

@section('title', 'TripWise — User Accounts')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <span>User Accounts</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">User Accounts</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage all administrator and driver accounts. Create, edit, view, and archive user accounts.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('createUserModal')"><i class="fas fa-plus"></i> Create User</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total'] }}</h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-shield"></i></div>
        <div class="card-info">
            <h3>{{ $stats['admins'] }}</h3>
            <p>Admin Accounts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-car"></i></div>
        <div class="card-info">
            <h3>{{ $stats['drivers'] }}</h3>
            <p>Driver Accounts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Users</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.users.accounts') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="role" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="driver" {{ request('role') === 'driver' ? 'selected' : '' }}>Driver</option>
        </select>
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.users.accounts') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> User Accounts</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Account Status</th>
                    <th>Date Created</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#USR-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td><span class="status-badge status-{{ $user->role === 'admin' ? 'review' : 'success' }}">{{ ucfirst($user->role) }}</span></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @php
                            $statusColors = [
                                'active' => 'status-success',
                                'inactive' => 'status-pending',
                                'suspended' => 'status-inactive',
                                'archived' => 'status-review',
                            ];
                        @endphp
                        <span class="status-badge {{ $statusColors[$user->status] ?? 'status-pending' }}">{{ ucfirst($user->status) }}</span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="viewUser({{ $user->id }})"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Edit" onclick="editUser({{ $user->id }})"><i class="fas fa-edit"></i></button>
                        <button class="icon-btn" title="Archive" onclick="archiveUser({{ $user->id }})" style="color:var(--warning);"><i class="fas fa-archive"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $users->links() }}
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-user-plus"></i> Create User</h2>
            <button class="icon-btn" onclick="closeModal('createUserModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.users.accounts.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Full Name</label>
                    <input type="text" name="name" required placeholder="Enter full name" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email</label>
                    <input type="email" name="email" required placeholder="Enter email" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Password</label>
                        <input type="password" name="password" required placeholder="Min 8 characters" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Confirm password" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Role</label>
                        <select name="role" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="driver">Driver</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                        <select name="status" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Phone</label>
                    <input type="text" name="phone" placeholder="Enter phone number" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Address</label>
                    <textarea name="address" rows="2" placeholder="Enter address" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create User</button>
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
function viewUser(id) {
    showToast('View user profile #USR-' + String(id).padStart(6, '0'));
}
function editUser(id) {
    window.location.href = "{{ route('admin.users.index') }}/" + id + "/edit";
}
function archiveUser(id) {
    if (confirm('Archive this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.users.index') }}/" + id;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
