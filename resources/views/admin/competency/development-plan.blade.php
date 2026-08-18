@extends('admin.layouts.admin')

@section('title', 'TripWise — Development Plan')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Development Plan</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Development Plan</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Create competency improvement plans for drivers.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('planModal')"><i class="fas fa-plus"></i> Create Plan</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-clipboard-list"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Development Plans</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Completed Plans</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-pause-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['on_hold'] }}</h3>
            <p>On Hold</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_progress'] }}</h3>
            <p>Average Progress</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.plans') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Development Plans Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-list"></i> Development Plans</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Development Plan</th>
                    <th>Assigned Learning</th>
                    <th>Assigned Training</th>
                    <th>Progress</th>
                    <th>Completion %</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td><strong>{{ $plan->driver_name }}</strong></td>
                        <td>{{ $plan->plan_name }}</td>
                        <td>
                            @php
                                $learning = $plan->assigned_learning_modules ?? [];
                                echo is_array($learning) ? count($learning) . ' modules' : 'N/A';
                            @endphp
                        </td>
                        <td>
                            @php
                                $trainings = $plan->assigned_trainings ?? [];
                                echo is_array($trainings) ? count($trainings) . ' trainings' : 'N/A';
                            @endphp
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div class="progress-bar" style="width:100px;height:8px;">
                                    <div class="progress-fill" style="width:{{ $plan->completion_percentage }}%;"></div>
                                </div>
                                <span style="font-size:0.85rem;font-weight:600;">{{ $plan->completion_percentage }}%</span>
                            </div>
                        </td>
                        <td><strong>{{ $plan->completion_percentage }}%</strong></td>
                        <td>
                            <span class="item-badge {{ $plan->status === 'completed' ? 'badge-success' : ($plan->status === 'active' ? 'badge-info' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;align-items:center;">
                                <a href="{{ route('admin.competency.assessments.driver.pdf', $plan->driver_id ?? 1) }}" target="_blank" class="btn btn-sm btn-secondary" title="View Plan PDF Report"><i class="fas fa-file-pdf"></i></a>
                                <button type="button" class="btn btn-sm btn-primary edit-plan-btn" title="Edit Plan In-Place" data-id="{{ $plan->id }}" data-driver="{{ $plan->driver->name ?? 'Driver' }}" data-name="{{ $plan->plan_name }}" data-progress="{{ $plan->completion_percentage }}" data-status="{{ $plan->status }}"><i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-sm btn-success" title="Deploy Development Plan to Driver" style="background:#10b981;border-color:#10b981;color:#ffffff;font-weight:600;" onclick="openDeployModal({{ $plan->id }}, '{{ addslashes($plan->plan_name) }}', '{{ addslashes($plan->driver_name) }}')">
                                    <i class="fas fa-rocket" style="margin-right:4px;"></i> Deploy
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No development plans found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $plans->links() }}
    </div>
</div>

<!-- Deploy Plan Confirmation Modal -->
<div id="deployPlanModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:480px;box-shadow:0 20px 50px rgba(0,0,0,0.25);overflow:hidden;">
        <form id="deployPlanForm" method="POST">
            @csrf
            <div class="modal-header" style="padding:1.25rem 1.5rem;background:#ecfdf5;border-bottom:1px solid #a7f3d0;display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.2rem;color:#047857;font-family:'Poppins',sans-serif;margin:0;font-weight:700;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-rocket" style="color:#059669;"></i> Deploy Development Plan</h2>
                <button type="button" onclick="closeModal('deployPlanModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;text-align:left;">
                <p style="margin:0 0 1rem;font-size:0.95rem;color:var(--text-dark);line-height:1.5;">Are you sure you want to deploy this development plan to the assigned driver? Once deployed, the driver will receive training modules and progress tracking.</p>
                <div style="background:#f0fdf4;padding:1rem;border-radius:0.5rem;border:1px solid #bbf7d0;">
                    <div style="margin-bottom:0.5rem;">
                        <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Plan Title</span>
                        <strong id="deployPlanTitle" style="font-size:0.95rem;color:#047857;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Assigned Driver</span>
                        <strong id="deployDriverName" style="font-size:0.95rem;color:#1e293b;">-</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;background:#f8fafc;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deployPlanModal')">Cancel</button>
                <button type="submit" class="btn btn-success" style="background:#059669;border-color:#059669;color:#ffffff;font-weight:600;"><i class="fas fa-paper-plane" style="margin-right:4px;"></i> Confirm Deployment</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Plan Modal -->
<div id="planModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.competency.plans.store') }}" method="POST">
            @csrf
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Create Development Plan</h2>
                <button type="button" onclick="closeModal('planModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver</label>
                    <select name="driver_id" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @foreach($allDrivers as $d)
                            <option value="{{ $d->id }}">{{ $d->full_name }} ({{ $d->formatted_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Plan Name / Target Skill</label>
                    <input type="text" name="plan_name" placeholder="e.g. Advanced GPS & Route Optimization Course" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Initial Progress %</label>
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
                <button type="button" class="btn btn-secondary" onclick="closeModal('planModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Plan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Plan Modal -->
<div id="editPlanModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form id="editPlanForm" method="POST">
            @csrf
            @method('PUT')
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Development Plan</h2>
                <button type="button" onclick="closeModal('editPlanModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editDriverName" readonly style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:#f1f5f9;color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Plan Name / Skill</label>
                    <input type="text" name="plan_name" id="editPlanName" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Progress %</label>
                    <input type="number" name="completion_percentage" id="editProgress" min="0" max="100" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" id="editStatus" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editPlanModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update Plan</button>
            </div>
        </form>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Development Progress</h3>
        <div class="chart-wrapper">
            <canvas id="devProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompChart"></canvas>
        </div>
    </div>
</div>

<script>
window.openDeployModal = function(id, planTitle, driverName) {
    const form = document.getElementById('deployPlanForm');
    if (form) form.action = '/admin/competency/plans/' + id + '/deploy';

    const titleEl = document.getElementById('deployPlanTitle');
    const driverEl = document.getElementById('deployDriverName');

    if (titleEl) titleEl.innerText = planTitle || 'Development Plan';
    if (driverEl) driverEl.innerText = driverName || 'Assigned Driver';

    const modal = document.getElementById('deployPlanModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
    }
};

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.edit-plan-btn');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-id');
    const driverName = btn.getAttribute('data-driver');
    const planName = btn.getAttribute('data-name');
    const progress = btn.getAttribute('data-progress');
    const status = btn.getAttribute('data-status');

    const modal = document.getElementById('editPlanModal');
    if (modal) {
        document.getElementById('editPlanForm').action = '/admin/competency/plans/' + id;
        document.getElementById('editDriverName').value = driverName;
        document.getElementById('editPlanName').value = planName;
        document.getElementById('editProgress').value = progress;
        document.getElementById('editStatus').value = status;
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('devProgressChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($progressData->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Completion %',
                data: {!! json_encode($progressData->pluck('avg_progress')->toArray()) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('trainingCompChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($trainingData->pluck('status')->toArray()) !!},
            datasets: [{
                label: 'Plans',
                data: {!! json_encode($trainingData->pluck('total')->toArray()) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });
});
</script>

@endsection
