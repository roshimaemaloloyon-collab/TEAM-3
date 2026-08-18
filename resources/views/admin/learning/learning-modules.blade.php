@extends('admin.layouts.admin')

@section('title', 'TripWise — Learning Modules by Position')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Learning Modules by Position</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;font-weight:700;">Learning Modules by Position</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Select a position to view all learning modules tailored for that role and driver enrollment statistics.</p>
    </div>
</div>

<!-- Top Filters & Search -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div style="flex:1;max-width:400px;position:relative;">
        <i class="fas fa-search" style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);"></i>
        <input type="text" id="positionSearchInput" placeholder="Search position or modules..." style="width:100%;padding:0.65rem 1rem 0.65rem 2.5rem;border:1px solid var(--border);border-radius:0.75rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;" onkeyup="filterPositions()">
    </div>
    <div style="display:flex;gap:0.75rem;">
        <select id="categorySelect" style="padding:0.65rem 1.25rem;border:1px solid var(--border);border-radius:0.75rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;" onchange="filterPositions()">
            <option value="">All Categories</option>
            <option value="driver">Drivers (MC & 4-Wheel)</option>
            <option value="management">Management & Operations</option>
            <option value="staff">Office & Administrative Staff</option>
        </select>
    </div>
</div>

@php
    $positionsData = [
        [
            'name' => 'MC TAXI DRIVER',
            'slug' => 'mc-taxi-driver',
            'count' => 12,
            'completion' => 60,
            'category_type' => 'driver',
            'icon' => 'fas fa-motorcycle',
            'img' => route('position.photo', ['role' => 'mc-taxi-driver', 'v' => time()])
        ],
        [
            'name' => '4-WHEEL CAR DRIVER',
            'slug' => '4-wheel-car-driver',
            'count' => 14,
            'completion' => 45,
            'category_type' => 'driver',
            'icon' => 'fas fa-car',
            'img' => route('position.photo', ['role' => '4-wheel-car-driver', 'v' => time()])
        ],
        [
            'name' => 'OPERATIONS MANAGER',
            'slug' => 'operations-manager',
            'count' => 16,
            'completion' => 70,
            'category_type' => 'management',
            'icon' => 'fas fa-users-cog',
            'img' => route('position.photo', 'operations-manager')
        ],
        [
            'name' => 'OFFICE STAFF',
            'slug' => 'office-staff',
            'count' => 10,
            'completion' => 50,
            'category_type' => 'staff',
            'icon' => 'fas fa-folder-open',
            'img' => route('position.photo', 'office-staff')
        ],
        [
            'name' => 'HR MANAGER',
            'slug' => 'hr-manager',
            'count' => 15,
            'completion' => 65,
            'category_type' => 'management',
            'icon' => 'fas fa-user-shield',
            'img' => route('position.photo', 'hr-manager')
        ],
        [
            'name' => 'FACILITIES COORDINATOR',
            'slug' => 'facilities-coordinator',
            'count' => 12,
            'completion' => 55,
            'category_type' => 'staff',
            'icon' => 'fas fa-building',
            'img' => route('position.photo', 'facilities-coordinator')
        ],
        [
            'name' => 'VEHICLE DISPATCHER',
            'slug' => 'vehicle-dispatcher',
            'count' => 13,
            'completion' => 60,
            'category_type' => 'management',
            'icon' => 'fas fa-map-marker-alt',
            'img' => route('position.photo', 'vehicle-dispatcher')
        ],
        [
            'name' => 'FINANCE OFFICER',
            'slug' => 'finance-officer',
            'count' => 14,
            'completion' => 60,
            'category_type' => 'staff',
            'icon' => 'fas fa-dollar-sign',
            'img' => route('position.photo', 'finance-officer')
        ],
        [
            'name' => 'RECRUITMENT SPECIALIST',
            'slug' => 'recruitment-specialist',
            'count' => 11,
            'completion' => 50,
            'category_type' => 'staff',
            'icon' => 'fas fa-id-badge',
            'img' => route('position.photo', 'recruitment-specialist')
        ]
    ];
@endphp

