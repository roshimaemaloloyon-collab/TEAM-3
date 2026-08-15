@extends('admin.layouts.admin')

@section('title', 'TripWise — Development Plan')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Development Plan</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Development Plan</h1>
        <p>Create and manage Individual Development Plans (IDP) for drivers.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;" onclick="openCreatePlanModal()"><i class="fas fa-plus"></i> Create Plan</button>
        <a href="{{ route('admin.succession.development-plan.export', ['format' => 'pdf']) }}" target="_blank" class="btn btn-secondary" style="color:#dc2626;border-color:#fca5a5;"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="{{ route('admin.succession.development-plan.export', ['format' => 'excel']) }}" target="_blank" class="btn btn-secondary" style="color:#16a34a;border-color:#86efac;"><i class="fas fa-file-excel"></i> Export Excel</a>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.succession.development-plan') }}" class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select name="status" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; background:#ef4444; border-color:#ef4444;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.succession.development-plan') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem; color:#475569;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-file-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active_plans'] }}</h3>
            <p>Active Development Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +6 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed_plans'] }}</h3>
            <p>Completed Development Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +5 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-book"></i></div>
        <div class="card-info">
            <h3>{{ $stats['assigned_modules'] }}</h3>
            <p>Assigned Learning Modules</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--info);"><i class="fas fa-minus"></i> Active Curriculum</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="card-info">
            <h3>{{ $stats['assigned_trainings'] }}</h3>
            <p>Assigned Trainings</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +12 this quarter</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Development Progress</h3>
        <div class="chart-wrapper">
            <canvas id="developmentProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Learning Completion</h3>
        <div class="chart-wrapper">
            <canvas id="learningCompletionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompletionChart"></canvas>
        </div>
    </div>
</div>

<!-- Development Plan Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Development Plan</th>
                    <th>Coaching Sessions</th>
                    <th>Progress</th>
                    <th>Completion %</th>
                    <th>Target Date</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td><strong>{{ $plan->driver->name ?? 'Driver' }}</strong></td>
                    <td><strong>{{ $plan->plan_name }}</strong></td>
                    <td>{{ $plan->coaching_sessions ?? 3 }} Sessions</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: {{ $plan->completion_percentage }}%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">{{ $plan->completion_percentage }}%</span>
                        </div>
                    </td>
                    <td><strong>{{ $plan->completion_percentage }}%</strong></td>
                    <td>{{ $plan->target_completion_date ? $plan->target_completion_date->format('M d, Y') : 'N/A' }}</td>
                    <td><span class="status-badge {{ $plan->status === 'active' ? 'status-active' : ($plan->status === 'completed' ? 'badge-success' : 'status-pending') }}">{{ ucfirst($plan->status) }}</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.35rem; justify-content: center;">
                            <button type="button" class="btn btn-sm btn-secondary" title="View Development Plan" style="color:#dc2626;border-color:#fca5a5;" onclick="openViewPlanModal({{ json_encode([
                                'id' => $plan->id,
                                'driver' => $plan->driver->name ?? 'Driver',
                                'plan' => $plan->plan_name,
                                'description' => $plan->description ?? 'Comprehensive Individual Development Plan (IDP) for performance enhancement.',
                                'coaching' => ($plan->coaching_sessions ?? 3) . ' Coaching Sessions',
                                'progress' => $plan->completion_percentage . '%',
                                'target_date' => $plan->target_completion_date ? $plan->target_completion_date->format('M d, Y') : 'N/A',
                                'status' => ucfirst($plan->status),
                                'remarks' => $plan->hr_remarks ?? 'Driver is actively completing assigned learning modules.'
                            ]) }})"><i class="fas fa-eye"></i></button>

                            <button type="button" class="btn btn-sm btn-primary" title="Update Plan & Progress" style="background:#ef4444;border-color:#ef4444;" onclick="openEditPlanModal({{ json_encode([
                                'id' => $plan->id,
                                'driver' => $plan->driver->name ?? 'Driver',
                                'plan' => $plan->plan_name,
                                'progress' => $plan->completion_percentage,
                                'status' => $plan->status,
                                'remarks' => $plan->hr_remarks
                            ]) }})"><i class="fas fa-edit"></i></button>

                            <form action="{{ route('admin.succession.development-plan.destroy', $plan->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive development plan for {{ $plan->driver->name ?? 'Driver' }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="background:#dc2626;border-color:#dc2626;" title="Archive Record"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">No individual development plans found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $plans->links() }}
    </div>
</div>

