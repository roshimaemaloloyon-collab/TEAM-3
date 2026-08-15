@extends('admin.layouts.admin')

@section('title', 'TripWise — Career Path')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Career Path</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Career Path</h1>
        <p>Track and visualize career progression of every driver.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;" onclick="openAddCareerModal()"><i class="fas fa-plus"></i> Add Career Path</button>
        <a href="{{ route('admin.succession.career-path.export', ['format' => 'pdf']) }}" target="_blank" class="btn btn-secondary" style="color:#dc2626;border-color:#fca5a5;"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="{{ route('admin.succession.career-path.export', ['format' => 'excel']) }}" target="_blank" class="btn btn-secondary" style="color:#16a34a;border-color:#86efac;"><i class="fas fa-file-excel"></i> Export Excel</a>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.succession.career-path') }}" class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name or ID..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select name="status" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Status</option>
            <option value="on-track" {{ request('status') === 'on-track' ? 'selected' : '' }}>On Track</option>
            <option value="at-risk" {{ request('status') === 'at-risk' ? 'selected' : '' }}>At Risk</option>
        </select>
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; background:#ef4444; border-color:#ef4444;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.succession.career-path') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem; color:#475569;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-route"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active_plans'] }}</h3>
            <p>Active Career Plans</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +8 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['ready_next'] }}</h3>
            <p>Drivers Ready for Next Level</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-minus"></i> High Potential Track</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-bullseye"></i></div>
        <div class="card-info">
            <h3>{{ $stats['goals_completed'] }}</h3>
            <p>Career Goals Completed</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +3 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['progress_rate'] }}</h3>
            <p>Career Progress Rate</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +5%</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Career Progress</h3>
        <div class="chart-wrapper">
            <canvas id="careerProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Promotion Pipeline</h3>
        <div class="chart-wrapper">
            <canvas id="promotionPipelineChart"></canvas>
        </div>
    </div>
</div>

<!-- Career Path Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Current Position</th>
                    <th>Recommended Position</th>
                    <th>Required Skills</th>
                    <th>Required Competencies</th>
                    <th>Required Trainings</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($careerPaths as $cp)
                <tr>
                    <td><strong>{{ $cp['driver']->full_name ?? 'Driver' }}</strong></td>
                    <td>{{ $cp['current_position'] }}</td>
                    <td><strong style="color:#c2410c;">{{ $cp['recommended_position'] }}</strong></td>
                    <td>{{ $cp['required_skills'] }}</td>
                    <td>{{ $cp['required_competencies'] }}</td>
                    <td>{{ $cp['required_trainings'] }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="progress-bar" style="flex: 1;">
                                <div class="progress-fill" style="width: {{ $cp['progress'] }}%;"></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">{{ $cp['progress'] }}%</span>
                        </div>
                    </td>
                    <td><span class="status-badge {{ $cp['status'] === 'On Track' ? 'status-active' : 'status-pending' }}">{{ $cp['status'] }}</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.35rem; justify-content: center;">
                            <button type="button" class="btn btn-sm btn-secondary" title="View Career Path Details" style="color:#dc2626;border-color:#fca5a5;" onclick="openViewCareerModal({{ json_encode([
                                'driver' => $cp['driver']->full_name ?? 'Driver',
                                'driver_id' => $cp['driver']->formatted_id ?? ('#DRV-2026-' . sprintf('%04d', $cp['driver']->id)),
                                'current' => $cp['current_position'],
                                'target' => $cp['recommended_position'],
                                'skills' => $cp['required_skills'],
                                'competencies' => $cp['required_competencies'],
                                'trainings' => $cp['required_trainings'],
                                'progress' => $cp['progress'] . '%',
                                'status' => $cp['status']
                            ]) }})"><i class="fas fa-eye"></i></button>

                            <button type="button" class="btn btn-sm btn-primary" title="Update Progression" style="background:#ef4444;border-color:#ef4444;" onclick="openEditCareerModal({{ json_encode([
                                'id' => $cp['id'],
                                'driver' => $cp['driver']->full_name ?? 'Driver',
                                'target' => $cp['recommended_position'],
                                'progress' => $cp['progress']
                            ]) }})"><i class="fas fa-edit"></i></button>

                            <form action="{{ route('admin.succession.career-path.destroy', $cp['id']) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive career path for {{ $cp['driver']->full_name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="background:#dc2626;border-color:#dc2626;" title="Archive Record"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:2rem; color:var(--text-muted);">No career paths found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Career Path Modal -->
