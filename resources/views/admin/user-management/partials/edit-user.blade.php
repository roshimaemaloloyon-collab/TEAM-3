@extends('admin.layouts.admin')

@section('title', 'TripWise — Edit User')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">User Management</a>
    <span>/</span>
    <a href="{{ route('admin.users.accounts') }}">User Accounts</a>
    <span>/</span>
    <span>Edit User #{{ $user->id }}</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Edit User</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Update user account information for {{ $user->name }}.</p>
    </div>
    <a href="{{ route('admin.users.accounts') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="table-card">
    <form method="POST" action="{{ route('admin.users.accounts.update', $user) }}">
        @csrf
        @method('PUT')
        <div style="display:grid;gap:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Full Name</label>
                    <input type="text" name="name" required value="{{ $user->name }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email</label>
                    <input type="email" name="email" required value="{{ $user->email }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Role</label>
                    <select name="role" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="driver" {{ $user->role === 'driver' ? 'selected' : '' }}>Driver</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="archived" {{ $user->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Phone</label>
                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="Enter phone number" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Address</label>
                <textarea name="address" rows="2" placeholder="Enter address" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;">{{ $user->address }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">New Password (optional)</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.users.accounts') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update User</button>
            </div>
        </div>
    </form>
</div>

@endsection
