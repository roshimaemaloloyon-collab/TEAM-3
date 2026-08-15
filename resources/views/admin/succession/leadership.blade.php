@extends('admin.layouts.admin')

@section('title', 'TripWise — Leadership Potential')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="javascript:void(0);">Succession Planning</a>
    <span>/</span>
    <span>Leadership Potential</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Leadership Potential</h1>
        <p>Evaluate leadership capability and future leadership potential of every driver.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;" onclick="openAddLeadershipModal()"><i class="fas fa-plus"></i> Add Assessment</button>
        <a href="{{ route('admin.succession.leadership.export', ['format' => 'pdf']) }}" target="_blank" class="btn btn-secondary" style="color:#dc2626;border-color:#fca5a5;"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="{{ route('admin.succession.leadership.export', ['format' => 'excel']) }}" target="_blank" class="btn btn-secondary" style="color:#16a34a;border-color:#86efac;"><i class="fas fa-file-excel"></i> Export Excel</a>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.succession.leadership') }}" class="filter-bar" style="margin-bottom: 0; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name or ID..." style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 180px;">
        <select name="status" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 160px;">
            <option value="">All Status</option>
            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>High Potential (Ready)</option>
            <option value="developing" {{ request('status') === 'developing' ? 'selected' : '' }}>Developing</option>
        </select>
        <select name="branch" style="padding: 0.5rem 0.85rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem; background: var(--white); color: var(--text-dark); font-family: 'Inter', sans-serif; min-width: 140px;">
            <option value="">All Branches</option>
            <option value="Central" {{ request('branch') === 'Central' ? 'selected' : '' }}>Central Branch</option>
            <option value="North" {{ request('branch') === 'North' ? 'selected' : '' }}>North Branch</option>
            <option value="South" {{ request('branch') === 'South' ? 'selected' : '' }}>South Branch</option>
            <option value="East" {{ request('branch') === 'East' ? 'selected' : '' }}>East Branch</option>
            <option value="West" {{ request('branch') === 'West' ? 'selected' : '' }}>West Branch</option>
        </select>
        <div style="margin-left: auto; display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; background:#ef4444; border-color:#ef4444;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.succession.leadership') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem; color:#475569;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}/5</h3>
            <p>Average Leadership Score</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> +0.3 this quarter</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['high_potential'] }}</h3>
            <p>High Potential Drivers</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> Active Leadership Pipeline</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-edit"></i></div>
        <div class="card-info">
            <h3>{{ $stats['developing'] }}</h3>
            <p>Developing Candidates</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fas fa-minus"></i> Under Coaching</span>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total_assessments'] }}</h3>
            <p>Assessments Logged</p>
            <span style="font-size: 0.75rem; font-weight: 600; color: var(--success);"><i class="fas fa-arrow-up"></i> 100% Evaluated</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Leadership Score Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="leadershipScoreChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Leadership Trend</h3>
        <div class="chart-wrapper">
            <canvas id="leadershipTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Top Leadership Candidates</h3>
        <div class="chart-wrapper">
            <canvas id="topCandidatesChart"></canvas>
        </div>
    </div>
</div>

<!-- Leadership Assessment Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Leadership Score</th>
                    <th>Leadership Competency</th>
                    <th>Recommended Role</th>
                    <th>Readiness Level</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $c)
                <tr>
                    <td><strong>{{ $c['driver']->formatted_id ?? ('#DRV-2026-' . sprintf('%04d', $c['driver']->id)) }}</strong></td>
                    <td><strong>{{ $c['driver']->full_name ?? 'Driver' }}</strong></td>
                    <td><strong>{{ $c['performance_score'] }}/5</strong></td>
                    <td>{{ $c['competency_score'] }}% Average</td>
                    <td>{{ $c['recommended_role'] }}</td>
                    <td><span class="status-badge {{ $c['readiness'] === 'High Potential' ? 'badge-success' : 'status-pending' }}">{{ $c['readiness'] }}</span></td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.35rem; justify-content: center;">
                            <button type="button" class="btn btn-sm btn-secondary" title="View Assessment Details" style="color:#dc2626;border-color:#fca5a5;" onclick="openViewLeadershipModal({{ json_encode([
                                'id' => $c['driver']->formatted_id ?? ('#DRV-2026-' . sprintf('%04d', $c['driver']->id)),
                                'name' => $c['driver']->full_name ?? 'Driver',
                                'score' => $c['performance_score'] . '/5 Stars',
                                'competency' => $c['competency_score'] . '% Average Score',
                                'role' => $c['recommended_role'],
                                'readiness' => $c['readiness'],
                                'branch' => $c['driver']->branch ?? 'Central Branch'
                            ]) }})"><i class="fas fa-eye"></i></button>

                            <button type="button" class="btn btn-sm btn-primary" title="Edit Assessment" style="background:#ef4444;border-color:#ef4444;" onclick="openEditLeadershipModal({{ json_encode([
                                'id' => $c['driver']->id,
                                'name' => $c['driver']->full_name ?? 'Driver',
                                'role' => $c['recommended_role']
                            ]) }})"><i class="fas fa-edit"></i></button>

                            <form action="{{ route('admin.succession.leadership.destroy', $c['driver']->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive leadership assessment for {{ $c['driver']->full_name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="background:#dc2626;border-color:#dc2626;" title="Archive Record"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">No leadership potential candidates found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Leadership Assessment Modal -->
