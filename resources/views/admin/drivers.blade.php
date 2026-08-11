@extends('admin.layouts.admin')

@section('title', 'TripWise — Manage Drivers')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <span>Manage Drivers</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Manage Drivers</h1>
        <p>View, register, update, and manage all registered TNVS drivers within the agency.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addDriverModal')"><i class="fas fa-plus"></i> Add New Driver</button>
</div>

<!-- Quick Statistics Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $totalDrivers ?? 0 }}</h3>
            <p>Total Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $activeDrivers ?? 0 }}</h3>
            <p>Active Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-clock"></i></div>
        <div class="card-info">
            <h3>{{ $underReviewDrivers ?? 0 }}</h3>
            <p>Drivers Under Review</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-star"></i></div>
        <div class="card-info">
            <h3>{{ number_format($avgPerformance ?? 4.6, 1) }}/5</h3>
            <p>Average Performance Score</p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <form action="{{ route('admin.drivers.index') }}" method="GET" class="filter-bar" style="margin-bottom: 0;">
        <div class="search-box" style="flex: 1; min-width: 250px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" id="searchDriver" value="{{ request('search') }}" placeholder="Search Driver Name, Driver ID, Contact Number..." style="width: 100%;">
        </div>
        <select name="status" id="filterStatus">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Under Review</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <select name="branch" id="filterBranch">
            <option value="">All Branches</option>
            <option value="North Branch" {{ request('branch') == 'North Branch' ? 'selected' : '' }}>North Branch</option>
            <option value="South Branch" {{ request('branch') == 'South Branch' ? 'selected' : '' }}>South Branch</option>
            <option value="East Branch" {{ request('branch') == 'East Branch' ? 'selected' : '' }}>East Branch</option>
            <option value="West Branch" {{ request('branch') == 'West Branch' ? 'selected' : '' }}>West Branch</option>
            <option value="Central Branch" {{ request('branch') == 'Central Branch' ? 'selected' : '' }}>Central Branch</option>
        </select>
        <select name="vehicle_type" id="filterVehicle">
            <option value="">All Vehicle Types</option>
            <option value="Sedan" {{ request('vehicle_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
            <option value="SUV" {{ request('vehicle_type') == 'SUV' ? 'selected' : '' }}>SUV</option>
            <option value="Van" {{ request('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
            <option value="Motorcycle" {{ request('vehicle_type') == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
        </select>
        <select name="rating" id="filterRating">
            <option value="">All Ratings</option>
            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</a>
        <a href="{{ route('admin.drivers.export') }}" class="btn btn-secondary"><i class="fas fa-file-export"></i> Export</a>
    </form>
</div>

<!-- Driver Management Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver Photo</th>
                    <th>Driver ID</th>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Assigned Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Route</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Performance</th>
                    <th>Documents</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr>
                    <td>
                        <a href="{{ route('admin.drivers.profile', $driver->id) }}" title="View {{ $driver->first_name }}'s profile">
                            <img src="{{ $driver->photo ?: asset('drivers/photo/' . $driver->id) }}" alt="{{ $driver->first_name }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;cursor:pointer;">
                        </a>
                    </td>
                    <td><a href="{{ route('admin.drivers.profile', $driver->id) }}" style="color:inherit;text-decoration:none;"><strong>{{ $driver->formatted_id }}</strong></a></td>
                    <td><a href="{{ route('admin.drivers.profile', $driver->id) }}" style="color:inherit;text-decoration:none;"><strong>{{ $driver->full_name }}</strong></a></td>
                    <td>{{ $driver->contact_number }}</td>
                    <td>{{ $driver->vehicle_assignment ?? 'Unassigned' }}</td>
                    <td>{{ $driver->vehicle_type ?? 'N/A' }}</td>
                    <td>{{ $driver->route_assignment ?? 'N/A' }}</td>
                    <td>{{ $driver->branch ?? 'N/A' }}</td>
                    <td>
                        @if($driver->status === 'active')
                            <span class="status-badge status-active">Active</span>
                        @elseif($driver->status === 'review')
                            <span class="status-badge status-review">Under Review</span>
                        @elseif($driver->status === 'suspended')
                            <span class="status-badge status-archived">Suspended</span>
                        @else
                            <span class="status-badge status-pending">{{ ucfirst($driver->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <i class="fas fa-star" style="color:#f59e0b;font-size:0.85rem;"></i>
                            <span>{{ number_format($driver->performance_score, 1) }}</span>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.drivers.profile', $driver->id) }}" class="icon-btn" title="View Documents">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.4rem;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('admin.drivers.profile', $driver->id) }}" class="icon-btn" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="icon-btn" title="Edit Driver" onclick="editDriver({{ json_encode($driver) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="icon-btn" title="Activate/Deactivate" onclick="openStatusModal({{ $driver->id }}, '{{ ucfirst($driver->status) }}')">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button class="icon-btn" title="Archive Driver" onclick="openArchiveModal({{ $driver->id }})">
                                <i class="fas fa-archive"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align:center;padding:2rem;color:var(--text-muted);">
                        <i class="fas fa-info-circle" style="font-size:1.5rem;margin-bottom:0.5rem;"></i>
                        <p>No drivers found matching criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);">
        <div style="font-size:0.85rem;color:var(--text-muted);">
            Showing {{ $drivers->firstItem() ?? 0 }} to {{ $drivers->lastItem() ?? 0 }} of {{ $drivers->total() }} drivers
        </div>
        <div>
            {{ $drivers->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Add New Driver Modal -->
<div class="modal-overlay" id="addDriverModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:800px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Add New Driver</h2>
                <button type="button" onclick="closeModal('addDriverModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div class="modal-tabs" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--border);">
                    <button type="button" class="modal-tab active" onclick="switchTab(this, 'personalTab')">Personal Information</button>
                    <button type="button" class="modal-tab" onclick="switchTab(this, 'employmentTab')">Employment Information</button>
                    <button type="button" class="modal-tab" onclick="switchTab(this, 'accountTab')">Account Information</button>
                </div>
                <!-- Personal Information Tab -->
                <div id="personalTab" class="tab-content">
                    <div style="text-align:center;margin-bottom:1.5rem;">
                        <div style="width:100px;height:100px;border-radius:50%;background:var(--beige);margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;border:2px dashed var(--border);cursor:pointer;" onclick="document.getElementById('driverPhoto').click()">
                            <i class="fas fa-camera" style="font-size:2rem;color:var(--text-muted);"></i>
                        </div>
                        <input type="file" name="photo" id="driverPhoto" accept="image/*" style="display:none;">
                        <p style="font-size:0.85rem;color:var(--text-muted);">Click to upload driver photo</p>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">First Name *</label><input type="text" name="first_name" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Middle Name</label><input type="text" name="middle_name" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Last Name *</label><input type="text" name="last_name" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Birth Date</label><input type="date" name="birth_date" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Gender</label><select name="gender" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="Male">Male</option><option value="Female">Female</option></select></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Civil Status</label><select name="civil_status" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="Single">Single</option><option value="Married">Married</option><option value="Widowed">Widowed</option></select></div>
                        <div style="grid-column:span 2;"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Address</label><input type="text" name="address" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Contact Number</label><input type="tel" name="contact_number" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email Address</label><input type="email" name="email" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Emergency Contact Person</label><input type="text" name="emergency_contact_person" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Emergency Contact Number</label><input type="tel" name="emergency_contact_number" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    </div>
                </div>
                <!-- Employment Information Tab -->
                <div id="employmentTab" class="tab-content" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Date Hired</label><input type="date" name="date_hired" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Branch</label><select name="branch" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="North Branch">North Branch</option><option value="South Branch">South Branch</option><option value="East Branch">East Branch</option><option value="West Branch">West Branch</option><option value="Central Branch">Central Branch</option></select></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Assignment</label><input type="text" name="vehicle_assignment" placeholder="e.g. Toyota Fortuner" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Type</label><select name="vehicle_type" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="Sedan">Sedan</option><option value="SUV">SUV</option><option value="Van">Van</option><option value="Motorcycle">Motorcycle</option></select></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Route Assignment</label><select name="route_assignment" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="North Route">North Route</option><option value="South Route">South Route</option><option value="East Route">East Route</option><option value="West Route">West Route</option><option value="Central Route">Central Route</option></select></div>
                        <div style="grid-column:span 2;"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Employment Status</label><select name="status" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="active">Active</option><option value="inactive">Inactive</option><option value="review">Under Review</option><option value="suspended">Suspended</option></select></div>
                    </div>
                </div>
                <!-- Account Information Tab -->
                <div id="accountTab" class="tab-content" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Username</label><input type="text" name="username" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">User Role</label><select name="role" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="Driver">Driver</option><option value="Senior Driver">Senior Driver</option><option value="Lead Driver">Lead Driver</option></select></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addDriverModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Driver</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Driver Modal -->
<div class="modal-overlay" id="editDriverModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:900px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editDriverForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Driver</h2>
                <button type="button" onclick="closeModal('editDriverModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div class="modal-tabs" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--border);flex-wrap:wrap;">
                    <button type="button" class="modal-tab active" onclick="switchTab(this, 'editPersonalTab')">Personal Information</button>
                    <button type="button" class="modal-tab" onclick="switchTab(this, 'editContactTab')">Contact Information</button>
                    <button type="button" class="modal-tab" onclick="switchTab(this, 'editEmploymentTab')">Employment Details</button>
                </div>
                <div id="editPersonalTab" class="tab-content">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">First Name *</label><input type="text" name="first_name" id="edit_first_name" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Middle Name</label><input type="text" name="middle_name" id="edit_middle_name" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Last Name *</label><input type="text" name="last_name" id="edit_last_name" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Address</label><input type="text" name="address" id="edit_address" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    </div>
                </div>
                <div id="editContactTab" class="tab-content" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Contact Number</label><input type="tel" name="contact_number" id="edit_contact_number" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email Address</label><input type="email" name="email" id="edit_email" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    </div>
                </div>
                <div id="editEmploymentTab" class="tab-content" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Branch</label><input type="text" name="branch" id="edit_branch" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Assignment</label><input type="text" name="vehicle_assignment" id="edit_vehicle_assignment" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Route</label><input type="text" name="route_assignment" id="edit_route_assignment" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                        <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label><select name="status" id="edit_status" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option value="active">Active</option><option value="inactive">Inactive</option><option value="review">Under Review</option><option value="suspended">Suspended</option></select></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editDriverModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Activate/Deactivate Confirmation Modal -->
<div class="modal-overlay" id="statusModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:450px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" id="targetStatusValue" value="active">
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.1rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Confirm Status Change</h2>
                <button type="button" onclick="closeModal('statusModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;text-align:center;">
                <i class="fas fa-exclamation-triangle" style="font-size:3rem;color:var(--warning);margin-bottom:1rem;"></i>
                <p style="font-size:1rem;color:var(--text-dark);margin-bottom:0.5rem;">Are you sure you want to change this driver's account status?</p>
                <p style="font-size:0.85rem;color:var(--text-muted);">This action will immediately affect the driver's access to the system.</p>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:center;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal-overlay" id="archiveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:450px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="archiveForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.1rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Confirm Archive</h2>
                <button type="button" onclick="closeModal('archiveModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;text-align:center;">
                <i class="fas fa-archive" style="font-size:3rem;color:var(--primary);margin-bottom:1rem;"></i>
                <p style="font-size:1rem;color:var(--text-dark);margin-bottom:0.5rem;">Are you sure you want to archive this driver?</p>
                <p style="font-size:0.85rem;color:var(--text-muted);">The driver will be removed from the active list. All historical records will be retained and can be restored later.</p>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:center;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('archiveModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Archive Driver</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" style="position:fixed;bottom:2rem;right:2rem;background:var(--charcoal);color:var(--white);padding:1rem 1.5rem;border-radius:0.75rem;box-shadow:0 8px 24px rgba(0,0,0,0.2);z-index:3000;display:none;align-items:center;gap:0.75rem;min-width:300px;">
    <i class="fas fa-check-circle" style="color:var(--success);font-size:1.25rem;"></i>
    <span id="toastMessage" style="font-size:0.9rem;"></span>
</div>
@endsection

@section('scripts')
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

function showToast(message) {
    const toast = document.getElementById('toast');
    if (toast) {
        document.getElementById('toastMessage').textContent = message;
        toast.style.display = 'flex';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }
}

function editDriver(driver) {
    const form = document.getElementById('editDriverForm');
    form.action = `/admin/drivers/${driver.id}`;
    document.getElementById('edit_first_name').value = driver.first_name || '';
    document.getElementById('edit_middle_name').value = driver.middle_name || '';
    document.getElementById('edit_last_name').value = driver.last_name || '';
    document.getElementById('edit_address').value = driver.address || '';
    document.getElementById('edit_contact_number').value = driver.contact_number || '';
    document.getElementById('edit_email').value = driver.email || '';
    document.getElementById('edit_branch').value = driver.branch || '';
    document.getElementById('edit_vehicle_assignment').value = driver.vehicle_assignment || '';
    document.getElementById('edit_route_assignment').value = driver.route_assignment || '';
    document.getElementById('edit_status').value = driver.status || 'active';
    openModal('editDriverModal');
}

function openStatusModal(id, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `/admin/drivers/${id}/status`;
    const targetValue = currentStatus.toLowerCase() === 'active' ? 'inactive' : 'active';
    document.getElementById('targetStatusValue').value = targetValue;
    openModal('statusModal');
}

function openArchiveModal(id) {
    const form = document.getElementById('archiveForm');
    form.action = `/admin/drivers/${id}`;
    openModal('archiveModal');
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});
</script>
@endsection
