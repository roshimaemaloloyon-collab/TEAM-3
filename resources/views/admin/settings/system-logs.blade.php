@extends('admin.layouts.admin')

@section('title', 'TripWise — System Logs')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span>/</span>
    <span>System Logs</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">System Logs</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor system events, errors, configuration changes, and administrative activities.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary"><i class="fas fa-file-export"></i> Export PDF</button>
        <button class="btn btn-secondary"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-list"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['total_logs']) }}</h3>
            <p>Total Logs</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-exclamation-circle"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['errors_today']) }}</h3>
            <p>Errors Today</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-cogs"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['system_events']) }}</h3>
            <p>System Events</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-edit"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['config_updates']) }}</h3>
            <p>Configuration Updates</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.settings.logs.index') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Event Type</label>
            <select name="event_type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Events</option>
                <option value="error" {{ request('event_type') === 'error' ? 'selected' : '' }}>Error</option>
                <option value="system_activity" {{ request('event_type') === 'system_activity' ? 'selected' : '' }}>System Activity</option>
                <option value="configuration_change" {{ request('event_type') === 'configuration_change' ? 'selected' : '' }}>Configuration Change</option>
                <option value="audit" {{ request('event_type') === 'audit' ? 'selected' : '' }}>Audit</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Severity</label>
            <select name="severity" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Severities</option>
                <option value="info" {{ request('severity') === 'info' ? 'selected' : '' }}>Info</option>
                <option value="warning" {{ request('severity') === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="error" {{ request('severity') === 'error' ? 'selected' : '' }}>Error</option>
                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- System Logs Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-list-alt"></i> System Events</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Event Type</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                    <th>Performed By</th>
                    <th>Severity</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>#{{ str_pad($log->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $log->event_type) }}</td>
                        <td style="text-transform:capitalize;">{{ $log->module ?? 'System' }}</td>
                        <td>{{ Str::limit($log->description, 50) }}</td>
                        <td>{{ $log->performed_at ? $log->performed_at->format('M d, Y h:i A') : ($log->created_at->format('M d, Y h:i A')) }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="item-badge {{ $log->severity === 'info' ? 'badge-success' : ($log->severity === 'warning' ? 'badge-warning' : ($log->severity === 'error' ? 'badge-danger' : 'badge-critical')) }}">
                                {{ ucfirst($log->severity) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View Details"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Export"><i class="fas fa-download"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $logs->links() }}
    </div>
</div>

@endsection
