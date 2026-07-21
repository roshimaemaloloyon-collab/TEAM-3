@extends('admin.layouts.admin')

@section('title', 'TripWise — Backup & Recovery')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span>/</span>
    <span>Backup & Recovery</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Backup & Recovery</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage database backups, schedules, and recovery operations.</p>
    </div>
    <button type="submit" form="backupForm" class="btn btn-primary"><i class="fas fa-plus"></i> Create Backup</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-history"></i></div>
        <div class="card-info">
            <h3>{{ $stats['last_backup'] }}</h3>
            <p>Last Backup</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['next_backup'] }}</h3>
            <p>Next Scheduled Backup</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-hdd"></i></div>
        <div class="card-info">
            <h3>{{ $stats['backup_storage'] }}</h3>
            <p>Backup Storage</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['backup_status'] }}</h3>
            <p>Backup Status</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.settings.backup.index') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search backups..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Type</label>
            <select name="type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Types</option>
                <option value="manual" {{ request('type') === 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="scheduled" {{ request('type') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Backups Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-database"></i> Backup History</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Backup ID</th>
                    <th>Backup Type</th>
                    <th>Created Date</th>
                    <th>File Size</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                    <tr>
                        <td>#{{ str_pad($backup->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td style="text-transform:capitalize;">{{ $backup->backup_type }}</td>
                        <td>{{ $backup->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $backup->file_size ? number_format($backup->file_size / 1024 / 1024, 2) . ' MB' : 'N/A' }}</td>
                        <td>
                            <span class="item-badge {{ $backup->status === 'completed' ? 'badge-success' : ($backup->status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst($backup->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <a href="{{ route('admin.settings.backup.download', $backup) }}" class="btn btn-sm btn-secondary" title="Download"><i class="fas fa-download"></i></a>
                                <button class="btn btn-sm btn-primary" title="Restore"><i class="fas fa-undo"></i></button>
                                <form action="{{ route('admin.settings.backup.destroy', $backup) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this backup?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No backups found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $backups->links() }}
    </div>
</div>

<form id="backupForm" method="POST" action="{{ route('admin.settings.backup.store') }}" style="display:none;">
    @csrf
</form>

@endsection
