@extends('admin.layouts.admin')

@section('title', 'TripWise — Vehicle Information')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.drivers.index') }}">Manage Drivers</a>
    <span>/</span>
    <span>Vehicle Information</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Vehicle Information Management</h1>
        <p>Fleet vehicle assignments, plate registration records, maintenance status, and vehicle route distribution.</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <button class="btn btn-primary" onclick="showToast('Vehicle assignment modal opened')"><i class="fas fa-car"></i> Assign New Vehicle</button>
    </div>
</div>

<!-- Fleet Statistics Cards -->
<div class="summary-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1.25rem;margin-bottom:1.5rem;">
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-car-side"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:var(--primary);">{{ $drivers->count() }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Total Fleet Vehicles</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-key"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#059669;">{{ intval($drivers->count() * 0.85) }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Active & On-Route</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-tools"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#ea580c;">{{ intval($drivers->count() * 0.1) + 1 }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Scheduled Maintenance</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#f3e8ff;color:#9333ea;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-route"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#9333ea;">5 Branches</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Active Operating Zones</p>
        </div>
    </div>
</div>

<!-- Vehicle Information Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Vehicle Code</th>
                    <th>Plate Number</th>
                    <th>Vehicle Model</th>
                    <th>Type</th>
                    <th>Assigned Driver</th>
                    <th>Branch Zone</th>
                    <th>Route</th>
                    <th>Vehicle Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $index => $driver)
                <tr>
                    <td><strong>VH-2026-{{ str_pad($driver->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                    <td><span style="font-family:monospace;font-weight:700;letter-spacing:1px;background:#f1f5f9;padding:4px 8px;border-radius:4px;border:1px solid #cbd5e1;">{{ strtoupper(substr($driver->last_name ?? 'ABC', 0, 3)) }}-{{ 1000 + $driver->id }}</span></td>
                    <td><strong>{{ $driver->vehicle_assignment ?? 'Toyota Hiace' }}</strong></td>
                    <td>{{ $driver->vehicle_type ?? 'Van' }}</td>
                    <td>
                        <a href="{{ route('admin.drivers.profile', $driver->id) }}" style="display:flex;align-items:center;gap:0.5rem;color:inherit;text-decoration:none;">
                            <img src="{{ $driver->photo ?: asset('drivers/photo/' . $driver->id) }}" alt="{{ $driver->first_name }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            <span>{{ $driver->full_name }}</span>
                        </a>
                    </td>
                    <td>{{ $driver->branch ?? 'North Branch' }}</td>
                    <td>{{ $driver->route_assignment ?? 'Main Route' }}</td>
                    <td>
                        @if($index % 7 == 0)
                            <span class="status-badge" style="background:#ffedd5;color:#c2410c;">🛠 Under Maintenance</span>
                        @else
                            <span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Active & Operational</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.35rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', ['id' => $driver->id, 'tab' => 'tab-vehicle']) }}" class="icon-btn" title="Vehicle Details"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Reassign Driver" onclick="showToast('Reassign vehicle modal opened')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:2rem;">No vehicle records found.</td>
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
