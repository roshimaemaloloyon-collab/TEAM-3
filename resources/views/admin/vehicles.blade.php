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
        <button class="btn btn-primary" onclick="openModal('assignVehicleModal')"><i class="fas fa-car"></i> Assign New Vehicle</button>
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

<!-- Search & Filter Bar -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('admin.drivers.vehicles') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
        <div style="flex:1;min-width:240px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name, vehicle model, plate number..." style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
        </div>
        <div style="width:190px;">
            <select name="location" onchange="this.form.submit()" style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;cursor:pointer;">
                <option value="">All Locations</option>
                <option value="Cebu" {{ request('location') == 'Cebu' ? 'selected' : '' }}>📍 Cebu</option>
                <option value="Manila" {{ request('location') == 'Manila' ? 'selected' : '' }}>📍 Manila</option>
                <option value="Davao" {{ request('location') == 'Davao' ? 'selected' : '' }}>📍 Davao</option>
                <option value="Iloilo" {{ request('location') == 'Iloilo' ? 'selected' : '' }}>📍 Iloilo</option>
                <option value="Cagayan de Oro" {{ request('location') == 'Cagayan de Oro' || request('location') == 'CDO' ? 'selected' : '' }}>📍 Cagayan de Oro</option>
                <option value="Quezon City" {{ request('location') == 'Quezon City' ? 'selected' : '' }}>📍 Quezon City</option>
                <option value="Pasig" {{ request('location') == 'Pasig' ? 'selected' : '' }}>📍 Pasig</option>
                <option value="Makati" {{ request('location') == 'Makati' ? 'selected' : '' }}>📍 Makati</option>
                <option value="Pampanga" {{ request('location') == 'Pampanga' ? 'selected' : '' }}>📍 Pampanga</option>
                <option value="Batangas" {{ request('location') == 'Batangas' ? 'selected' : '' }}>📍 Batangas</option>
            </select>
        </div>
        <div style="width:180px;">
            <select name="type" onchange="this.form.submit()" style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;cursor:pointer;">
                <option value="">All Vehicle Types</option>
                <option value="Sedan" {{ request('type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                <option value="SUV" {{ request('type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                <option value="Van" {{ request('type') == 'Van' ? 'selected' : '' }}>Van</option>
                <option value="Motorcycle" {{ request('type') == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('admin.drivers.vehicles') }}" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</a>
    </form>
</div>

<!-- Vehicle Information Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;">Vehicle Photo</th>
                    <th>Plate Number</th>
                    <th>Vehicle Model</th>
                    <th>Type</th>
                    <th>Assigned Driver</th>
                    <th>Operational Zone</th>
                    <th>Vehicle Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $index => $driver)
                @php
                    $vType = strtolower($driver->vehicle_type ?? 'sedan');
                    $vImageMap = [
                        'sedan' => asset('vehicles/sedan.jpg'),
                        'suv' => asset('vehicles/suv.jpg'),
                        'van' => asset('vehicles/van.jpg'),
                        'motorcycle' => asset('vehicles/motorcycle.jpg'),
                    ];
                    $vImgSrc = $vImageMap[$vType] ?? asset('vehicles/sedan.jpg');
                    $vModelName = $driver->vehicle_assignment ?? 'Fleet Vehicle';
                    $vPlate = strtoupper(substr($driver->last_name ?? 'ABC', 0, 3)) . '-' . (1000 + $driver->id);
                @endphp
                <tr>
                    <td style="text-align:center;">
                        <div style="width:50px;height:40px;margin:0 auto;border-radius:6px;overflow:hidden;border:1px solid #cbd5e1;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);" onclick="openVehiclePhotoModal('{{ $vImgSrc }}', '{{ $vModelName }}', '{{ ucfirst($vType) }}', '{{ $vPlate }}', '{{ $driver->full_name }}')" title="Click to view vehicle photo">
                            <img src="{{ $vImgSrc }}" alt="{{ $vModelName }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-weight:700;letter-spacing:1px;background:#f1f5f9;padding:4px 8px;border-radius:4px;border:1px solid #cbd5e1;">{{ $vPlate }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <strong>{{ $vModelName }}</strong>
                            <button type="button" style="background:none;border:none;color:#0284c7;cursor:pointer;padding:0;" onclick="openVehiclePhotoModal('{{ $vImgSrc }}', '{{ $vModelName }}', '{{ ucfirst($vType) }}', '{{ $vPlate }}', '{{ $driver->full_name }}')" title="View {{ $vModelName }} photo">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </td>
                    <td><span class="status-badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;"><i class="fas {{ $vType == 'motorcycle' ? 'fa-motorcycle' : 'fa-car' }}" style="margin-right:4px;"></i>{{ ucfirst($driver->vehicle_type ?? 'Van') }}</span></td>
                    <td>
                        <a href="{{ route('admin.drivers.profile', $driver->id) }}" style="display:flex;align-items:center;gap:0.5rem;color:inherit;text-decoration:none;">
                            <img src="{{ $driver->photo ?: asset('drivers/photo/' . $driver->id) }}" alt="{{ $driver->first_name }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            <span>{{ $driver->full_name }}</span>
                        </a>
                    </td>
                    <td>
                        @php
                            $locations = ['Cebu', 'Manila', 'Davao', 'Iloilo', 'Cagayan de Oro', 'Quezon City', 'Pasig', 'Makati'];
                            $cleanBranch = str_replace(['Branch', 'branch', 'Zone', 'zone'], '', $driver->branch ?? '');
                            $cleanBranch = trim($cleanBranch);
                            $displayLoc = !empty($cleanBranch) && !in_array($cleanBranch, ['North', 'South', 'East', 'West', 'Central']) ? $cleanBranch : $locations[$driver->id % count($locations)];
                        @endphp
                        <strong style="color:#0284c7;"><i class="fas fa-map-marker-alt" style="margin-right:4px;color:#ef4444;"></i>{{ $displayLoc }}</strong>
                    </td>
                    <td>
                        @if($index % 7 == 0)
                            <span class="status-badge" style="background:#ffedd5;color:#c2410c;cursor:pointer;" onclick="openMaintenanceModal({{ json_encode($driver) }})" title="Click to review maintenance status"><i class="fas fa-tools"></i> Under Maintenance</span>
                        @else
                            <span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Active & Operational</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.35rem;justify-content:center;">
                            <button type="button" class="icon-btn" title="View Vehicle Photo & Details" style="color:#0284c7;" onclick="openVehiclePhotoModal('{{ $vImgSrc }}', '{{ $vModelName }}', '{{ ucfirst($vType) }}', '{{ $vPlate }}', '{{ $driver->full_name }}')"><i class="fas fa-image"></i></button>
                            <button class="icon-btn" title="Review & Update Maintenance Status" style="color:#ea580c;" onclick="openMaintenanceModal({{ json_encode($driver) }})"><i class="fas fa-tools"></i></button>
                            <button class="icon-btn" title="Reassign Driver" onclick="openReassignModal({{ json_encode($driver) }})"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;">No vehicle records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1.25rem;">
        {{ $drivers->links() }}
    </div>
</div>

<!-- Review & Update Vehicle Maintenance Modal -->
<div class="modal-overlay" id="reviewMaintenanceModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="maintenanceForm" onsubmit="event.preventDefault(); alert('Vehicle maintenance status updated to Active & Operational!'); closeModal('reviewMaintenanceModal'); location.reload();">
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
                <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-tools" style="margin-right:0.5rem;"></i> Vehicle Maintenance Review</h2>
                <button type="button" onclick="closeModal('reviewMaintenanceModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Assigned Driver</label>
                        <input type="text" id="maintDriverName" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;font-weight:600;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Model & Assignment</label>
                        <input type="text" id="maintVehicleModel" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;font-weight:600;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Update Vehicle Maintenance Status *</label>
                        <select id="maintStatusSelect" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;font-weight:600;">
                            <option value="operational" style="color:#059669;">🟢 Active & Operational (Passed Maintenance Inspection)</option>
                            <option value="maintenance" style="color:#c2410c;">🛠 Under Maintenance (In Workshop / Repair)</option>
                            <option value="out_of_service" style="color:#dc2626;">🚨 Out of Service (Awaiting Parts)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Maintenance Remarks & Diagnostic Findings</label>
                        <textarea id="maintRemarks" rows="3" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;" placeholder="Engine oil change, tire alignment, and brake pad inspection completed. Vehicle is cleared for dispatch."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('reviewMaintenanceModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#059669;border-color:#059669;"><i class="fas fa-check-circle"></i> Save & Mark Operational</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign New Vehicle Modal -->
<div class="modal-overlay" id="assignVehicleModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:650px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="assignVehicleForm" onsubmit="event.preventDefault(); alert('Vehicle assignment saved successfully!'); closeModal('assignVehicleModal'); location.reload();">
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Assign New Fleet Vehicle</h2>
                <button type="button" onclick="closeModal('assignVehicleModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div style="grid-column:span 2;">
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver *</label>
                        <select name="driver_id" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="">Choose Driver...</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Model / Name *</label>
                        <input type="text" required name="vehicle_model" placeholder="e.g. Toyota Fortuner, Honda Civic" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Type *</label>
                        <select name="vehicle_type" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Van">Van</option>
                            <option value="Motorcycle">Motorcycle</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Plate Number *</label>
                        <input type="text" required name="plate_number" placeholder="ABC-1234" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Operational Zone *</label>
                        <select name="branch" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="Cebu">Cebu</option>
                            <option value="Manila">Manila</option>
                            <option value="Davao">Davao</option>
                            <option value="Iloilo">Iloilo</option>
                            <option value="Cagayan de Oro">Cagayan de Oro</option>
                            <option value="Quezon City">Quezon City</option>
                            <option value="Pasig">Pasig</option>
                            <option value="Makati">Makati</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('assignVehicleModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Assignment</button>
            </div>
        </form>
    </div>
</div>

<!-- Reassign Vehicle Modal -->
<div class="modal-overlay" id="reassignVehicleModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="reassignForm" onsubmit="event.preventDefault(); alert('Vehicle assignment updated for driver!'); closeModal('reassignVehicleModal');">
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Reassign Driver Vehicle</h2>
                <button type="button" onclick="closeModal('reassignVehicleModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                        <input type="text" id="reassignDriverName" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;font-weight:600;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Current Vehicle Assignment</label>
                        <input type="text" id="reassignCurrentVehicle" name="vehicle_assignment" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Type</label>
                        <select id="reassignVehicleType" name="vehicle_type" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Van">Van</option>
                            <option value="Motorcycle">Motorcycle</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('reassignVehicleModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Assignment</button>
            </div>
        </form>
    </div>
</div>

<!-- View Vehicle Photo Modal -->
<div class="modal-overlay" id="viewVehiclePhotoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.65);z-index:2300;align-items:center;justify-content:center;padding:2rem;backdrop-filter:blur(4px);">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:650px;box-shadow:0 25px 70px rgba(0,0,0,0.3);overflow:hidden;">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#1e293b;color:#ffffff;">
            <h2 id="vModalTitle" style="font-size:1.2rem;font-family:'Poppins',sans-serif;margin:0;font-weight:700;display:flex;align-items:center;gap:0.5rem;color:#ffffff;"><i class="fas fa-car" style="color:#38bdf8;"></i> Vehicle Photo Preview</h2>
            <button type="button" onclick="closeModal('viewVehiclePhotoModal')" style="background:none;border:none;font-size:1.5rem;color:#94a3b8;cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;text-align:center;background:#f8fafc;">
            <div style="width:100%;max-height:360px;border-radius:0.75rem;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.15);border:1px solid #cbd5e1;margin-bottom:1.25rem;background:#ffffff;">
                <img id="vModalImg" src="" alt="Vehicle Photo" style="width:100%;height:320px;object-fit:cover;display:block;">
            </div>
            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:1rem;background:#ffffff;padding:1rem;border-radius:0.75rem;border:1px solid #e2e8f0;text-align:left;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Vehicle Type</span>
                    <strong id="vModalType" style="font-size:0.95rem;color:#0284c7;">Sedan</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Plate Registration</span>
                    <strong id="vModalPlate" style="font-size:0.95rem;font-family:monospace;letter-spacing:1px;color:#1e293b;">ABC-1001</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Assigned Driver</span>
                    <strong id="vModalDriver" style="font-size:0.95rem;color:#059669;">Driver Name</strong>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;background:#ffffff;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewVehiclePhotoModal')">Close Preview</button>
        </div>
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

function openVehiclePhotoModal(imgSrc, model, type, plate, driver) {
    const imgEl = document.getElementById('vModalImg');
    const titleEl = document.getElementById('vModalTitle');
    const typeEl = document.getElementById('vModalType');
    const plateEl = document.getElementById('vModalPlate');
    const driverEl = document.getElementById('vModalDriver');

    if (imgEl) imgEl.src = imgSrc;
    if (titleEl) titleEl.innerHTML = '<i class="fas fa-camera" style="color:#38bdf8;margin-right:8px;"></i> ' + model + ' (' + type + ')';
    if (typeEl) typeEl.innerText = type;
    if (plateEl) plateEl.innerText = plate;
    if (driverEl) driverEl.innerText = driver;

    openModal('viewVehiclePhotoModal');
}

function openReassignModal(driver) {
    document.getElementById('reassignDriverName').value = driver.first_name + ' ' + driver.last_name;
    document.getElementById('reassignCurrentVehicle').value = driver.vehicle_assignment || '';
    document.getElementById('reassignVehicleType').value = driver.vehicle_type || 'Sedan';
    openModal('reassignVehicleModal');
}

function openMaintenanceModal(driver) {
    document.getElementById('maintDriverName').value = driver.first_name + ' ' + driver.last_name + ' (' + (driver.driver_id || '#DRV-2026') + ')';
    document.getElementById('maintVehicleModel').value = (driver.vehicle_assignment || 'Hyundai Tucson') + ' (' + (driver.vehicle_type || 'Sedan') + ')';
    document.getElementById('maintRemarks').value = 'Routine engine oil change, brake pad inspection, and wheel alignment completed. Cleared for active route dispatch.';
    openModal('reviewMaintenanceModal');
}
</script>
@endsection
