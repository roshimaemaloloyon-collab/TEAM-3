@extends('admin.layouts.admin')

@section('title', 'TripWise — Gap Analysis')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Gap Analysis</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Competency Gap Analysis</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Identify, measure, and analyze performance gaps between actual driver skills and target operational benchmarks.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.competency.gap-analysis.pdf') }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print"></i> Export Gap Report</a>
        <button class="btn btn-primary" onclick="openCreatePlanModal()"><i class="fas fa-plus-circle"></i> Create Development Plan</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['critical_gaps'] }}</h3>
            <p>Critical Skill Gaps (&lt;60%)</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-chart-pie"></i></div>
        <div class="card-info">
            <h3>{{ $stats['moderate_gaps'] }}</h3>
            <p>Moderate Skill Gaps (60-74%)</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bullseye"></i></div>
        <div class="card-info">
            <h3>{{ $stats['overall_gap'] }}%</h3>
            <p>Average Skill Variance</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['proficient_count'] }}</h3>
            <p>Proficient Drivers (&ge;85%)</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('admin.competency.gap-analysis') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver by name or email..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency Category</label>
            <select name="category" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Categories</option>
                <option value="safety" {{ request('category') === 'safety' ? 'selected' : '' }}>Safety & Regulations</option>
                <option value="customer_service" {{ request('category') === 'customer_service' ? 'selected' : '' }}>Customer Service</option>
                <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>Technical Driving Skills</option>
                <option value="behavioral" {{ request('category') === 'behavioral' ? 'selected' : '' }}>Behavioral & Conduct</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Gap Level</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Gap Levels</option>
                <option value="critical" {{ request('status') === 'critical' ? 'selected' : '' }}>Critical Gap (&lt;60%)</option>
                <option value="moderate" {{ request('status') === 'moderate' ? 'selected' : '' }}>Moderate Gap (60-74%)</option>
                <option value="minimal" {{ request('status') === 'minimal' ? 'selected' : '' }}>Minimal Gap (75-84%)</option>
                <option value="proficient" {{ request('status') === 'proficient' ? 'selected' : '' }}>Proficient (&ge;85%)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Analyze</button>
    </form>
</div>