<div id="addCareerModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.succession.career-path.store') }}" method="POST">
            @csrf
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
                <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-route" style="margin-right:0.5rem;"></i> Register Driver Career Pathway</h2>
                <button type="button" onclick="closeModal('addCareerModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver</label>
                    <select name="driver_id" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        @foreach($allDrivers as $d)
                            <option value="{{ $d->id }}">{{ $d->full_name }} ({{ $d->driver_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommended Advancement Position</label>
                    <select name="recommended_position" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="Senior Driver">Senior Driver</option>
                        <option value="Route Supervisor">Route Supervisor</option>
                        <option value="Fleet Operations Lead">Fleet Operations Lead</option>
                        <option value="Dispatch Manager">Dispatch Manager</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Required Skills & Competencies</label>
                    <textarea name="required_skills" rows="3" placeholder="e.g. Leadership, Route Planning, Incident Management..." style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addCareerModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Career Path</button>
            </div>
        </form>
    </div>
</div>

<!-- View Career Path Modal -->
<div id="viewCareerModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-user-graduate" style="margin-right:0.5rem;"></i> Driver Career Progression Pathway</h2>
            <button type="button" onclick="closeModal('viewCareerModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver ID</span>
                    <strong id="vCareerId" style="font-size:0.95rem;color:var(--primary);">#DRV-00001</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Readiness Status</span>
                    <span id="vCareerStatus" class="status-badge status-active">On Track</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="vCareerDriver" style="font-size:1rem;color:#c2410c;">Juan Dela Cruz</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Current Position</span>
                    <span id="vCareerCurrent" style="font-size:0.9rem;font-weight:600;">Senior Driver</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Target Advancement</span>
                    <span id="vCareerTarget" style="font-size:0.9rem;font-weight:700;color:#059669;">Route Supervisor</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Required Skills & Competencies</span>
                    <p id="vCareerSkills" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">Leadership, Planning, Decision Making</p>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Required Trainings</span>
                    <p id="vCareerTrainings" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">Supervisory & Fleet Operations Seminar</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewCareerModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Career Path Modal -->
<div id="editCareerModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editCareerForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Update Career Progression</h2>
                <button type="button" onclick="closeModal('editCareerModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editCareerDriver" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommended Position</label>
                    <select name="recommended_position" id="editCareerTarget" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="Senior Driver">Senior Driver</option>
                        <option value="Route Supervisor">Route Supervisor</option>
                        <option value="Fleet Operations Lead">Fleet Operations Lead</option>
                        <option value="Dispatch Manager">Dispatch Manager</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Readiness Progress (% 0-100)</label>
                    <input type="number" name="progress" id="editCareerProgress" min="0" max="100" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editCareerModal')">Cancel</button>
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

window.openAddCareerModal = function() {
    window.openModal('addCareerModal');
};

window.openViewCareerModal = function(data) {
    if (!data) return;
    const idEl = document.getElementById('vCareerId');
    const driverEl = document.getElementById('vCareerDriver');
    const currEl = document.getElementById('vCareerCurrent');
    const targetEl = document.getElementById('vCareerTarget');
    const skillsEl = document.getElementById('vCareerSkills');
    const trainEl = document.getElementById('vCareerTrainings');
    const statEl = document.getElementById('vCareerStatus');

    if (idEl) idEl.innerText = data.driver_id || 'N/A';
    if (driverEl) driverEl.innerText = data.driver || 'Driver';
    if (currEl) currEl.innerText = data.current || 'Driver';
    if (targetEl) targetEl.innerText = data.target || 'Route Supervisor';
    if (skillsEl) skillsEl.innerText = data.skills || 'Leadership, Route Planning';
    if (trainEl) trainEl.innerText = data.trainings || 'Supervisory Seminar';
    if (statEl) statEl.innerText = data.status || 'On Track';

    window.openModal('viewCareerModal');
};

window.openEditCareerModal = function(data) {
    if (!data) return;
    const form = document.getElementById('editCareerForm');
    if (form) form.action = '/admin/succession/career-path/' + data.id;

    const driverEl = document.getElementById('editCareerDriver');
    const targetEl = document.getElementById('editCareerTarget');
    const progEl = document.getElementById('editCareerProgress');

    if (driverEl) driverEl.value = data.driver || '';
    if (targetEl) targetEl.value = data.target || 'Route Supervisor';
    if (progEl) progEl.value = data.progress || 75;

    window.openModal('editCareerModal');
};

document.addEventListener('DOMContentLoaded', function() {
    const progressCtx = document.getElementById('careerProgressChart');
    if (progressCtx && typeof Chart !== 'undefined') {
        new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'Avg Progress %',
                    data: [30, 45, 60, 75],
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

    const pipelineCtx = document.getElementById('promotionPipelineChart');
    if (pipelineCtx && typeof Chart !== 'undefined') {
        new Chart(pipelineCtx, {
            type: 'bar',
            data: {
                labels: ['Senior Driver', 'Route Supervisor', 'Fleet Supervisor', 'Operations Manager'],
                datasets: [{
                    label: 'Drivers in Track',
                    data: [15, 10, 6, 3],
                    backgroundColor: '#ef4444',
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
});
</script>
@endsection