<!-- Create Plan Modal -->
<div id="createPlanModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.succession.development-plan.store') }}" method="POST">
            @csrf
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
                <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-file-alt" style="margin-right:0.5rem;"></i> Create Individual Development Plan</h2>
                <button type="button" onclick="closeModal('createPlanModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver</label>
                    <select name="driver_id" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}">{{ $d->full_name }} ({{ $d->driver_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Development Plan Name</label>
                    <input type="text" name="plan_name" placeholder="e.g. Advanced Defensive Driving & Leadership Track" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Target Completion Date</label>
                    <input type="date" name="target_completion_date" required value="{{ date('Y-m-d', strtotime('+3 months')) }}" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Plan Objectives & Description</label>
                    <textarea name="description" rows="3" placeholder="Enter development plan objectives..." style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createPlanModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Plan</button>
            </div>
        </form>
    </div>
</div>

<!-- View Plan Modal -->
<div id="viewPlanModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-clipboard-list" style="margin-right:0.5rem;"></i> Individual Development Plan Details</h2>
            <button type="button" onclick="closeModal('viewPlanModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="vPlanDriver" style="font-size:0.95rem;color:var(--primary);">Juan Dela Cruz</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Status</span>
                    <span id="vPlanStatus" class="status-badge status-active">Active</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Development Plan</span>
                    <strong id="vPlanName" style="font-size:1rem;color:#c2410c;">Leadership Development</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Completion Progress</span>
                    <span id="vPlanProgress" style="font-size:0.9rem;font-weight:700;color:#059669;">75%</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Target Date</span>
                    <span id="vPlanTargetDate" style="font-size:0.85rem;font-weight:600;">Dec 31, 2026</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Objectives & Description</span>
                    <p id="vPlanDesc" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">Objectives text...</p>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">HR Remarks</span>
                    <p id="vPlanRemarks" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">HR Remarks text...</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewPlanModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Plan Modal -->
<div id="editPlanModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editPlanForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Update Development Plan Progress</h2>
                <button type="button" onclick="closeModal('editPlanModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editPlanDriver" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Plan Name</label>
                    <input type="text" name="plan_name" id="editPlanName" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Progress (% 0-100)</label>
                        <input type="number" name="completion_percentage" id="editPlanProgress" min="0" max="100" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                        <select name="status" id="editPlanStatus" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">HR / Evaluation Remarks</label>
                    <textarea name="hr_remarks" id="editPlanRemarks" rows="3" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editPlanModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
window.openModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'flex';
        el.style.visibility = 'visible';
        el.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'none';
        document.body.style.overflow = '';
    }
};

window.openCreatePlanModal = function() {
    window.openModal('createPlanModal');
};

window.openViewPlanModal = function(data) {
    if (!data) return;
    const driverEl = document.getElementById('vPlanDriver');
    const nameEl = document.getElementById('vPlanName');
    const progEl = document.getElementById('vPlanProgress');
    const dateEl = document.getElementById('vPlanTargetDate');
    const statEl = document.getElementById('vPlanStatus');
    const descEl = document.getElementById('vPlanDesc');
    const remEl = document.getElementById('vPlanRemarks');

    if (driverEl) driverEl.innerText = data.driver || 'Driver';
    if (nameEl) nameEl.innerText = data.plan || 'Plan';
    if (progEl) progEl.innerText = data.progress || '0%';
    if (dateEl) dateEl.innerText = data.target_date || 'N/A';
    if (statEl) statEl.innerText = data.status || 'Active';
    if (descEl) descEl.innerText = data.description || 'N/A';
    if (remEl) remEl.innerText = data.remarks || 'N/A';

    window.openModal('viewPlanModal');
};

window.openEditPlanModal = function(data) {
    if (!data) return;
    const form = document.getElementById('editPlanForm');
    if (form) form.action = '/admin/succession/development-plan/' + data.id;

    const driverEl = document.getElementById('editPlanDriver');
    const nameEl = document.getElementById('editPlanName');
    const progEl = document.getElementById('editPlanProgress');
    const statEl = document.getElementById('editPlanStatus');
    const remEl = document.getElementById('editPlanRemarks');

    if (driverEl) driverEl.value = data.driver || '';
    if (nameEl) nameEl.value = data.plan || '';
    if (progEl) progEl.value = data.progress || 0;
    if (statEl) statEl.value = data.status || 'active';
    if (remEl) remEl.value = data.remarks || '';

    window.openModal('editPlanModal');
};

document.addEventListener('DOMContentLoaded', function() {
    const progressCtx = document.getElementById('developmentProgressChart');
    if (progressCtx && typeof Chart !== 'undefined') {
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Completion %',
                    data: [20, 30, 45, 50, 60, 65, 68],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F44336'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const learningCtx = document.getElementById('learningCompletionChart');
    if (learningCtx && typeof Chart !== 'undefined') {
        new Chart(learningCtx, {
            type: 'bar',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'Modules Completed',
                    data: [28, 35, 42, 30],
                    backgroundColor: '#10b981',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const trainingCtx = document.getElementById('trainingCompletionChart');
    if (trainingCtx && typeof Chart !== 'undefined') {
        new Chart(trainingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Pending'],
                datasets: [{
                    data: [56, 24, 12],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                    }
                }
            }
        });
    }
});
</script>
@endsection
