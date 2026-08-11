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
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Select your position to view learning modules tailored for your role.</p>
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
                <a href="{{ route('admin.learning.modules', ['position' => $pos['name']]) }}" style="display:block;width:100%;padding:0.6rem 0;text-align:center;border:1px solid var(--border);border-radius:0.5rem;background:var(--white);color:var(--text-dark);font-size:0.85rem;font-weight:600;text-decoration:none;transition:all 0.2s ease;">
                    View Modules
                </a>
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
        <p style="margin:0;font-size:0.85rem;color:var(--text-muted);">Learning modules are continuously updated to ensure you have the latest knowledge and skills required for your role. Keep learning, keep growing with Tripwise!</p>
    </div>
</div>

@endsection

@section('scripts')
<script>
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
</script>
@endsection
