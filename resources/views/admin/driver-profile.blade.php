@extends('admin.layouts.admin')

@section('title', 'TripWise — Driver Profile')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.drivers.index') }}">Manage Drivers</a>
    <span>/</span>
    <span>Driver Profile</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Driver Profile</h1>
        <p>Complete driver information, documents, performance history, and activity logs.</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Drivers</a>
        <button class="btn btn-primary" onclick="openModal('editDriverModal')"><i class="fas fa-edit"></i> Edit Driver</button>
    </div>
</div>

<!-- Profile Header Card -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
        <img src="{{ $driver->photo ?: asset('drivers/photo/' . ($driver->id ?? 1)) }}" alt="{{ $driver->full_name }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
        <div style="flex:1;min-width:200px;">
            <h2 style="font-size:1.5rem;color:var(--primary);margin:0 0 0.25rem;">{{ $driver->full_name }}</h2>
            <p style="font-size:0.9rem;color:var(--text-muted);margin:0 0 0.5rem;">{{ $driver->formatted_id }} • {{ $driver->branch ?? 'North Branch' }} • {{ $driver->route_assignment ?? 'North Route' }} • {{ $driver->vehicle_assignment ?? 'Unassigned' }}</p>
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                <span class="status-badge status-{{ strtolower($driver->status ?? 'active') }}">{{ ucfirst($driver->status ?? 'Active') }}</span>
                <span class="status-badge" style="background:#d1fae5;color:#065f46;">{{ number_format($driver->performance_score ?? 4.9, 1) }} Performance</span>
                <span class="status-badge" style="background:#dbeafe;color:#1e40af;">{{ number_format($driver->trips_count ?? 1248) }} Trips</span>
            </div>
        </div>
    </div>
</div>

