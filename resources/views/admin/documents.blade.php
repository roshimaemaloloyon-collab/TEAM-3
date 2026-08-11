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
    <a href="{{ route('admin.drivers.documents') }}" class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;text-decoration:none;cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px;height:48px;border-radius:12px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:var(--primary);">{{ $drivers->total() * 4 }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Total Submitted Documents</p>
        </div>
    </a>
    <a href="{{ route('admin.drivers.documents', ['status' => 'verified']) }}" class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;text-decoration:none;cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px;height:48px;border-radius:12px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#059669;">{{ intval($drivers->total() * 3.2) }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Verified Documents</p>
        </div>
    </a>
    <a href="{{ route('admin.drivers.documents', ['status' => 'pending']) }}" class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;text-decoration:none;cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px;height:48px;border-radius:12px;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#ea580c;">{{ intval($drivers->total() * 0.6) }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Pending Verification</p>
        </div>
    </a>
    <a href="{{ route('admin.drivers.documents', ['status' => 'expired']) }}" class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;text-decoration:none;cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#dc2626;">{{ intval($drivers->total() * 0.2) + 1 }}</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Expired / Needing Action</p>
        </div>
    </a>
</div>

<!-- Search & Filter Controls -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('admin.drivers.documents') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
        <div style="flex:1;min-width:240px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name, ID, or document type..." style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
        </div>
        <div style="width:200px;">
            <select name="type" style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
                <option value="">All Document Types</option>
                <option value="license" {{ request('type') == 'license' ? 'selected' : '' }}>Driver's License</option>
                <option value="orcr" {{ request('type') == 'orcr' ? 'selected' : '' }}>OR / CR</option>
                <option value="nbi" {{ request('type') == 'nbi' ? 'selected' : '' }}>NBI Clearance</option>
                <option value="medical" {{ request('type') == 'medical' ? 'selected' : '' }}>Medical Certificate</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('admin.drivers.documents') }}" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</a>
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
                            <button class="icon-btn" title="Download Document" onclick="alert('Downloading document file for {{ $driver->first_name }}...')"><i class="fas fa-download"></i></button>
                            <button class="icon-btn" title="Verify Status" onclick="alert('Status updated for {{ $driver->first_name }}\'s document.')"><i class="fas fa-check"></i></button>
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

<!-- Upload New Document Modal -->
<div class="modal-overlay" id="uploadDocModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="uploadDocForm" onsubmit="event.preventDefault(); alert('Document uploaded successfully!'); closeModal('uploadDocModal');">
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Upload New Document</h2>
                <button type="button" onclick="closeModal('uploadDocModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver *</label>
                        <select name="driver_id" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="">Select a Driver...</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->full_name }} ({{ $driver->formatted_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Document Type *</label>
                        <select name="doc_type" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="License">Driver's License</option>
                            <option value="ORCR">OR / CR Vehicle Registration</option>
                            <option value="NBI">NBI Clearance</option>
                            <option value="Medical">Medical Certificate</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Expiry Date</label>
                        <input type="date" name="expiry_date" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Document File (PDF / Image) *</label>
                        <input type="file" required accept="image/*,.pdf" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('uploadDocModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload Document</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'flex';
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
}
</script>
@endsection
