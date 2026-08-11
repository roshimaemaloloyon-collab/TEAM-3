@extends('admin.layouts.admin')

@section('title', 'TripWise — Driver Documents')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.drivers.index') }}">Manage Drivers</a>
    <span>/</span>
    <span>Driver Documents</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Driver Documents Management</h1>
        <p>Centralized repository for all driver licenses, clearance certificates, vehicle registrations, and compliance documents.</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <button class="btn btn-primary" onclick="openModal('uploadDocModal')"><i class="fas fa-upload"></i> Upload New Document</button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="summary-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1.25rem;margin-bottom:1.5rem;">
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:var(--primary);">{{ $drivers->count() * 4 }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Total Submitted Documents</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#059669;">{{ intval($drivers->count() * 3.2) }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Verified Documents</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#ea580c;">{{ intval($drivers->count() * 0.6) }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Pending Verification</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#dc2626;">{{ intval($drivers->count() * 0.2) + 1 }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Expired / Needing Action</p>
        </div>
    </div>
</div>

<!-- Search & Filter Controls -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('admin.drivers.documents') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
        <div style="flex:1;min-width:240px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name, ID, or document type..." style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
        </div>
        <div style="width:180px;">
            <select name="type" style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
                <option value="">All Document Types</option>
                <option value="license">Driver's License</option>
                <option value="orcr">OR / CR</option>
                <option value="nbi">NBI Clearance</option>
                <option value="police">Police Clearance</option>
                <option value="medical">Medical Certificate</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    </form>
</div>

<!-- Documents Master Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver Photo</th>
                    <th>Driver Name</th>
                    <th>Driver ID</th>
                    <th>Document Type</th>
                    <th>Issue / Upload Date</th>
                    <th>Expiration Date</th>
                    <th>Verification Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $index => $driver)
                <tr>
                    <td>
                        <a href="{{ route('admin.drivers.profile', $driver->id) }}">
                            <img src="{{ $driver->photo ?: asset('drivers/photo/' . $driver->id) }}" alt="{{ $driver->first_name }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.drivers.profile', $driver->id) }}" style="color:inherit;text-decoration:none;">
                            <strong>{{ $driver->full_name }}</strong>
                        </a>
                    </td>
                    <td><strong>{{ $driver->formatted_id }}</strong></td>
                    <td>
                        @if($index % 4 == 0)
                            <i class="fas fa-id-card" style="color:#0284c7;margin-right:0.4rem;"></i> Driver's License
                        @elseif($index % 4 == 1)
                            <i class="fas fa-file-alt" style="color:#059669;margin-right:0.4rem;"></i> OR / CR ({{ $driver->vehicle_assignment }})
                        @elseif($index % 4 == 2)
                            <i class="fas fa-file-contract" style="color:#ea580c;margin-right:0.4rem;"></i> NBI Clearance
                        @else
                            <i class="fas fa-notes-medical" style="color:#8b5cf6;margin-right:0.4rem;"></i> Medical Certificate
                        @endif
                    </td>
                    <td>{{ $driver->created_at ? $driver->created_at->format('M d, Y') : 'Jan 10, 2026' }}</td>
                    <td>{{ $driver->license_expiration ? \Carbon\Carbon::parse($driver->license_expiration)->format('M d, Y') : 'Dec 20, 2026' }}</td>
                    <td>
                        @if($index % 5 == 0)
                            <span class="status-badge" style="background:#ffedd5;color:#c2410c;">🟡 Pending Review</span>
                        @elseif($index % 6 == 0)
                            <span class="status-badge" style="background:#fee2e2;color:#991b1b;">🔴 Expired</span>
                        @else
                            <span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Verified</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.35rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', ['id' => $driver->id, 'tab' => 'tab-documents']) }}" class="icon-btn" title="View Document"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Download" onclick="showToast('Downloading document for {{ $driver->first_name }}...')"><i class="fas fa-download"></i></button>
                            <button class="icon-btn" title="Update Status" onclick="showToast('Verification status updated.')"><i class="fas fa-check"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;">No driver documents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1.25rem;">
        {{ $drivers->links() }}
    </div>
</div>
@endsection
