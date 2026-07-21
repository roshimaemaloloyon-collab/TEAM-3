@extends('admin.layouts.admin')

@section('title', 'TripWise — Account Management')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <span>Account Management</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Account Management</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage user account status and credentials. Activate, deactivate, lock, unlock, and reset passwords.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Accounts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-times"></i></div>
        <div class="card-info">
            <h3>{{ $stats['inactive'] }}</h3>
            <p>Inactive Accounts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-lock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['locked'] }}</h3>
            <p>Locked Accounts</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-key"></i></div>
        <div class="card-info">
            <h3>{{ $stats['password_resets'] }}</h3>
            <p>Password Resets</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.users.accounts') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.users.accounts') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Accounts Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Account Management</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Account Status</th>
                    <th>Last Password Reset</th>
                    <th>Last Updated</th>
                    <th>Updated By</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#92400e;flex-shrink:0;">{{ substr($user->name, 0, 2) }}</div>
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <p style="font-size:0.75rem;color:var(--text-muted);margin:0;">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
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
                    <td>{{ $user->updated_at->format('M d, Y') }}</td>
                    <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                    <td>System Admin</td>
                    <td style="text-align:center;">
                        @if($user->status !== 'active')
                            <button class="icon-btn" title="Activate" onclick="activateUser({{ $user->id }})" style="color:var(--success);"><i class="fas fa-check"></i></button>
                        @endif
                        @if($user->status === 'active')
                            <button class="icon-btn" title="Deactivate" onclick="deactivateUser({{ $user->id }})" style="color:var(--warning);"><i class="fas fa-ban"></i></button>
                        @endif
                        @if($user->status !== 'suspended')
                            <button class="icon-btn" title="Lock" onclick="lockUser({{ $user->id }})" style="color:var(--danger);"><i class="fas fa-lock"></i></button>
                        @endif
                        @if($user->status === 'suspended')
                            <button class="icon-btn" title="Unlock" onclick="unlockUser({{ $user->id }})" style="color:var(--success);"><i class="fas fa-unlock"></i></button>
                        @endif
                        <button class="icon-btn" title="Reset Password" onclick="resetPassword({{ $user->id }})"><i class="fas fa-key"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:var(--text-muted);">No accounts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $users->links() }}
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-key"></i> Reset Password</h2>
            <button class="icon-btn" onclick="closeModal('resetPasswordModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="resetPasswordForm" method="POST">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">New Password</label>
                    <input type="password" name="new_password" required placeholder="Min 8 characters" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Confirm Password</label>
                    <input type="password" name="new_password_confirmation" required placeholder="Confirm password" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('resetPasswordModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Reset Password</button>
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
function activateUser(id) {
    if (confirm('Activate this account?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.users.accounts') }}/" + id + "/activate";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function deactivateUser(id) {
    if (confirm('Deactivate this account?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.users.accounts') }}/" + id + "/deactivate";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function lockUser(id) {
    if (confirm('Lock this account?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.users.accounts') }}/" + id + "/lock";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function unlockUser(id) {
    if (confirm('Unlock this account?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.users.accounts') }}/" + id + "/unlock";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
function resetPassword(id) {
    document.getElementById('resetPasswordForm').action = "{{ route('admin.users.accounts') }}/" + id + "/reset-password";
    openModal('resetPasswordModal');
}
</script>
@endpush