<!-- Profile Tabs Navigation -->
<style>
    .driver-profile-tabs-wrapper {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 8px;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 16px -4px rgba(0, 0, 0, 0.04);
    }
    .driver-profile-tabs {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
    }
    .profile-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-family: 'Poppins', sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        flex: 1 0 auto;
        justify-content: center;
    }
    .profile-tab .tab-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
        font-size: 11px;
        transition: all 0.2s ease;
    }
    .profile-tab:hover {
        background: #fff5f5;
        border-color: #fca5a5;
        color: var(--primary, #F44336);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.1);
    }
    .profile-tab:hover .tab-icon {
        background: rgba(244, 67, 54, 0.15);
        color: var(--primary, #F44336);
    }
    .profile-tab.active {
        background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(244, 67, 54, 0.3);
    }
    .profile-tab.active .tab-icon {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .profile-page-tab {
        animation: fadeInTab 0.3s ease-out;
    }
    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="driver-profile-tabs-wrapper">
    <div class="driver-profile-tabs">
        <button class="profile-tab active" onclick="switchProfilePageTab(this, 'tab-overview')">
            <span class="tab-icon"><i class="fas fa-chart-pie"></i></span>
            <span>Overview</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-personal')">
            <span class="tab-icon"><i class="fas fa-user"></i></span>
            <span>Personal Information</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-employment')">
            <span class="tab-icon"><i class="fas fa-briefcase"></i></span>
            <span>Employment Details</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-documents')">
            <span class="tab-icon"><i class="fas fa-file-alt"></i></span>
            <span>Documents</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-vehicle')">
            <span class="tab-icon"><i class="fas fa-car"></i></span>
            <span>Vehicle Information</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-license')">
            <span class="tab-icon"><i class="fas fa-id-card"></i></span>
            <span>License Information</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-performance')">
            <span class="tab-icon"><i class="fas fa-chart-line"></i></span>
            <span>Performance History</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-training')">
            <span class="tab-icon"><i class="fas fa-graduation-cap"></i></span>
            <span>Training Records</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-evaluations')">
            <span class="tab-icon"><i class="fas fa-users"></i></span>
            <span>Peer-to-Peer Evaluations</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-recognition')">
            <span class="tab-icon"><i class="fas fa-trophy"></i></span>
            <span>Recognition & Awards</span>
        </button>
        <button class="profile-tab" onclick="switchProfilePageTab(this, 'tab-activity')">
            <span class="tab-icon"><i class="fas fa-history"></i></span>
            <span>Activity Logs</span>
        </button>
    </div>
</div>

<!-- Tab Contents -->
<div class="tab-content-wrapper">

    <!-- Overview Tab -->
    <div id="tab-overview" class="profile-page-tab">
        <div class="section-grid">
            <div class="section-card">
                <h3><i class="fas fa-user"></i> Quick Summary</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Full Name</label><p style="font-weight:600;">{{ $driver->full_name }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Driver ID</label><p style="font-weight:600;">{{ $driver->formatted_id }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Contact</label><p style="font-weight:600;">{{ $driver->contact_number ?? 'N/A' }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Email</label><p style="font-weight:600;">{{ $driver->email ?? 'N/A' }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Branch</label><p style="font-weight:600;">{{ $driver->branch ?? 'N/A' }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Route</label><p style="font-weight:600;">{{ $driver->route_assignment ?? 'N/A' }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle</label><p style="font-weight:600;">{{ $driver->vehicle_assignment ?? 'N/A' }}</p></div>
                    <div><label style="font-size:0.8rem;color:var(--text-muted);">Status</label><p><span class="status-badge status-{{ strtolower($driver->status ?? 'active') }}">{{ ucfirst($driver->status ?? 'Active') }}</span></p></div>
                </div>
            </div>
            <div class="section-card">
                <h3><i class="fas fa-chart-bar"></i> Performance Snapshot</h3>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1rem;">
                    <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;"><p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">{{ number_format($driver->performance_score ?? 4.9, 1) }}</p><p style="font-size:0.8rem;color:var(--text-muted);">Performance</p></div>
                    <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;"><p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">{{ number_format($driver->trips_count ?? 1248) }}</p><p style="font-size:0.8rem;color:var(--text-muted);">Trips</p></div>
                    <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;"><p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">{{ $driver->complaints_count ?? 0 }}</p><p style="font-size:0.8rem;color:var(--text-muted);">Complaints</p></div>
                </div>
            </div>
        </div>
        <div class="section-card" style="margin-top:1.5rem;">
            <h3><i class="fas fa-info-circle"></i> About</h3>
            <p style="font-size:0.9rem;color:var(--text-muted);line-height:1.6;">Dedicated and professional TNVS driver with 5+ years of experience in passenger transportation. Known for exceptional customer service, safe driving record, and punctuality. Consistently rated 4.8+ by passengers and peers.</p>
        </div>
    </div>

    <!-- Personal Information Tab -->
    <div id="tab-personal" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-user"></i> Personal Information</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
                <div><label style="font-size:0.8rem;color:var(--text-muted);">First Name</label><p style="font-weight:600;">{{ $driver->first_name }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Middle Name</label><p style="font-weight:600;">{{ $driver->middle_name ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Last Name</label><p style="font-weight:600;">{{ $driver->last_name }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Birth Date</label><p style="font-weight:600;">{{ $driver->birth_date ? \Carbon\Carbon::parse($driver->birth_date)->format('M d, Y') : 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Gender</label><p style="font-weight:600;">{{ $driver->gender ?? 'Male' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Civil Status</label><p style="font-weight:600;">{{ $driver->civil_status ?? 'Single' }}</p></div>
                <div style="grid-column:span 2;"><label style="font-size:0.8rem;color:var(--text-muted);">Address</label><p style="font-weight:600;">{{ $driver->address ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Contact Number</label><p style="font-weight:600;">{{ $driver->contact_number ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Email Address</label><p style="font-weight:600;">{{ $driver->email ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Emergency Contact Person</label><p style="font-weight:600;">{{ $driver->emergency_contact_person ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Emergency Contact Number</label><p style="font-weight:600;">{{ $driver->emergency_contact_number ?? 'N/A' }}</p></div>
            </div>
        </div>
    </div>

    <!-- Employment Details Tab -->
    <div id="tab-employment" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-briefcase"></i> Employment Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Driver ID</label><p style="font-weight:600;">{{ $driver->formatted_id }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Date Hired</label><p style="font-weight:600;">{{ $driver->date_hired ? \Carbon\Carbon::parse($driver->date_hired)->format('M d, Y') : 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Branch</label><p style="font-weight:600;">{{ $driver->branch ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle Assignment</label><p style="font-weight:600;">{{ $driver->vehicle_assignment ?? 'Unassigned' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle Type</label><p style="font-weight:600;">{{ $driver->vehicle_type ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Route Assignment</label><p style="font-weight:600;">{{ $driver->route_assignment ?? 'N/A' }}</p></div>
                <div style="grid-column:span 2;"><label style="font-size:0.8rem;color:var(--text-muted);">Employment Status</label><p><span class="status-badge status-{{ strtolower($driver->status ?? 'active') }}">{{ ucfirst($driver->status ?? 'Active') }}</span></p></div>
            </div>
        </div>
    </div>

    <!-- Documents Tab -->
    <div id="tab-documents" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
                <h3 style="margin:0;"><i class="fas fa-file-alt"></i> Driver Documents ({{ $driver->full_name }})</h3>
                <button class="btn btn-primary" onclick="openModal('uploadDocModal')"><i class="fas fa-upload"></i> Upload Document</button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Upload Date</th>
                            <th>Expiration Date</th>
                            <th>Verification Status</th>
                            <th>Last Updated By</th>
                            <th>Last Updated Date</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><i class="fas fa-id-card" style="color:var(--primary);margin-right:0.5rem;"></i>Driver's License</strong></td>
                            <td>{{ $driver->created_at ? $driver->created_at->format('M d, Y') : 'Jan 10, 2026' }}</td>
                            <td>{{ $driver->license_expiration ? \Carbon\Carbon::parse($driver->license_expiration)->format('M d, Y') : 'Dec 20, 2026' }}</td>
                            <td><span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Verified</span></td>
                            <td>Admin User</td>
                            <td>{{ $driver->updated_at ? $driver->updated_at->format('M d, Y') : 'Jan 10, 2026' }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:0.35rem;justify-content:center;flex-wrap:wrap;">
                                    <button class="icon-btn" title="Upload Driver's License" onclick="openDocUploadModal('Driver\'s License')"><i class="fas fa-upload"></i></button>
                                    <button class="icon-btn" title="Replace Document" onclick="openDocUploadModal('Driver\'s License')"><i class="fas fa-sync-alt"></i></button>
                                    <button class="icon-btn" title="Preview Document" onclick="previewDocument('Driver\'s License', '{{ $driver->full_name }}')"><i class="fas fa-eye"></i></button>
                                    <a href="{{ route('admin.drivers.documents.download', ['id' => $driver->id, 'type' => 'license']) }}" class="icon-btn" title="Download Document"><i class="fas fa-download"></i></a>
                                    <button class="icon-btn" title="Delete Document" onclick="deleteDocumentRow(this, 'Driver\'s License')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-file-alt" style="color:var(--primary);margin-right:0.5rem;"></i>OR/CR ({{ $driver->vehicle_assignment ?? 'Vehicle' }})</strong></td>
                            <td>Jan 10, 2026</td>
                            <td>Jun 20, 2027</td>
                            <td><span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Verified</span></td>
                            <td>Admin User</td>
                            <td>Jan 10, 2026</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:0.35rem;justify-content:center;flex-wrap:wrap;">
                                    <button class="icon-btn" title="Upload OR/CR" onclick="openDocUploadModal('OR/CR')"><i class="fas fa-upload"></i></button>
                                    <button class="icon-btn" title="Replace Document" onclick="openDocUploadModal('OR/CR')"><i class="fas fa-sync-alt"></i></button>
                                    <button class="icon-btn" title="Preview Document" onclick="previewDocument('OR/CR', '{{ $driver->full_name }}')"><i class="fas fa-eye"></i></button>
                                    <a href="{{ route('admin.drivers.documents.download', ['id' => $driver->id, 'type' => 'orcr']) }}" class="icon-btn" title="Download Document"><i class="fas fa-download"></i></a>
                                    <button class="icon-btn" title="Delete Document" onclick="deleteDocumentRow(this, 'OR/CR')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-file-image" style="color:var(--primary);margin-right:0.5rem;"></i>NBI Clearance</strong></td>
                            <td>Mar 15, 2026</td>
                            <td>Mar 15, 2027</td>
                            <td><span class="status-badge" style="background:#ffedd5;color:#c2410c;">🟡 Pending Verification</span></td>
                            <td>Admin User</td>
                            <td>Mar 15, 2026</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:0.35rem;justify-content:center;flex-wrap:wrap;">
                                    <button class="icon-btn" title="Upload NBI Clearance" onclick="openDocUploadModal('NBI Clearance')"><i class="fas fa-upload"></i></button>
                                    <button class="icon-btn" title="Replace Document" onclick="openDocUploadModal('NBI Clearance')"><i class="fas fa-sync-alt"></i></button>
                                    <button class="icon-btn" title="Preview Document" onclick="previewDocument('NBI Clearance', '{{ $driver->full_name }}')"><i class="fas fa-eye"></i></button>
                                    <a href="{{ route('admin.drivers.documents.download', ['id' => $driver->id, 'type' => 'nbi']) }}" class="icon-btn" title="Download Document"><i class="fas fa-download"></i></a>
                                    <button class="icon-btn" title="Delete Document" onclick="deleteDocumentRow(this, 'NBI Clearance')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-shield-alt" style="color:var(--primary);margin-right:0.5rem;"></i>Police Clearance</strong></td>
                            <td>Feb 01, 2026</td>
                            <td>Feb 01, 2027</td>
                            <td><span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Verified</span></td>
                            <td>Admin User</td>
                            <td>Feb 01, 2026</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:0.35rem;justify-content:center;flex-wrap:wrap;">
                                    <button class="icon-btn" title="Upload Police Clearance" onclick="openDocUploadModal('Police Clearance')"><i class="fas fa-upload"></i></button>
                                    <button class="icon-btn" title="Replace Document" onclick="openDocUploadModal('Police Clearance')"><i class="fas fa-sync-alt"></i></button>
                                    <button class="icon-btn" title="Preview Document" onclick="previewDocument('Police Clearance', '{{ $driver->full_name }}')"><i class="fas fa-eye"></i></button>
                                    <a href="{{ route('admin.drivers.documents.download', ['id' => $driver->id, 'type' => 'police']) }}" class="icon-btn" title="Download Document"><i class="fas fa-download"></i></a>
                                    <button class="icon-btn" title="Delete Document" onclick="deleteDocumentRow(this, 'Police Clearance')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-notes-medical" style="color:var(--primary);margin-right:0.5rem;"></i>Medical Certificate</strong></td>
                            <td>Jan 05, 2026</td>
                            <td>Jan 05, 2027</td>
                            <td><span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Verified</span></td>
                            <td>Admin User</td>
                            <td>Jan 05, 2026</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:0.35rem;justify-content:center;flex-wrap:wrap;">
                                    <button class="icon-btn" title="Upload Medical Certificate" onclick="openDocUploadModal('Medical Certificate')"><i class="fas fa-upload"></i></button>
                                    <button class="icon-btn" title="Replace Document" onclick="openDocUploadModal('Medical Certificate')"><i class="fas fa-sync-alt"></i></button>
                                    <button class="icon-btn" title="Preview Document" onclick="previewDocument('Medical Certificate', '{{ $driver->full_name }}')"><i class="fas fa-eye"></i></button>
                                    <a href="{{ route('admin.drivers.documents.download', ['id' => $driver->id, 'type' => 'medical']) }}" class="icon-btn" title="Download Document"><i class="fas fa-download"></i></a>
                                    <button class="icon-btn" title="Delete Document" onclick="deleteDocumentRow(this, 'Medical Certificate')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vehicle Information Tab -->
    <div id="tab-vehicle" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-car"></i> Vehicle Information ({{ $driver->full_name }})</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle ID</label><p style="font-weight:600;">VH-2026-{{ str_pad($driver->id, 3, '0', STR_PAD_LEFT) }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Plate Number</label><p style="font-weight:600;">{{ strtoupper(substr($driver->last_name ?? 'ABC', 0, 3)) }}-{{ 1000 + $driver->id }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle Type</label><p style="font-weight:600;">{{ $driver->vehicle_type ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle Model / Name</label><p style="font-weight:600;">{{ $driver->vehicle_assignment ?? 'Unassigned' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Assigned Branch</label><p style="font-weight:600;">{{ $driver->branch ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Assigned Route</label><p style="font-weight:600;">{{ $driver->route_assignment ?? 'N/A' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Year Model</label><p style="font-weight:600;">2023</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Color</label><p style="font-weight:600;">Pearl White</p></div>
                <div style="grid-column:span 2;"><label style="font-size:0.8rem;color:var(--text-muted);">Vehicle Status</label><p><span class="status-badge status-success">Active & Operational</span></p></div>
            </div>
        </div>
    </div>

    <!-- License Information Tab -->
    <div id="tab-license" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-id-card"></i> License Information</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;">
                <div><label style="font-size:0.8rem;color:var(--text-muted);">License Number</label><p style="font-weight:600;">{{ $driver->license_number ?? 'N01-12-345678' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">License Type</label><p style="font-weight:600;">Professional Driver</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Restriction Codes</label><p style="font-weight:600;">1, 2, 3</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Date Hired</label><p style="font-weight:600;">{{ $driver->date_hired ? \Carbon\Carbon::parse($driver->date_hired)->format('M d, Y') : 'Jan 15, 2021' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">Expiration Date</label><p style="font-weight:600;">{{ $driver->license_expiration ? \Carbon\Carbon::parse($driver->license_expiration)->format('M d, Y') : 'Mar 15, 2027' }}</p></div>
                <div><label style="font-size:0.8rem;color:var(--text-muted);">License Status</label><p><span class="status-badge status-success">Valid</span></p></div>
            </div>
        </div>
    </div>

    <!-- Performance History Tab -->
    <div id="tab-performance" class="profile-page-tab" style="display:none;">
        <div class="section-grid">
            <div class="section-card">
                <h3><i class="fas fa-chart-line"></i> Performance Scores</h3>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1rem;">
                    <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;"><p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">4.9/5</p><p style="font-size:0.8rem;color:var(--text-muted);">Customer Rating</p></div>
                    <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;"><p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">4.8/5</p><p style="font-size:0.8rem;color:var(--text-muted);">Peer Evaluation</p></div>
                    <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;"><p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">1,248</p><p style="font-size:0.8rem;color:var(--text-muted);">Completed Trips</p></div>
                </div>
            </div>
            <div class="section-card">
                <h3><i class="fas fa-calendar-alt"></i> Monthly Performance</h3>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-top:1rem;">
                    <span class="status-badge status-success">Jan: 4.8</span>
                    <span class="status-badge status-success">Feb: 4.9</span>
                    <span class="status-badge status-success">Mar: 4.7</span>
                    <span class="status-badge status-success">Apr: 4.9</span>
                    <span class="status-badge status-success">May: 4.8</span>
                    <span class="status-badge status-success">Jun: 4.9</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Training Records Tab -->
    <div id="tab-training" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-chalkboard-teacher"></i> Training Records</h3>
            <div style="display:flex;flex-direction:column;gap:0.75rem;margin-top:1rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Defensive Driving Workshop</p><p style="font-size:0.8rem;color:var(--text-muted);">July 15, 2026</p></div>
                    <span class="status-badge status-success">Completed</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">First Aid Certification</p><p style="font-size:0.8rem;color:var(--text-muted);">June 20, 2026</p></div>
                    <span class="status-badge status-success">Completed</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;">
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Customer Service Excellence</p><p style="font-size:0.8rem;color:var(--text-muted);">March 10, 2026</p></div>
                    <span class="status-badge status-success">Completed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Peer-to-Peer Evaluations Tab -->
    <div id="tab-evaluations" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-users"></i> Peer-to-Peer Evaluations</h3>
            <div style="display:flex;flex-direction:column;gap:0.75rem;margin-top:1rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Evaluation by Maria Santos</p><p style="font-size:0.8rem;color:var(--text-muted);">June 2026</p></div>
                    <span class="status-badge status-success">4.8/5</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Evaluation by Pedro Reyes</p><p style="font-size:0.8rem;color:var(--text-muted);">May 2026</p></div>
                    <span class="status-badge status-success">4.9/5</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;">
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Evaluation by Ana Lim</p><p style="font-size:0.8rem;color:var(--text-muted);">April 2026</p></div>
                    <span class="status-badge status-success">4.7/5</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recognition & Awards Tab -->
    <div id="tab-recognition" class="profile-page-tab" style="display:none;">
        <div class="section-grid">
            <div class="section-card" style="text-align:center;">
                <p style="font-size:2.5rem;margin:0;">🏆</p>
                <p style="font-weight:600;margin:0.5rem 0 0;">Driver of the Month</p>
                <p style="font-size:0.85rem;color:var(--text-muted);">June 2026</p>
            </div>
            <div class="section-card" style="text-align:center;">
                <p style="font-size:2.5rem;margin:0;">⭐</p>
                <p style="font-weight:600;margin:0.5rem 0 0;">Excellent Performer</p>
                <p style="font-size:0.85rem;color:var(--text-muted);">Q2 2026</p>
            </div>
            <div class="section-card" style="text-align:center;">
                <p style="font-size:2.5rem;margin:0;">🌟</p>
                <p style="font-weight:600;margin:0.5rem 0 0;">5-Star Rating</p>
                <p style="font-size:0.85rem;color:var(--text-muted);">Consecutive 6 Months</p>
            </div>
        </div>
    </div>

    <!-- Activity Logs Tab -->
    <div id="tab-activity" class="profile-page-tab" style="display:none;">
        <div class="section-card">
            <h3><i class="fas fa-history"></i> Recent Activity</h3>
            <div style="display:flex;flex-direction:column;gap:0.75rem;margin-top:1rem;">
                <div style="display:flex;gap:1rem;align-items:flex-start;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--beige);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-file-upload" style="color:var(--primary);"></i></div>
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Document Uploaded</p><p style="font-size:0.8rem;color:var(--text-muted);">Driver's License uploaded by Admin User</p><p style="font-size:0.75rem;color:var(--text-muted);">Jan 10, 2026 at 10:30 AM</p></div>
                </div>
                <div style="display:flex;gap:1rem;align-items:flex-start;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--beige);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-edit" style="color:var(--primary);"></i></div>
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Profile Updated</p><p style="font-size:0.8rem;color:var(--text-muted);">Employment details updated by Admin User</p><p style="font-size:0.75rem;color:var(--text-muted);">Jan 08, 2026 at 2:15 PM</p></div>
                </div>
                <div style="display:flex;gap:1rem;align-items:flex-start;padding:0.75rem;border-bottom:1px solid var(--border);">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--beige);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-check-circle" style="color:var(--success);"></i></div>
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Status Change</p><p style="font-size:0.8rem;color:var(--text-muted);">Account activated by Admin User</p><p style="font-size:0.75rem;color:var(--text-muted);">Jan 05, 2026 at 9:00 AM</p></div>
                </div>
                <div style="display:flex;gap:1rem;align-items:flex-start;padding:0.75rem;">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--beige);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-award" style="color:var(--warning);"></i></div>
                    <div><p style="font-weight:600;margin:0;font-size:0.9rem;">Award Granted</p><p style="font-size:0.8rem;color:var(--text-muted);">Driver of the Month - June 2026</p><p style="font-size:0.75rem;color:var(--text-muted);">Jul 01, 2026 at 8:00 AM</p></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Edit Driver Modal -->
<div class="modal-overlay" id="editDriverModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:900px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Driver</h2>
            <button onclick="closeModal('editDriverModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div class="modal-tabs" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--border);flex-wrap:wrap;">
                <button class="modal-tab active" onclick="switchTab(this, 'editPersonalTab')">Personal Information</button>
                <button class="modal-tab" onclick="switchTab(this, 'editContactTab')">Contact Information</button>
                <button class="modal-tab" onclick="switchTab(this, 'editEmploymentTab')">Employment Details</button>
                <button class="modal-tab" onclick="switchTab(this, 'editVehicleTab')">Vehicle Assignment</button>
                <button class="modal-tab" onclick="switchTab(this, 'editAccountTab')">Account Credentials</button>
            </div>
            <div id="editPersonalTab" class="tab-content">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">First Name</label><input type="text" value="Juan" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Middle Name</label><input type="text" value="Santos" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Last Name</label><input type="text" value="Dela Cruz" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Birth Date</label><input type="date" value="1990-03-15" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Gender</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Male</option><option>Female</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Civil Status</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Single</option><option selected>Married</option><option>Widowed</option></select></div>
                    <div style="grid-column:span 2;"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Address</label><input type="text" value="123 Main St., Brgy. San Jose, Quezon City" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                </div>
            </div>
            <div id="editContactTab" class="tab-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Contact Number</label><input type="tel" value="+63 912 345 6789" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email Address</label><input type="email" value="juan.delacruz@email.com" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Emergency Contact Person</label><input type="text" value="Maria Dela Cruz" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Emergency Contact Number</label><input type="tel" value="+63 912 345 6790" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                </div>
            </div>
            <div id="editEmploymentTab" class="tab-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Branch Assignment</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>North Branch</option><option>South Branch</option><option>East Branch</option><option>West Branch</option><option>Central Branch</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Route Assignment</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>North Route</option><option>South Route</option><option>East Route</option><option>West Route</option><option>Central Route</option></select></div>
                    <div style="grid-column:span 2;"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Status</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Active</option><option>Inactive</option><option>Under Review</option><option>Suspended</option><option>Archived</option></select></div>
                </div>
            </div>
            <div id="editVehicleTab" class="tab-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Assignment</label><input type="text" value="Toyota Fortuner" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Type</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Sedan</option><option selected>SUV</option><option>Van</option><option>Motorcycle</option></select></div>
                </div>
            </div>
            <div id="editAccountTab" class="tab-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Username</label><input type="text" value="juan.delacruz" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">User Role</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Driver</option><option>Senior Driver</option><option>Lead Driver</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">New Password</label><input type="password" placeholder="Leave blank to keep current" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Confirm New Password</label><input type="password" placeholder="Confirm new password" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('editDriverModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveEditDriver()">Save Changes</button>
        </div>
    </div>
</div>

<!-- Activate/Deactivate Confirmation Modal -->
<div class="modal-overlay" id="statusModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:450px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.1rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Confirm Status Change</h2>
            <button onclick="closeModal('statusModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;text-align:center;">
            <i class="fas fa-exclamation-triangle" style="font-size:3rem;color:var(--warning);margin-bottom:1rem;"></i>
            <p style="font-size:1rem;color:var(--text-dark);margin-bottom:0.5rem;">Are you sure you want to change this driver's account status?</p>
            <p style="font-size:0.85rem;color:var(--text-muted);">This action will immediately affect the driver's access to the system.</p>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:center;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
            <button class="btn btn-primary" onclick="confirmStatusChange()">Confirm</button>
        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal-overlay" id="archiveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:450px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.1rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Confirm Archive</h2>
            <button onclick="closeModal('archiveModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;text-align:center;">
            <i class="fas fa-archive" style="font-size:3rem;color:var(--primary);margin-bottom:1rem;"></i>
            <p style="font-size:1rem;color:var(--text-dark);margin-bottom:0.5rem;">Are you sure you want to archive this driver?</p>
            <p style="font-size:0.85rem;color:var(--text-muted);">The driver will be removed from the active list. All historical records will be retained and can be restored later.</p>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:center;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('archiveModal')">Cancel</button>
            <button class="btn btn-primary" onclick="confirmArchive()">Archive Driver</button>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal-overlay" id="uploadDocModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:600px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 id="uploadModalTitle" style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Upload Document</h2>
            <button onclick="closeModal('uploadDocModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Document Type</label>
                <select id="modalDocTypeSelect" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    <option value="Driver's License">Driver's License</option>
                    <option value="OR/CR">OR/CR</option>
                    <option value="NBI Clearance">NBI Clearance</option>
                    <option value="Police Clearance">Police Clearance</option>
                    <option value="Medical Certificate">Medical Certificate</option>
                    <option value="Drug Test Result">Drug Test Result</option>
                    <option value="Vehicle Registration">Vehicle Registration</option>
                    <option value="Vehicle Insurance">Vehicle Insurance</option>
                    <option value="Other Supporting Documents">Other Supporting Documents</option>
                </select>
            </div>
            <div style="border:2px dashed var(--border);border-radius:1rem;padding:3rem;text-align:center;margin-bottom:1rem;background:var(--beige);cursor:pointer;" onclick="document.getElementById('hiddenFileInput').click()">
                <i class="fas fa-cloud-upload-alt" style="font-size:3rem;color:var(--primary);margin-bottom:1rem;"></i>
                <p style="font-weight:600;margin:0 0 0.5rem;">Drag & Drop files here</p>
                <p style="font-size:0.85rem;color:var(--text-muted);margin:0 0 1rem;">or</p>
                <button type="button" class="btn btn-primary">Browse Files</button>
                <input type="file" id="hiddenFileInput" style="display:none;" onchange="showToast('File selected: ' + (this.files[0] ? this.files[0].name : ''))">
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:1rem;">Supported: PDF, JPG, PNG (Max 10MB)</p>
            </div>
            <div id="uploadProgress" style="display:none;">
                <p style="font-size:0.85rem;font-weight:600;margin-bottom:0.5rem;">Uploading...</p>
                <div class="progress-bar"><div class="progress-fill" style="width:60%;"></div></div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('uploadDocModal')">Cancel</button>
            <button class="btn btn-primary" onclick="uploadDocument()">Upload</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

function switchTab(btn, tabId) {
    const modal = btn.closest('.modal-container');
    modal.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    modal.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
    btn.classList.add('active');
    document.getElementById(tabId).style.display = 'block';
}

function switchProfilePageTab(btn, tabId) {
    document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.profile-page-tab').forEach(t => t.style.display = 'none');
    btn.classList.add('active');
    document.getElementById(tabId).style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam && document.getElementById(tabParam)) {
        const targetBtn = document.querySelector(`.profile-tab[onclick*="${tabParam}"]`);
        if (targetBtn) {
            switchProfilePageTab(targetBtn, tabParam);
        }
    }
});

function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function editDriver(id) {
    const form = document.getElementById('editDriverForm');
    if (form) {
        form.action = `/admin/drivers/${id}`;
    }
    openModal('editDriverModal');
}

function openStatusModal(id, currentStatus) {
    openModal('statusModal');
}

function openArchiveModal(id) {
    openModal('archiveModal');
}

function saveDriver() {
    closeModal('addDriverModal');
    showToast('Driver successfully added.');
}

function saveEditDriver() {
    closeModal('editDriverModal');
    showToast('Driver information updated successfully.');
}

function confirmStatusChange() {
    closeModal('statusModal');
    showToast('Driver account status updated.');
}

function confirmArchive() {
    closeModal('archiveModal');
    showToast('Driver archived successfully.');
}

function openDocUploadModal(docType) {
    const select = document.getElementById('modalDocTypeSelect');
    if (select) {
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].text.toLowerCase().includes(docType.toLowerCase())) {
                select.selectedIndex = i;
                break;
            }
        }
    }
    const titleHeader = document.getElementById('uploadModalTitle');
    if (titleHeader) {
        titleHeader.textContent = `Upload / Replace ${docType}`;
    }
    openModal('uploadDocModal');
}

function previewDocument(docType, driverName) {
    alert(`DOCUMENT PREVIEW\n----------------\nDocument: ${docType}\nDriver: ${driverName}\nStatus: Verified\nFile Format: PDF / Image\n\nPreview loading completed!`);
}

function deleteDocumentRow(btn, docType) {
    if (confirm(`Are you sure you want to delete ${docType}?`)) {
        const tr = btn.closest('tr');
        if (tr) {
            tr.style.opacity = '0.3';
            setTimeout(() => {
                tr.remove();
                showToast(`${docType} deleted successfully.`);
            }, 500);
        }
    }
}

function uploadDocument() {
    const progress = document.getElementById('uploadProgress');
    if (progress) progress.style.display = 'block';
    const select = document.getElementById('modalDocTypeSelect');
    const docName = select ? select.value : 'Document';
    setTimeout(() => {
        closeModal('uploadDocModal');
        showToast(`${docName} uploaded & verified successfully.`);
        if (progress) progress.style.display = 'none';
    }, 1200);
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

const style = document.createElement('style');
style.textContent = `
    .driver-profile-tabs {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 0.25rem;
    }
    .profile-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 42px;
        padding: 0 18px;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        background: #FFFFFF;
        color: #374151;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        transition: all 0.25s ease;
    }
    .profile-tab:hover {
        background: #FFF3F2;
        border-color: #F44336;
        color: #F44336;
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.12);
        transform: translateY(-2px);
    }
    .profile-tab:hover i {
        color: #F44336;
    }
    .profile-tab.active {
        background: #ff4d3d;
        border-color: transparent;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(255, 77, 61, 0.25);
    }
    .profile-tab.active i {
        color: #FFFFFF;
    }
    .profile-tab i {
        font-size: 14px;
        color: #6B7280;
        transition: all 0.25s ease;
    }
    .profile-page-tab {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
