@extends('admin.layouts.admin')

@section('title', 'TripWise — Assessments')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Assessments</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Assessments</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Evaluate driver knowledge after completing learning modules.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Assessments Completed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Quiz Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-thumbs-up"></i></div>
        <div class="card-info">
            <h3>{{ $stats['passed'] }}</h3>
            <p>Passed Assessments</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['failed'] }}</h3>
            <p>Failed Assessments</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.learning.assessments') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Assessments Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Assessment Records</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Assessment ID</th>
                    <th>Driver</th>
                    <th>Learning Module</th>
                    <th>Score</th>
                    <th>Passing Score</th>
                    <th>Attempt</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    <tr>
                        <td>#ASM-{{ str_pad($assessment->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $assessment->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->module->title ?? 'N/A' }}</td>
                        <td><strong>{{ $assessment->score ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->passing_score }}</td>
                        <td>{{ $assessment->attempt }} / {{ $assessment->max_attempts }}</td>
                        <td>
                            <span class="item-badge {{ $assessment->status === 'passed' ? 'badge-success' : ($assessment->status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View Results" style="color:#dc2626;border-color:#fca5a5;" onclick="openViewAssessmentModal({{ json_encode([
                                    'id' => '#ASM-' . str_pad($assessment->id, 5, '0', STR_PAD_LEFT),
                                    'driver' => $assessment->driver->name ?? 'N/A',
                                    'module' => $assessment->module->title ?? 'N/A',
                                    'score' => $assessment->score ?? 'N/A',
                                    'passing_score' => $assessment->passing_score,
                                    'attempt' => $assessment->attempt . ' / ' . $assessment->max_attempts,
                                    'status' => ucfirst(str_replace('_', ' ', $assessment->status)),
                                    'completed_at' => $assessment->completed_at ? $assessment->completed_at->format('M d, Y h:i A') : 'In Progress'
                                ]) }})"><i class="fas fa-eye"></i></button>

                                <form action="{{ route('admin.learning.assessments.retake', $assessment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Allow {{ $assessment->driver->name ?? 'driver' }} to retake this assessment?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" style="background:#ef4444;border-color:#ef4444;" title="Retake Assessment"><i class="fas fa-redo"></i></button>
                                </form>

                                <button class="btn btn-sm btn-primary" style="background:#ef4444;border-color:#ef4444;" title="Edit Assessment" onclick="openEditAssessmentModal({{ json_encode([
                                    'id' => $assessment->id,
                                    'driver' => $assessment->driver->name ?? 'N/A',
                                    'score' => $assessment->score,
                                    'status' => $assessment->status
                                ]) }})"><i class="fas fa-edit"></i></button>

                                <form action="{{ route('admin.learning.assessments.destroy', $assessment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to archive/delete this assessment record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="background:#dc2626;border-color:#dc2626;" title="Archive / Delete Record"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $assessments->links() }}
    </div>
</div>

<!-- View Results Modal -->
<div id="viewAssessmentModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#f0f9ff;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#0369a1;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-clipboard-check" style="margin-right:0.5rem;"></i> Assessment Results Details</h2>
            <button type="button" onclick="closeModal('viewAssessmentModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Assessment Ref</span>
                    <strong id="modalAsmId" style="font-size:0.95rem;color:var(--primary);">#ASM-00001</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="modalDriverName" style="font-size:0.95rem;">Juan Dela Cruz</strong>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Learning Module</span>
                    <span id="modalModuleTitle" style="font-size:0.9rem;font-weight:600;color:#0369a1;">Module Name</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Score / Passing</span>
                    <span id="modalScoreVal" style="font-size:0.95rem;font-weight:700;color:#059669;">85 / 75</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Attempt Status</span>
                    <span id="modalStatusBadge" class="item-badge badge-success">Passed</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Completed Date & Time</span>
                    <span id="modalCompletedAt" style="font-size:0.85rem;color:var(--text-dark);">Aug 14, 2026</span>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewAssessmentModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Assessment Modal -->
<div id="editAssessmentModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:500px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editAssessmentForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Assessment Record</h2>
                <button type="button" onclick="closeModal('editAssessmentModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editDriverName" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Score (0 - 100)</label>
                    <input type="number" name="score" id="editScore" min="0" max="100" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" id="editStatus" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="passed">Passed</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAssessmentModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Quiz Performance</h3>
        <div class="chart-wrapper">
            <canvas id="quizPerformanceChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Pass vs Fail</h3>
        <div class="chart-wrapper">
            <canvas id="passFailChart"></canvas>
        </div>
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

window.openViewAssessmentModal = function(data) {
    document.getElementById('modalAsmId').innerText = data.id;
    document.getElementById('modalDriverName').innerText = data.driver;
    document.getElementById('modalModuleTitle').innerText = data.module;
    document.getElementById('modalScoreVal').innerText = data.score + ' (Passing: ' + data.passing_score + ')';
    document.getElementById('modalStatusBadge').innerText = data.status;
    document.getElementById('modalCompletedAt').innerText = data.completed_at;
    window.openModal('viewAssessmentModal');
};

window.openEditAssessmentModal = function(data) {
    document.getElementById('editAssessmentForm').action = '/admin/learning/assessments/' + data.id;
    document.getElementById('editDriverName').value = data.driver;
    document.getElementById('editScore').value = data.score;
    document.getElementById('editStatus').value = data.status;
    window.openModal('editAssessmentModal');
};
</script>

@endsection

@section('scripts')
<script>
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('quizPerformanceChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($quizPerformance->pluck('module.title')->toArray()) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($quizPerformance->pluck('avg_score')->toArray()) !!},
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    new Chart(document.getElementById('passFailChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($passFailData)) !!},
            datasets: [{ data: {!! json_encode(array_values($passFailData)) !!}, backgroundColor: ['#10b981', '#ef4444'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });
});
</script>
@endsection
