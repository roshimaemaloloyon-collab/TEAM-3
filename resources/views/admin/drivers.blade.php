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
            <h3>248</h3>
            <p>Total Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>231</h3>
            <p>Active Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-clock"></i></div>
        <div class="card-info">
            <h3>12</h3>
            <p>Drivers Under Review</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-star"></i></div>
        <div class="card-info">
            <h3>4.6/5</h3>
            <p>Average Performance Score</p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <div class="filter-bar" style="margin-bottom: 0;">
        <div class="search-box" style="flex: 1; min-width: 250px;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchDriver" placeholder="Search Driver Name, Driver ID, Contact Number..." style="width: 100%;">
        </div>
        <select id="filterStatus">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="review">Under Review</option>
            <option value="suspended">Suspended</option>
            <option value="archived">Archived</option>
        </select>
        <select id="filterBranch">
            <option value="">All Branches</option>
            <option value="north">North Branch</option>
            <option value="south">South Branch</option>
            <option value="east">East Branch</option>
            <option value="west">West Branch</option>
        </select>
        <select id="filterVehicle">
            <option value="">All Vehicle Types</option>
            <option value="sedan">Sedan</option>
            <option value="suv">SUV</option>
            <option value="van">Van</option>
            <option value="motorcycle">Motorcycle</option>
        </select>
        <select id="filterRating">
            <option value="">All Ratings</option>
            <option value="5">5 Stars</option>
            <option value="4">4+ Stars</option>
            <option value="3">3+ Stars</option>
        </select>
        <select id="filterDate">
            <option value="">All Dates</option>
            <option value="2026">2026</option>
            <option value="2025">2025</option>
        </select>
        <button class="btn btn-primary" onclick="applyFilters()"><i class="fas fa-search"></i> Search</button>
        <button class="btn btn-secondary" onclick="resetFilters()"><i class="fas fa-undo"></i> Reset</button>
        <button class="btn btn-secondary" onclick="exportDrivers()"><i class="fas fa-file-export"></i> Export</button>
    </div>
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
                    <th>Route</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Performance</th>
                    <th>Documents</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&h=80&q=80" alt="Juan" style="width:40px;height:40px;border-radius:50%;object-fit:cover;"></td>
                    <td><strong>#DRV-2026-0001</strong></td>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td>+63 912 345 6789</td>
                    <td>Toyota Fortuner</td>
                    <td>North Route</td>
                    <td>North Branch</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td><div style="display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.85rem;"></i> 4.9</div></td>
                    <td><button class="icon-btn" title="Documents" onclick="showToast('Documents viewer coming soon')"><i class="fas fa-file-alt"></i></button></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.4rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', 1) }}" class="icon-btn" title="View Profile"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Edit Driver" onclick="editDriver(1)"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Activate/Deactivate" onclick="openStatusModal(1, 'Active')"><i class="fas fa-power-off"></i></button>
                            <button class="icon-btn" title="Archive Driver" onclick="openArchiveModal(1)"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=80&h=80&q=80" alt="Maria" style="width:40px;height:40px;border-radius:50%;object-fit:cover;"></td>
                    <td><strong>#DRV-2026-0002</strong></td>
                    <td><strong>Maria Santos</strong></td>
                    <td>+63 917 234 5678</td>
                    <td>Honda Civic</td>
                    <td>South Route</td>
                    <td>South Branch</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td><div style="display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.85rem;"></i> 4.8</div></td>
                    <td><button class="icon-btn" title="Documents" onclick="showToast('Documents viewer coming soon')"><i class="fas fa-file-alt"></i></button></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.4rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', 2) }}" class="icon-btn" title="View Profile"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Edit Driver" onclick="editDriver(2)"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Activate/Deactivate" onclick="openStatusModal(2, 'Active')"><i class="fas fa-power-off"></i></button>
                            <button class="icon-btn" title="Archive Driver" onclick="openArchiveModal(2)"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=80&h=80&q=80" alt="Pedro" style="width:40px;height:40px;border-radius:50%;object-fit:cover;"></td>
                    <td><strong>#DRV-2026-0003</strong></td>
                    <td><strong>Pedro Reyes</strong></td>
                    <td>+63 915 456 7890</td>
                    <td>Mitsubishi Montero</td>
                    <td>East Route</td>
                    <td>East Branch</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td><div style="display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.85rem;"></i> 4.7</div></td>
                    <td><button class="icon-btn" title="Documents" onclick="showToast('Documents viewer coming soon')"><i class="fas fa-file-alt"></i></button></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.4rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', 3) }}" class="icon-btn" title="View Profile"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Edit Driver" onclick="editDriver(3)"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Activate/Deactivate" onclick="openStatusModal(3, 'Active')"><i class="fas fa-power-off"></i></button>
                            <button class="icon-btn" title="Archive Driver" onclick="openArchiveModal(3)"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=80&h=80&q=80" alt="Ana" style="width:40px;height:40px;border-radius:50%;object-fit:cover;"></td>
                    <td><strong>#DRV-2026-0004</strong></td>
                    <td><strong>Ana Lim</strong></td>
                    <td>+63 918 567 8901</td>
                    <td>Hyundai Tucson</td>
                    <td>West Route</td>
                    <td>West Branch</td>
                    <td><span class="status-badge status-review">Under Review</span></td>
                    <td><div style="display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.85rem;"></i> 4.6</div></td>
                    <td><button class="icon-btn" title="Documents" onclick="showToast('Documents viewer coming soon')"><i class="fas fa-file-alt"></i></button></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.4rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', 4) }}" class="icon-btn" title="View Profile"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Edit Driver" onclick="editDriver(4)"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Activate/Deactivate" onclick="openStatusModal(4, 'Under Review')"><i class="fas fa-power-off"></i></button>
                            <button class="icon-btn" title="Archive Driver" onclick="openArchiveModal(4)"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&h=80&q=80" alt="Rosa" style="width:40px;height:40px;border-radius:50%;object-fit:cover;"></td>
                    <td><strong>#DRV-2026-0005</strong></td>
                    <td><strong>Rosa Garcia</strong></td>
                    <td>+63 919 678 9012</td>
                    <td>Nissan Terra</td>
                    <td>Central Route</td>
                    <td>Central Branch</td>
                    <td><span class="status-badge status-pending">Inactive</span></td>
                    <td><div style="display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.85rem;"></i> 3.2</div></td>
                    <td><button class="icon-btn" title="Documents" onclick="showToast('Documents viewer coming soon')"><i class="fas fa-file-alt"></i></button></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.4rem;justify-content:center;">
                            <a href="{{ route('admin.drivers.profile', 5) }}" class="icon-btn" title="View Profile"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Edit Driver" onclick="editDriver(5)"><i class="fas fa-edit"></i></button>
                            <button class="icon-btn" title="Activate/Deactivate" onclick="openStatusModal(5, 'Inactive')"><i class="fas fa-power-off"></i></button>
                            <button class="icon-btn" title="Archive Driver" onclick="openArchiveModal(5)"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:var(--text-muted);">
            <span>Rows per page:</span>
            <select style="padding:0.4rem 0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
        </div>
        <div style="display:flex;gap:0.4rem;align-items:center;">
            <button class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.85rem;">Previous</button>
            <button class="btn btn-primary" style="padding:0.4rem 0.8rem;font-size:0.85rem;min-width:36px;">1</button>
            <button class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.85rem;min-width:36px;">2</button>
            <button class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.85rem;min-width:36px;">3</button>
            <button class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.85rem;">Next</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Add New Driver Modal -->
