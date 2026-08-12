@extends('admin.layouts.admin')

@section('title', 'TripWise — Skills Assessment')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Skills Assessment</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Skills Assessment</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Assess every driver's competencies based on predefined competency standards.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('assessModal')"><i class="fas fa-plus"></i> New Assessment</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Competency Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $stats['drivers_assessed'] }}</h3>
            <p>Drivers Assessed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Assessments Pending</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completion_rate'] }}</h3>
            <p>Competency Completion Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.assessments') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency</label>
            <select name="competency_id" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Competencies</option>
                @foreach($competencies as $comp)
                    <option value="{{ $comp->id }}" {{ request('competency_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="assessed" {{ request('status') === 'assessed' ? 'selected' : '' }}>Assessed</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Assessment Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-tasks"></i> Skills Assessment</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Competency</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Assessment Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    <tr>
                        <td>#DRV-{{ str_pad($assessment->driver_id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $assessment->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->competency->name ?? 'N/A' }}</td>
                        <td><strong>{{ $assessment->score ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="item-badge {{ $assessment->status === 'assessed' ? 'badge-success' : ($assessment->status === 'pending' ? 'badge-warning' : 'badge-info') }}">
                                {{ ucfirst($assessment->status) }}
                            </span>
                        </td>
                        <td>{{ $assessment->assessed_at ? \Carbon\Carbon::parse($assessment->assessed_at)->format('M d, Y') : 'N/A' }}</td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <a href="{{ route('admin.competency.assessments.driver.pdf', $assessment->driver_id ?? 1) }}" target="_blank" class="btn btn-sm btn-secondary" title="View & Print Assessment PDF"><i class="fas fa-file-pdf"></i></a>
                                <button type="button" class="btn btn-sm btn-primary" title="Edit Assessment In-Place" onclick="openEditAssessModal({{ $assessment->id }}, '{{ addslashes($assessment->driver->name ?? 'Driver') }}', {{ $assessment->score ?? 85 }}, '{{ $assessment->status }}')"><i class="fas fa-edit"></i> Edit</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $assessments->links() }}
    </div>
</div>

<!-- New Assessment Modal -->
<div id="assessModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.competency.assessments.store') }}" method="POST">
            @csrf
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">New Skills Assessment</h2>
                <button type="button" onclick="closeModal('assessModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver</label>
                    <select name="driver_id" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @foreach($allDrivers as $d)
                            <option value="{{ $d->user_id ?: $d->id }}">{{ $d->full_name }} ({{ $d->formatted_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency Category</label>
                    <select name="competency_id" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @foreach($competencies as $comp)
                            <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Assessment Score (0 - 100%)</label>
                    <input type="number" name="score" min="0" max="100" value="88" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Evaluation Status</label>
                    <select name="status" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="assessed">Assessed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('assessModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Assessment</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Assessment Modal -->
<div id="editAssessModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form id="editAssessForm" method="POST">
            @csrf
            @method('PUT')
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Assessment Score</h2>
                <button type="button" onclick="closeModal('editAssessModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editDriverName" readonly style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:#f1f5f9;color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Score (0 - 100%)</label>
                    <input type="number" name="score" id="editScore" min="0" max="100" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" id="editStatus" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="assessed">Assessed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAssessModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update Assessment</button>
            </div>
        </form>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Competency Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="compDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Skills Comparison</h3>
        <div class="chart-wrapper">
            <canvas id="skillsCompChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEditAssessModal(id, name, score, status) {
    document.getElementById('editAssessForm').action = '/admin/competency/assessments/' + id;
    document.getElementById('editDriverName').value = name;
    document.getElementById('editScore').value = score;
    document.getElementById('editStatus').value = status;
    openModal('editAssessModal');
}

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('compDistChart'), {
        type: 'pie',
        data: {
            labels: ['Excellent', 'Proficient', 'Developing', 'Needs Coaching'],
            datasets: [{
                data: [
                    {{ $assessments->where('score', '>=', 90)->count() }},
                    {{ $assessments->whereBetween('score', [75, 89.99])->count() }},
                    {{ $assessments->whereBetween('score', [60, 74.99])->count() }},
                    {{ $assessments->where('score', '<', 60)->count() }}
                ],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('skillsCompChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($competencies->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($competencies->map(fn($c) => $assessments->where('competency_id', $c->id)->avg('score') ?? 0)->toArray()) !!},
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 100 } } }
    });
});
</script>
@endpush
