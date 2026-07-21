@extends('admin.layouts.admin')

@section('title', 'TripWise — System Preferences')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span>/</span>
    <span>System Preferences</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">System Preferences</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Configure general system behavior and preferences.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('restoreForm').submit();"><i class="fas fa-undo"></i> Restore Defaults</button>
        <button type="submit" form="preferencesForm" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $preferences->maintenance_mode ? 'Maintenance' : 'Online' }}</h3>
            <p>System Status</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-code-branch"></i></div>
        <div class="card-info">
            <h3>{{ $preferences->system_version ?? '1.0.0' }}</h3>
            <p>Current Version</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-wrench"></i></div>
        <div class="card-info">
            <h3>{{ $preferences->maintenance_mode ? 'Active' : 'Inactive' }}</h3>
            <p>Maintenance Status</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-cog"></i></div>
        <div class="card-info">
            <h3>Active</h3>
            <p>Active Configuration</p>
        </div>
    </div>
</div>

<!-- System Preferences Form -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-cog"></i> System Configuration</h3>
    <form id="preferencesForm" method="POST" action="{{ route('admin.settings.preferences.update') }}">
        @csrf
        @method('PUT')
        <div style="display:grid;gap:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Default Dashboard</label>
                    <select name="default_dashboard" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="admin.dashboard" {{ $preferences->default_dashboard === 'admin.dashboard' ? 'selected' : '' }}>Admin Dashboard</option>
                        <option value="driver.dashboard" {{ $preferences->default_dashboard === 'driver.dashboard' ? 'selected' : '' }}>Driver Dashboard</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">System Version</label>
                    <input type="text" name="system_version" value="{{ $preferences->system_version }}" placeholder="e.g., 1.0.0" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Date Format</label>
                    <select name="date_format" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="M d, Y" {{ $preferences->date_format === 'M d, Y' ? 'selected' : '' }}>Jan 01, 2026</option>
                        <option value="d/m/Y" {{ $preferences->date_format === 'd/m/Y' ? 'selected' : '' }}>01/01/2026</option>
                        <option value="Y-m-d" {{ $preferences->date_format === 'Y-m-d' ? 'selected' : '' }}>2026-01-01</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Time Format</label>
                    <select name="time_format" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="H:i" {{ $preferences->time_format === 'H:i' ? 'selected' : '' }}>24-hour (14:30)</option>
                        <option value="g:i A" {{ $preferences->time_format === 'g:i A' ? 'selected' : '' }}>12-hour (2:30 PM)</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Time Zone</label>
                <select name="timezone" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    <option value="Asia/Manila" {{ $preferences->timezone === 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (GMT+8)</option>
                    <option value="UTC" {{ $preferences->timezone === 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="America/New_York" {{ $preferences->timezone === 'America/New_York' ? 'selected' : '' }}>America/New_York (GMT-5)</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;padding:1rem;background:var(--beige);border-radius:0.75rem;">
                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ $preferences->maintenance_mode ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                <label for="maintenance_mode" style="font-size:0.9rem;cursor:pointer;">
                    <strong>Maintenance Mode</strong>
                    <p style="font-size:0.8rem;color:var(--text-muted);margin:0;">When enabled, only administrators can access the system.</p>
                </label>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </div>
        </div>
    </form>
    <form id="restoreForm" method="POST" action="{{ route('admin.settings.preferences.restore') }}" style="display:none;">
        @csrf
        @method('POST')
    </form>
</div>

@endsection