<!-- Real-time Skill Gap Analysis Table -->
<div class="table-card" style="margin-bottom:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.5rem;">
        <h3 style="margin:0;"><i class="fas fa-tasks"></i> Driver Skill Gap Matrix</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Real-time competency assessment vs operational target (85.0%)</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>Competency Skill</th>
                    <th>Category</th>
                    <th>Target Score</th>
                    <th>Actual Score</th>
                    <th>Skill Gap</th>
                    <th>Gap Severity</th>
                    <th>Recommended Action</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    @php
                        $target = $assessment->competency->target_score ?? 85;
                        $actual = $assessment->score ?? 0;
                        $gap = max(0, round($target - $actual, 1));
                        
                        if ($actual < 60) {
                            $badgeClass = 'badge-danger';
                            $severity = 'Critical Gap';
                            $action = 'Mandatory Re-training';
                        } elseif ($actual < 75) {
                            $badgeClass = 'badge-warning';
                            $severity = 'Moderate Gap';
                            $action = 'Assigned Mentorship';
                        } elseif ($actual < 85) {
                            $badgeClass = 'badge-info';
                            $severity = 'Minimal Gap';
                            $action = 'Refresher Course';
                        } else {
                            $badgeClass = 'badge-success';
                            $severity = 'Proficient';
                            $action = 'Skill Maintenance';
                        }

                        $driverName = $assessment->driver->name ?? 'Driver #' . $assessment->driver_id;
                        $compName = $assessment->competency->name ?? 'Operational Safety';
                    @endphp
                    <tr>
                        <td><strong>{{ $driverName }}</strong></td>
                        <td>{{ $compName }}</td>
                        <td><span style="text-transform:capitalize;font-size:0.82rem;padding:0.2rem 0.6rem;background:var(--beige-dark);border-radius:0.4rem;">{{ str_replace('_', ' ', $assessment->competency->category ?? 'General') }}</span></td>
                        <td><strong>{{ number_format($target, 1) }}%</strong></td>
                        <td><strong style="color: {{ $actual < 70 ? 'var(--danger)' : 'inherit' }}">{{ number_format($actual, 1) }}%</strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.4rem;">
                                <span style="font-weight:700;color:{{ $gap > 15 ? 'var(--danger)' : ($gap > 0 ? 'var(--warning)' : 'var(--success)') }}">
                                    -{{ $gap }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="item-badge {{ $badgeClass }}">
                                {{ $severity }}
                            </span>
                        </td>
                        <td><span style="font-size:0.85rem;font-weight:500;">{{ $action }}</span></td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button type="button" class="btn btn-sm btn-primary assign-plan-btn" title="Assign Development Plan" data-driver-id="{{ $assessment->driver_id }}" data-driver-name="{{ $driverName }}" data-skill="{{ $compName }} — {{ $action }}"><i class="fas fa-user-graduate"></i></button>
                                <a href="{{ route('admin.competency.assessments.driver.pdf', $assessment->driver_id ?? $assessment->id) }}" target="_blank" class="btn btn-sm btn-secondary" title="View Official Assessment PDF"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:2rem;">No competency gap assessments found matching your filter criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $assessments->links() }}
    </div>
</div>

<!-- Recommended Training Programs Section -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-chalkboard-teacher"></i> Automated Training Intervention Recommendations</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1rem;">
        @foreach($recommendedTrainings as $training)
            <div style="border:1px solid var(--border);border-radius:0.75rem;padding:1rem;background:var(--white);display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:var(--primary);text-transform:uppercase;">Intervention Course</span>
                        <span class="item-badge badge-info">{{ ucfirst($training->status ?? 'upcoming') }}</span>
                    </div>
                    <h4 style="margin:0 0 0.4rem;font-size:1rem;color:var(--text-dark);">{{ $training->title ?? $training->name }}</h4>
                    <p style="font-size:0.82rem;color:var(--text-muted);margin:0 0 0.75rem;">Designed to close critical skill variances in driving & safety protocols.</p>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--border);padding-top:0.75rem;margin-top:0.5rem;">
                    <span style="font-size:0.8rem;color:var(--text-muted);"><i class="fas fa-clock"></i> {{ $training->duration ?? '4 Hours' }}</span>
                    <button type="button" class="btn btn-sm btn-secondary assign-plan-btn" data-skill="{{ $training->title ?? $training->name }}" style="font-size:0.78rem;">Enroll Gap Drivers</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Assign Development Plan Modal -->
<div id="assignPlanModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.competency.plans.store') }}" method="POST">
            @csrf
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Assign Development Plan</h2>
                <button type="button" onclick="closeModal('assignPlanModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Target Driver</label>
                    <select name="driver_id" id="assignDriverSelect" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @if(isset($allDrivers))
                            @foreach($allDrivers as $d)
                                <option value="{{ $d->id }}">{{ $d->full_name }} ({{ $d->formatted_id }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Plan Name / Target Skill Intervention</label>
                    <input type="text" name="plan_name" id="assignPlanName" placeholder="e.g. Mandatory Re-training: Defensive Driving" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Initial Completion Progress %</label>
                    <input type="number" name="completion_percentage" min="0" max="100" value="0" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('assignPlanModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-graduate"></i> Assign & Save Plan</button>
            </div>
        </form>
    </div>
</div>

<script>
window.openCreatePlanModal = function() {
    var modal = document.getElementById('assignPlanModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
};

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.assign-plan-btn');
    if (!btn) return;
    e.preventDefault();

    const driverId = btn.getAttribute('data-driver-id');
    const skill = btn.getAttribute('data-skill');

    const modal = document.getElementById('assignPlanModal');
    if (modal) {
        if (driverId) {
            const select = document.getElementById('assignDriverSelect');
            if (select) select.value = driverId;
        }
        if (skill) {
            const input = document.getElementById('assignPlanName');
            if (input) input.value = skill;
        }
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
});
</script>

@endsection