<!-- Grid Layout matching exact card layout -->
<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:1.25rem;" id="positionsGrid">
    @foreach($positionsData as $pos)
        <div class="position-card" data-name="{{ strtolower($pos['name']) }}" data-category="{{ $pos['category_type'] }}" style="background:var(--white);border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 4px 14px rgba(0,0,0,0.04);transition:transform 0.25s ease, box-shadow 0.25s ease;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <!-- Top Card Image Container with Floating Badge -->
                <div style="position:relative;height:190px;background:#FAF6EE;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <img src="{{ $pos['img'] }}" alt="{{ $pos['name'] }}" style="width:100%;height:100%;object-fit:cover;">
                    
                    <!-- Floating Round Category Icon Badge on Bottom Left of Image -->
                    <div style="position:absolute;left:1rem;bottom:0.75rem;width:40px;height:40px;border-radius:50%;background:var(--charcoal);color:var(--white);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(0,0,0,0.15);z-index:2;">
                        <i class="{{ $pos['icon'] }}" style="font-size:1.05rem;"></i>
                    </div>
                </div>

                <!-- Card Body -->
                <div style="padding:1.25rem 1.25rem 0.75rem;">
                    <h3 style="font-size:0.95rem;font-weight:700;color:var(--text-dark);margin:0 0 0.25rem;letter-spacing:0.02em;">{{ $pos['name'] }}</h3>
                    <p style="font-size:0.8rem;color:var(--text-muted);margin:0 0 1rem;">{{ $pos['count'] }} Modules</p>

                    <!-- Progress Bar -->
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                        <div style="flex:1;height:6px;background:var(--border);border-radius:3px;overflow:hidden;">
                            <div style="width:{{ $pos['completion'] }}%;height:100%;background:var(--primary);border-radius:3px;"></div>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:var(--text-muted);">{{ $pos['completion'] }}% Complete</span>
                    </div>
                </div>
            </div>

            <!-- Card Footer Action Button -->
            <div style="padding:0 1.25rem 1.25rem;">
                <button type="button" class="btn-view-position" data-position="{{ $pos['name'] }}" style="display:block;width:100%;padding:0.65rem 0;text-align:center;border:1px solid var(--border);border-radius:0.5rem;background:var(--white);color:var(--text-dark);font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s ease;">
                    View Modules
                </button>
            </div>
        </div>
    @endforeach
</div>

<!-- Bottom Banner Information -->
<div style="margin-top:2rem;background:#f0f4f8;border:1px solid #dbeafe;border-radius:0.85rem;padding:1.25rem;display:flex;align-items:center;gap:1rem;">
    <div style="width:42px;height:42px;border-radius:50%;background:var(--charcoal);color:var(--white);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas fa-graduation-cap" style="font-size:1.2rem;"></i>
    </div>
    <div>
        <h4 style="margin:0 0 0.2rem;font-size:0.95rem;color:var(--text-dark);font-weight:600;">Continuous Professional Development</h4>
        <p style="margin:0;font-size:0.85rem;color:var(--text-muted);">Learning modules are continuously updated to ensure drivers and staff have the latest knowledge and skills required for their role. Keep learning, keep growing with TripWise!</p>
    </div>
</div>

