@extends('admin.layouts.admin')

@section('title', 'TripWise — Security Settings')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span>/</span>
    <span>Security Settings</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Security Settings</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage administrator account security, password policies, and session management.</p>
    </div>
    <button type="submit" form="securityForm" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-desktop"></i></div>
        <div class="card-info">
            <h3>1</h3>
            <p>Active Sessions</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-shield-alt"></i></div>
        <div class="card-info">
            <h3>{{ $settings->two_factor_enabled ? 'High' : 'Medium' }}</h3>
            <p>Security Level</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-key"></i></div>
        <div class="card-info">
            <h3>Strong</h3>
            <p>Password Status</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-mobile-alt"></i></div>
        <div class="card-info">
            <h3>{{ $settings->two_factor_enabled ? 'Enabled' : 'Disabled' }}</h3>
            <p>Two-Factor Authentication</p>
        </div>
    </div>
</div>

<div class="section-grid">
    <!-- Security Settings Form -->
    <div class="section-card">
        <h3><i class="fas fa-cog"></i> Security Configuration</h3>
        <form id="securityForm" method="POST" action="{{ route('admin.settings.security.update') }}">
            @csrf
            @method('PUT')
            <div style="display:grid;gap:1rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:var(--beige);border-radius:0.5rem;">
                    <input type="checkbox" name="two_factor_enabled" id="two_factor" value="1" {{ $settings->two_factor_enabled ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="two_factor" style="font-size:0.9rem;cursor:pointer;"><strong>Enable Two-Factor Authentication</strong></label>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Session Timeout (minutes)</label>
                    <input type="number" name="session_timeout" value="{{ $settings->session_timeout }}" min="5" max="120" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Max Login Attempts</label>
                    <input type="number" name="max_login_attempts" value="{{ $settings->max_login_attempts }}" min="3" max="10" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Lockout Duration (minutes)</label>
                    <input type="number" name="lockout_duration" value="{{ $settings->lockout_duration }}" min="5" max="60" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:var(--beige);border-radius:0.5rem;">
                    <input type="checkbox" name="force_logout_all" id="force_logout_all" value="1" {{ $settings->force_logout_all ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="force_logout_all" style="font-size:0.9rem;cursor:pointer;"><strong>Force Logout All Devices</strong></label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fas fa-save"></i> Save Security Settings</button>
            </div>
        </form>
    </div>

    <!-- Active Sessions -->
    <div class="section-card">
        <h3><i class="fas fa-desktop"></i> Active Sessions</h3>
        <div style="display:grid;gap:1rem;">
            <div class="list-item">
                <div class="item-content">
                    <div class="item-title">Current Session</div>
                    <div class="item-subtitle">Windows • Chrome • 192.168.1.1</div>
                </div>
                <span class="item-badge badge-success">Active</span>
            </div>
            <form method="POST" action="{{ route('admin.settings.security.force-logout') }}" onsubmit="return confirm('Force logout all devices? This will end all active sessions.');">
                @csrf
                <button type="submit" class="btn btn-danger" style="width:100%;"><i class="fas fa-sign-out-alt"></i> Force Logout All Devices</button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="section-card">
        <h3><i class="fas fa-key"></i> Change Password</h3>
        <form method="POST" action="{{ route('admin.settings.security.password') }}">
            @csrf
            @method('POST')
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Current Password</label>
                    <input type="password" name="current_password" required placeholder="Enter current password" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">New Password</label>
                    <input type="password" name="new_password" required placeholder="Min 8 characters" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required placeholder="Confirm new password" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fas fa-key"></i> Change Password</button>
            </div>
        </form>
    </div>
</div>

@endsection