<div class="modal-overlay" id="addDriverModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:800px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Add New Driver</h2>
            <button onclick="closeModal('addDriverModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <!-- Tabs -->
            <div class="modal-tabs" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--border);">
                <button class="modal-tab active" onclick="switchTab(this, 'personalTab')">Personal Information</button>
                <button class="modal-tab" onclick="switchTab(this, 'employmentTab')">Employment Information</button>
                <button class="modal-tab" onclick="switchTab(this, 'accountTab')">Account Information</button>
            </div>
            <!-- Personal Information Tab -->
            <div id="personalTab" class="tab-content">
                <div style="text-align:center;margin-bottom:1.5rem;">
                    <div style="width:100px;height:100px;border-radius:50%;background:var(--beige);margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;border:2px dashed var(--border);cursor:pointer;" onclick="document.getElementById('driverPhoto').click()">
                        <i class="fas fa-camera" style="font-size:2rem;color:var(--text-muted);"></i>
                    </div>
                    <input type="file" id="driverPhoto" accept="image/*" style="display:none;">
                    <p style="font-size:0.85rem;color:var(--text-muted);">Click to upload driver photo</p>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">First Name</label><input type="text" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Middle Name</label><input type="text" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Last Name</label><input type="text" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Birth Date</label><input type="date" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Gender</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Male</option><option>Female</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Civil Status</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Single</option><option>Married</option><option>Widowed</option></select></div>
                    <div style="grid-column:span 2;"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Address</label><input type="text" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Contact Number</label><input type="tel" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email Address</label><input type="email" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Emergency Contact Person</label><input type="text" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Emergency Contact Number</label><input type="tel" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                </div>
            </div>
            <!-- Employment Information Tab -->
            <div id="employmentTab" class="tab-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver ID</label><input type="text" value="DRV-2026-0006" readonly style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--beige);"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Date Hired</label><input type="date" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Branch</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>North Branch</option><option>South Branch</option><option>East Branch</option><option>West Branch</option><option>Central Branch</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Assignment</label><input type="text" placeholder="e.g. Toyota Fortuner" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Vehicle Type</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Sedan</option><option>SUV</option><option>Van</option><option>Motorcycle</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Route Assignment</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>North Route</option><option>South Route</option><option>East Route</option><option>West Route</option><option>Central Route</option></select></div>
                    <div style="grid-column:span 2;"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Employment Status</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Active</option><option>Inactive</option><option>Under Review</option><option>Suspended</option></select></div>
                </div>
            </div>
            <!-- Account Information Tab -->
            <div id="accountTab" class="tab-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Username</label><input type="text" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">User Role</label><select style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"><option>Driver</option><option>Senior Driver</option><option>Lead Driver</option></select></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Password</label><input type="password" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                    <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Confirm Password</label><input type="password" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addDriverModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveDriver()">Save Driver</button>
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
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function editDriver(id) {
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

function applyFilters() {
    showToast('Filters applied.');
}

function resetFilters() {
    document.getElementById('searchDriver').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterBranch').value = '';
    document.getElementById('filterVehicle').value = '';
    document.getElementById('filterRating').value = '';
    document.getElementById('filterDate').value = '';
    showToast('Filters reset.');
}

function exportDrivers() {
    showToast('Exporting drivers to Excel...');
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

const style = document.createElement('style');
style.textContent = `
    .modal-tab {
        padding: 0.6rem 1rem;
        border: none;
        background: none;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
    }
    .modal-tab:hover {
        color: var(--primary);
    }
    .modal-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
`;
document.head.appendChild(style);
</script>
@endsection