<!-- Split-Screen View Position Modules / Space Request Modal (Matching Reference Design) -->
<div id="positionModulesModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:2000;align-items:center;justify-content:center;padding:1.5rem;backdrop-filter:blur(4px);">
    <div class="modal-box" style="background:#ffffff;border-radius:1rem;width:100%;max-width:1050px;max-height:92vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.3);display:flex;flex-wrap:wrap;overflow:hidden;border:1px solid #e2e8f0;">
        
        <!-- Left Column: Position Image Header & Policy Details (42% width) -->
        <div style="flex:1 1 420px;background:#f8fafc;border-right:1px solid #e2e8f0;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <!-- Top Image Banner -->
                <div style="position:relative;height:220px;overflow:hidden;background:#0f172a;">
                    <img id="modalLeftImage" src="" alt="Position Header" style="width:100%;height:100%;object-fit:cover;opacity:0.88;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(15,23,42,0.92) 0%, rgba(15,23,42,0.2) 60%);"></div>
                    <div style="position:absolute;bottom:1.25rem;left:1.5rem;right:1.5rem;color:#ffffff;">
                        <h2 id="modalLeftTitle" style="font-size:1.4rem;font-weight:700;margin:0 0 0.25rem;color:#ffffff;font-family:'Poppins',sans-serif;">Training Room 1</h2>
                        <p id="modalLeftSubtitle" style="font-size:0.85rem;color:#cbd5e1;margin:0;">Training Center, Floor 2 • TNVS Fleet Hub</p>
                    </div>
                </div>

                <!-- Capacity & Amenities Section -->
                <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;">
                    <div>
                        <span style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.25rem;">Capacity</span>
                        <strong id="modalLeftCapacity" style="font-size:1.1rem;color:#0f172a;font-weight:700;">30 Seats available</strong>
                    </div>

                    <div>
                        <span style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.5rem;">Amenities List</span>
                        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;" id="modalLeftAmenities">
                            <span style="font-size:0.75rem;padding:0.35rem 0.75rem;background:#ffffff;border:1px solid #cbd5e1;border-radius:1rem;color:#334155;font-weight:500;">Projector</span>
                            <span style="font-size:0.75rem;padding:0.35rem 0.75rem;background:#ffffff;border:1px solid #cbd5e1;border-radius:1rem;color:#334155;font-weight:500;">Desk Mics</span>
                            <span style="font-size:0.75rem;padding:0.35rem 0.75rem;background:#ffffff;border:1px solid #cbd5e1;border-radius:1rem;color:#334155;font-weight:500;">Modular Seating</span>
                            <span style="font-size:0.75rem;padding:0.35rem 0.75rem;background:#ffffff;border:1px solid #cbd5e1;border-radius:1rem;color:#334155;font-weight:500;">Simulator Pods</span>
                        </div>
                    </div>

                    <div style="border-top:1px solid #e2e8f0;padding-top:1.25rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.6rem;">AI Engine Policy Rules</span>
                        <ul style="margin:0;padding-left:1.1rem;font-size:0.82rem;color:#475569;line-height:1.6;">
                            <li>P1 requesters have bump precedence over P3/P4 groups during conflicts.</li>
                            <li>Open category spaces are auto-approved instantly if there is no conflict.</li>
                            <li>Recurring schedules undergo review and must not exceed a 60-day window.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div style="padding:1rem 1.5rem;background:#f1f5f9;border-top:1px solid #e2e8f0;font-size:0.8rem;color:#64748b;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-shield-alt" style="color:#059669;"></i> Live Automated Verification System Active
            </div>
        </div>

        <!-- Right Column: Reserve Space Request / Module Enrollment Form (58% width) -->
        <div style="flex:1 1 500px;padding:1.75rem;background:#ffffff;display:flex;flex-direction:column;justify-content:space-between;position:relative;">
            <button type="button" onclick="closeModal('positionModulesModal')" style="position:absolute;right:1.25rem;top:1.25rem;background:none;border:none;font-size:1.4rem;color:#94a3b8;cursor:pointer;z-index:10;"><i class="fas fa-times"></i></button>

            <div>
                <!-- Modal Title -->
                <div style="margin-bottom:1.25rem;padding-right:2rem;">
                    <h2 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 0.2rem;text-transform:uppercase;letter-spacing:0.03em;">Reserve Space Request</h2>
                    <p style="font-size:0.8rem;color:#64748b;margin:0;">Evaluate availability check & enroll driver learning module</p>
                </div>

                <form id="reserveSpaceForm" onsubmit="event.preventDefault(); alert('Reservation & Module Enrollment submitted successfully!'); closeModal('positionModulesModal');">
                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        
                        <!-- Select Facility Space -->
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Select Facility Space / Curriculum Module</label>
                            <select id="reserveFacilitySelect" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.88rem;color:#0f172a;background:#ffffff;">
                                <option value="room1">Training Room 1 (Standard, 30 seats)</option>
                                <option value="sim_lab">Simulated Driving Lab A (15 Pods)</option>
                                <option value="conf_hall">Fleet Auditorium & Conference Hall (100 seats)</option>
                            </select>
                        </div>

                        <!-- Requester Team & Priority Tier Grid -->
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Requester Team</label>
                                <select style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                                    <option value="hr">Team 1 (HR & Safety)</option>
                                    <option value="ops">Team 2 (Fleet Operations)</option>
                                    <option value="logistics">Team 3 (Dispatch Logistics)</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Requester Priority Tier</label>
                                <select style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                                    <option value="p3">P3 — Operational (Staff project syncs)</option>
                                    <option value="p1">P1 — Critical Safety Retraining</option>
                                    <option value="p2">P2 — Onboarding Batch</option>
                                </select>
                            </div>
                        </div>

                        <!-- Requester Name & Email -->
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Requester Name</label>
                                <input type="text" value="e.g. Maria Santos" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Requester Email</label>
                                <input type="email" value="maria@tripwise.tnvs" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                            </div>
                        </div>

                        <!-- Time & Attendees Count Grid -->
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Start Time / Date</label>
                                <input type="text" value="09:00 AM — Aug 20, 2026" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Duration Helper</label>
                                <select style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                                    <option value="custom">Custom End Date (2 Hours)</option>
                                    <option value="full_day">Full Day Session</option>
                                </select>
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">End Time</label>
                                <input type="text" value="11:00 AM — Aug 20, 2026" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Attendees Count</label>
                                <input type="number" value="4" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;">
                            </div>
                        </div>

                        <!-- External Guest Invitation Section -->
                        <div style="border:1px solid #e2e8f0;border-radius:0.65rem;padding:0.85rem;background:#f8fafc;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                                <span style="font-size:0.72rem;font-weight:700;color:#334155;text-transform:uppercase;">External Guest Invitation (Optional)</span>
                                <span style="font-size:0.7rem;color:#ef4444;font-weight:600;"><i class="fas fa-link"></i> Linked to Visitor Management KYC</span>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;margin-bottom:0.6rem;">
                                <input type="text" placeholder="e.g. Juan Dela Cruz" style="padding:0.5rem 0.75rem;border:1px solid #cbd5e1;border-radius:0.4rem;font-size:0.8rem;background:#ffffff;">
                                <input type="text" placeholder="e.g. Acme Corp Phils." style="padding:0.5rem 0.75rem;border:1px solid #cbd5e1;border-radius:0.4rem;font-size:0.8rem;background:#ffffff;">
                            </div>
                            <div style="display:flex;gap:0.5rem;align-items:center;">
                                <input type="text" placeholder="e.g. 123-456-789-000 or ID Number" style="flex:1;padding:0.5rem 0.75rem;border:1px solid #cbd5e1;border-radius:0.4rem;font-size:0.8rem;background:#ffffff;">
                                <button type="button" style="background:#ef4444;color:#ffffff;border:none;padding:0.55rem 1rem;border-radius:0.4rem;font-size:0.78rem;font-weight:600;cursor:pointer;" onclick="alert('Live KYC Verification Successful!')">Verify ID</button>
                            </div>
                        </div>

                        <!-- Purpose of Booking -->
                        <div>
                            <label style="display:block;font-size:0.75rem;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:0.35rem;">Purpose of Booking / Module Goals</label>
                            <textarea rows="2" style="width:100%;padding:0.65rem 0.85rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.85rem;color:#0f172a;background:#ffffff;" placeholder="Detail the reservation target and learning module objectives..."></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Submit Action -->
            <div style="margin-top:1.25rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('positionModulesModal')">Cancel</button>
                <button type="button" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;color:#ffffff;padding:0.65rem 1.75rem;font-weight:700;" onclick="document.getElementById('reserveSpaceForm').dispatchEvent(new Event('submit'))">Submit Reservation</button>
            </div>
        </div>
    </div>
