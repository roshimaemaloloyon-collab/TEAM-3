@extends('admin.layouts.admin')

@section('title', 'TripWise — User Roles & Permissions')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <span>User Roles & Permissions</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">User Roles & Permissions</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage system roles and access permissions. Assign roles to users and configure permission matrices.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('createRoleModal')"><i class="fas fa-plus"></i> Create Role</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-user-tag"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total'] }}</h3>
            <p>Total Roles</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Roles</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $stats['assigned'] }}</h3>
            <p>Assigned Roles</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-key"></i></div>
        <div class="card-info">
            <h3>{{ $stats['permissions'] }}</h3>
            <p>Custom Permissions</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.users.roles') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search roles..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.users.roles') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Roles Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Roles & Permissions</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }} of {{ $roles->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Number of Users</th>
                    <th>Permissions</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td><strong>{{ $role->name }}</strong></td>
                    <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $role->description }}">{{ $role->description ?? '-' }}</td>
                    <td><strong>{{ $role->users->count() }}</strong></td>
                    <td><span class="status-badge status-review">{{ $role->permissions->count() }} permissions</span></td>
                    <td><span class="status-badge status-{{ $role->is_active ? 'success' : 'inactive' }}">{{ $role->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View role')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Edit" onclick="editRole({{ $role->id }})"><i class="fas fa-edit"></i></button>
                        <button class="icon-btn" title="Assign Users" onclick="showToast('Assign users')"><i class="fas fa-user-plus"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:var(--text-muted);">No roles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $roles->links() }}
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-user-tag"></i> Create Role</h2>
            <button class="icon-btn" onclick="closeModal('createRoleModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.users.roles.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Role Name</label>
                    <input type="text" name="name" required placeholder="e.g., Fleet Manager" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Slug</label>
                    <input type="text" name="slug" required placeholder="e.g., fleet-manager" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Description</label>
                    <textarea name="description" rows="2" placeholder="Role description..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Permissions</label>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0.5rem;max-height:200px;overflow-y:auto;padding:0.5rem;border:1px solid var(--border);border-radius:0.5rem;">
                        @foreach($permissions as $permission)
                        <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;cursor:pointer;">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" style="width:16px;height:16px;accent-color:var(--primary);">
                            {{ $permission->name }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createRoleModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Role</button>
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
function editRole(id) {
    showToast('Edit role #' + id);
}
</script>
@endpush