<div id="addLeadershipModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.succession.leadership.store') }}" method="POST">
            @csrf
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
                <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-user-tie" style="margin-right:0.5rem;"></i> Add Leadership Assessment</h2>
                <button type="button" onclick="closeModal('addLeadershipModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
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
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommended Leadership Role</label>
                    <select name="recommended_role" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="Senior Team Lead / Trainer">Senior Team Lead / Trainer</option>
                        <option value="Assistant Fleet Supervisor">Assistant Fleet Supervisor</option>
                        <option value="Dispatch Operations Lead">Dispatch Operations Lead</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Assessment Notes & Recommendations</label>
                    <textarea name="notes" rows="3" placeholder="Enter evaluation notes..." style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addLeadershipModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Assessment</button>
            </div>
        </form>
    </div>
</div>

<!-- View Leadership Details Modal -->
<div id="viewLeadershipModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-award" style="margin-right:0.5rem;"></i> Leadership Candidate Details</h2>
            <button type="button" onclick="closeModal('viewLeadershipModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver ID</span>
                    <strong id="leadModalId" style="font-size:0.95rem;color:var(--primary);">#DRV-00001</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Readiness Level</span>
                    <span id="leadModalReadiness" class="status-badge badge-success">High Potential</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="leadModalName" style="font-size:1rem;color:#c2410c;">Juan Dela Cruz</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Leadership Score</span>
                    <span id="leadModalScore" style="font-size:0.9rem;font-weight:700;color:#059669;">4.50 / 5</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Competency Score</span>
                    <span id="leadModalCompetency" style="font-size:0.9rem;font-weight:700;color:#2563eb;">88.5%</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Recommended Role Pathway</span>
                    <span id="leadModalRole" style="font-size:0.95rem;font-weight:600;">Senior Team Lead / Trainer</span>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewLeadershipModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Leadership Modal -->
<div id="editLeadershipModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:500px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.succession.leadership.store') }}" method="POST">
            @csrf
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Leadership Assessment</h2>
                <button type="button" onclick="closeModal('editLeadershipModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editLeadName" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommended Role</label>
                    <select name="recommended_role" id="editLeadRole" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="Senior Team Lead / Trainer">Senior Team Lead / Trainer</option>
                        <option value="Assistant Fleet Supervisor">Assistant Fleet Supervisor</option>
                        <option value="Dispatch Operations Lead">Dispatch Operations Lead</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editLeadershipModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

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

window.openAddLeadershipModal = function() {
    window.openModal('addLeadershipModal');
};

window.openViewLeadershipModal = function(data) {
    if (!data) return;
    const idEl = document.getElementById('leadModalId');
    const nameEl = document.getElementById('leadModalName');
    const scoreEl = document.getElementById('leadModalScore');
    const compEl = document.getElementById('leadModalCompetency');
    const roleEl = document.getElementById('leadModalRole');
    const readEl = document.getElementById('leadModalReadiness');

    if (idEl) idEl.innerText = data.id || 'N/A';
    if (nameEl) nameEl.innerText = data.name || 'Driver';
    if (scoreEl) scoreEl.innerText = data.score || 'N/A';
    if (compEl) compEl.innerText = data.competency || 'N/A';
    if (roleEl) roleEl.innerText = data.role || 'N/A';
    if (readEl) readEl.innerText = data.readiness || 'High Potential';

    window.openModal('viewLeadershipModal');
};

window.openEditLeadershipModal = function(data) {
    if (!data) return;
    const nameEl = document.getElementById('editLeadName');
    const roleEl = document.getElementById('editLeadRole');

    if (nameEl) nameEl.value = data.name || '';
    if (roleEl) roleEl.value = data.role || 'Senior Team Lead / Trainer';

    window.openModal('editLeadershipModal');
};

document.addEventListener('DOMContentLoaded', function() {
    const scoreCtx = document.getElementById('leadershipScoreChart');
    if (scoreCtx && typeof Chart !== 'undefined') {
        new Chart(scoreCtx, {
            type: 'bar',
            data: {
                labels: ['4.5-5.0', '4.0-4.4', '3.5-3.9', '3.0-3.4', '<3.0'],
                datasets: [{
                    label: 'Drivers',
                    data: [12, 18, 15, 8, 3],
                    backgroundColor: ['#10b981', '#34d399', '#F44336', '#f59e0b', '#ef4444'],
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

    const trendCtx = document.getElementById('leadershipTrendChart');
    if (trendCtx && typeof Chart !== 'undefined') {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Avg Leadership Score',
                    data: [3.9, 4.0, 4.1, 4.1, 4.2, 4.2, 4.3],
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
                    y: { beginAtZero: false, min: 3.5, max: 5.0, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const candidatesCtx = document.getElementById('topCandidatesChart');
    if (candidatesCtx && typeof Chart !== 'undefined') {
        new Chart(candidatesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Juan Dela Cruz', 'Maria Santos', 'Pedro Reyes', 'Ana Lim', 'Others'],
                datasets: [{
                    data: [4.8, 4.5, 3.9, 4.6, 3.2],
                    backgroundColor: ['#F44336', '#1c1c1e', '#2c2c2e', '#faf9f6', '#f1efe9'],
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
