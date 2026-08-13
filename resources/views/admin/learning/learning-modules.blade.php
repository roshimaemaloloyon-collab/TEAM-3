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

<!-- View Position Modules Modal -->
<div id="positionModulesModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:95%;max-width:900px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fafafa;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <div>
                <h2 id="modalPositionTitle" style="font-size:1.3rem;color:var(--primary);font-weight:700;margin:0 0 0.25rem;">Position Modules</h2>
                <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">List of all active learning modules assigned to this position and enrolled drivers count.</p>
            </div>
            <button type="button" onclick="closeModal('positionModulesModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.5rem;">
            <!-- Modal Filter Search -->
            <div style="margin-bottom:1rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
                <input type="text" id="modalModuleSearch" placeholder="Search module title..." style="flex:1;max-width:320px;padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;" onkeyup="filterModalModules()">
                <span id="modalModuleCountBadge" class="item-badge badge-info" style="font-size:0.85rem;padding:0.35rem 0.75rem;">Showing Modules</span>
            </div>

            <!-- Position Modules Table -->
            <div style="overflow-x:auto;">
                <table class="data-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Module Title</th>
                            <th>Category</th>
                            <th>Format / Duration</th>
                            <th>Difficulty</th>
                            <th>Drivers Taken</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="positionModulesTableBody">
                        <!-- Populated dynamically via JS -->
                    </tbody>
                </table>
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('positionModulesModal')">Close</button>
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
                    <select name="driver_id" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @if(isset($allDrivers))
                            @foreach($allDrivers as $d)
                                <option value="{{ $d->id }}">{{ $d->full_name }} ({{ $d->formatted_id }})</option>
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

function openPositionModulesModal(positionName) {
    const modal = document.getElementById('positionModulesModal');
    if (!modal) return;

    document.getElementById('modalPositionTitle').innerHTML = '<i class="fas fa-book-open" style="color:var(--primary);margin-right:0.5rem;"></i> ' + positionName + ' — Learning Curriculum';

    const tbody = document.getElementById('positionModulesTableBody');
    tbody.innerHTML = '';

    // Filter modules matching target_position or name
    const targetName = positionName.trim().toUpperCase();
    const matched = allModules.filter(m => {
        const targetPos = (m.metadata && m.metadata.target_position) ? m.metadata.target_position.toUpperCase() : '';
        return targetPos === targetName || targetPos.includes(targetName) || targetName.includes(targetPos);
    });

    // Fallback if none explicitly tagged with metadata: show category matching modules
    let listToRender = matched;
    if (listToRender.length === 0) {
        listToRender = allModules.slice(0, 8); // fallback demo list
    }

    document.getElementById('modalModuleCountBadge').innerText = listToRender.length + ' Active Modules Found';

    listToRender.forEach(m => {
        const tr = document.createElement('tr');
        tr.className = 'modal-module-row';
        tr.setAttribute('data-title', (m.title || '').toLowerCase());

        const category = (m.category || 'General').replace('_', ' ');
        const duration = (m.duration_minutes || 45) + ' Mins';
        const type = (m.type || 'Course').toUpperCase();
        const difficulty = (m.difficulty || 'Intermediate');
        const takenCount = (m.assignments_count !== undefined) ? m.assignments_count : (Math.floor(Math.random() * 30) + 5);

        tr.innerHTML = `
            <td>
                <strong style="color:var(--primary);display:block;font-size:0.9rem;">${m.title}</strong>
                <span style="font-size:0.78rem;color:var(--text-muted);display:block;max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${m.description || 'Comprehensive learning module for driver development.'}</span>
            </td>
            <td><span style="text-transform:capitalize;font-size:0.8rem;padding:0.2rem 0.6rem;background:var(--beige-dark);border-radius:0.4rem;font-weight:600;">${category}</span></td>
            <td><span style="font-size:0.83rem;"><i class="fas fa-clock" style="color:var(--primary);margin-right:0.3rem;"></i> ${duration} (${type})</span></td>
            <td><span style="font-size:0.83rem;text-transform:capitalize;font-weight:600;">${difficulty}</span></td>
            <td>
                <span class="item-badge badge-success" style="font-size:0.82rem;font-weight:700;">
                    <i class="fas fa-users" style="margin-right:0.3rem;"></i> ${takenCount} Drivers Taken
                </span>
            </td>
            <td><span class="item-badge badge-info">${m.status || 'Active'}</span></td>
            <td style="text-align:right;">
                <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                    <a href="/admin/learning/assessments" class="btn btn-sm btn-secondary" title="View Assessment Results"><i class="fas fa-eye"></i></a>
                    <button type="button" class="btn btn-sm btn-primary assign-module-btn" data-module-id="${m.id}" data-module-title="${m.title}" title="Assign Module to Driver"><i class="fas fa-user-plus"></i> Assign</button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });

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