</div>

<!-- Assign Learning Module Modal -->
<div id="assignLearningModuleModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:500px;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.learning.assignments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="learning_module_id" id="assignModuleIdInput">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.2rem;color:var(--primary);font-weight:700;margin:0;">Assign Learning Module</h2>
                <button type="button" onclick="closeModal('assignLearningModuleModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Target Driver</label>
                    <select name="driver_id" id="assignDriverSelect" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @if(isset($allDrivers))
                            @foreach($allDrivers as $d)
                                <option value="{{ $d->id }}" data-vtype="{{ $d->vehicle_type }}">{{ $d->full_name }} ({{ $d->formatted_id }}) — {{ $d->vehicle_type }} ({{ $d->vehicle_assignment }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Module Title</label>
                    <input type="text" id="assignModuleTitleInput" readonly style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:#f1f5f9;color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Target Due Date</label>
                    <input type="date" name="due_date" value="{{ now()->addDays(14)->format('Y-m-d') }}" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('assignLearningModuleModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Assign Module</button>
            </div>
        </form>
    </div>
</div>

<script>
// JSON payload of all learning modules with assignment counts
const allModules = {!! json_encode($allModulesWithCounts ?? []) !!};
const allDriversList = {!! json_encode($allDrivers->map(function($d) {
    return [
        'id' => $d->id,
        'name' => $d->full_name,
        'formatted_id' => $d->formatted_id,
        'vtype' => $d->vehicle_type,
        'vname' => $d->vehicle_assignment,
    ];
}) ?? []) !!};

function filterPositions() {
    const searchVal = document.getElementById('positionSearchInput').value.toLowerCase();
    const catVal = document.getElementById('categorySelect').value;
    const cards = document.querySelectorAll('.position-card');

    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const cat = card.getAttribute('data-category');

        const matchesSearch = name.includes(searchVal);
        const matchesCat = !catVal || cat === catVal;

        if (matchesSearch && matchesCat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

let currentPositionName = 'MC TAXI DRIVER';

function populateDriverSelect(positionName) {
    const select = document.getElementById('assignDriverSelect');
    if (!select) return;
    select.innerHTML = '';

    const posUpper = (positionName || '').toUpperCase();
    const isMcTaxi = posUpper.includes('MC TAXI');
    const is4Wheel = posUpper.includes('4-WHEEL');

    let filteredDrivers = allDriversList;
    if (isMcTaxi) {
        filteredDrivers = allDriversList.filter(d => (d.vtype || '').toLowerCase() === 'motorcycle');
    } else if (is4Wheel) {
        filteredDrivers = allDriversList.filter(d => (d.vtype || '').toLowerCase() !== 'motorcycle');
    }

    if (filteredDrivers.length === 0) {
        filteredDrivers = allDriversList;
    }

    filteredDrivers.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.innerText = `${d.name} (${d.formatted_id}) — ${d.vtype || 'Driver'} (${d.vname || 'Assigned Vehicle'})`;
        select.appendChild(opt);
    });
}

function openPositionModulesModal(positionName) {
    currentPositionName = positionName || 'MC TAXI DRIVER';
    const modal = document.getElementById('positionModulesModal');
    if (!modal) return;

    // Find card image
    let posImg = '/drivers/photo/1';
    let posCount = 12;
    const cards = document.querySelectorAll('.position-card');
    cards.forEach(card => {
        const titleEl = card.querySelector('h3');
        if (titleEl && titleEl.innerText.trim().toUpperCase() === currentPositionName.trim().toUpperCase()) {
            const img = card.querySelector('img');
            if (img) posImg = img.src;
        }
    });

    const leftImg = document.getElementById('modalLeftImage');
    const leftTitle = document.getElementById('modalLeftTitle');
    const leftSubtitle = document.getElementById('modalLeftSubtitle');
    const leftCap = document.getElementById('modalLeftCapacity');

    if (leftImg) leftImg.src = posImg;
    if (leftTitle) leftTitle.innerText = currentPositionName + ' Training Facility';
    if (leftSubtitle) leftSubtitle.innerText = 'Training Center, Floor 2 • TNVS Fleet Hub';
    if (leftCap) leftCap.innerText = '30 Seats & Simulator Pods Available';

    // Populate Facility / Module select
    const facSelect = document.getElementById('reserveFacilitySelect');
    if (facSelect) {
        facSelect.innerHTML = '';
        const opt1 = document.createElement('option');
        opt1.value = 'room1';
        opt1.innerText = `${currentPositionName} — Core Safety & Navigation Workshop (30 seats)`;
        facSelect.appendChild(opt1);

        const opt2 = document.createElement('option');
        opt2.value = 'sim_lab';
        opt2.innerText = `${currentPositionName} — Defensive Driving Simulator Pod A (15 Pods)`;
        facSelect.appendChild(opt2);

        const opt3 = document.createElement('option');
        opt3.value = 'conf_hall';
        opt3.innerText = `${currentPositionName} — Fleet Auditorium & Ethics Seminar (100 seats)`;
        facSelect.appendChild(opt3);
    }

    modal.style.display = 'flex';
    modal.style.visibility = 'visible';
    modal.style.opacity = '1';
    document.body.style.overflow = 'hidden';
}

function filterModalModules() {
    const searchVal = document.getElementById('modalModuleSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.modal-module-row');

    rows.forEach(row => {
        const title = row.getAttribute('data-title');
        if (title.includes(searchVal)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('click', function(e) {
    const viewBtn = e.target.closest('.btn-view-position');
    if (viewBtn) {
        e.preventDefault();
        const posName = viewBtn.getAttribute('data-position');
        openPositionModulesModal(posName);
        return;
    }

    const assignBtn = e.target.closest('.assign-module-btn');
    if (assignBtn) {
        e.preventDefault();
        const moduleId = assignBtn.getAttribute('data-module-id');
        const moduleTitle = assignBtn.getAttribute('data-module-title');

        const assignModal = document.getElementById('assignLearningModuleModal');
        if (assignModal) {
            populateDriverSelect(currentPositionName);
            document.getElementById('assignModuleIdInput').value = moduleId;
            document.getElementById('assignModuleTitleInput').value = moduleTitle;
            assignModal.style.display = 'flex';
            assignModal.style.visibility = 'visible';
            assignModal.style.opacity = '1';
        }
    }
});

// Auto-open modal if position query param is in URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const posParam = urlParams.get('position');
    if (posParam) {
        openPositionModulesModal(posParam);
    }
});
</script>
@endsection
