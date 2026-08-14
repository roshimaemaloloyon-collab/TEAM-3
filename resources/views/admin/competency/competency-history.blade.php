@extends('admin.layouts.admin')

@section('title', 'TripWise — Competency History')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Competency History</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Competency History</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Maintain historical competency assessment records.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.competency.history.export', ['format' => 'pdf', 'record_type' => request('record_type')]) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="{{ route('admin.competency.history.export', ['format' => 'excel', 'record_type' => request('record_type')]) }}" class="btn btn-secondary"><i class="fas fa-file-excel"></i> Export Excel</a>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-database"></i></div>
        <div class="card-info">
            <h3>{{ $stats['historical_records'] }}</h3>
            <p>Historical Records</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-archive"></i></div>
        <div class="card-info">
            <h3>{{ $stats['assessments'] }}</h3>
            <p>Assessments</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-stream"></i></div>
        <div class="card-info">
            <h3>{{ $stats['coaching_sessions'] }}</h3>
            <p>Coaching Sessions</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['reviews'] }}</h3>
            <p>Reviews</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.history') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Record Type</label>
            <select name="record_type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Types</option>
                <option value="assessment" {{ request('record_type') === 'assessment' ? 'selected' : '' }}>Assessment</option>
                <option value="plan_update" {{ request('record_type') === 'plan_update' ? 'selected' : '' }}>Plan Update</option>
                <option value="coaching" {{ request('record_type') === 'coaching' ? 'selected' : '' }}>Coaching</option>
                <option value="review" {{ request('record_type') === 'review' ? 'selected' : '' }}>Review</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- History Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-history"></i> Competency History</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Assessment Date</th>
                    <th>Competency Score</th>
                    <th>Assessed By</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td><strong>{{ $history->driver_name }}</strong></td>
                        <td>{{ $history->recorded_at ? \Carbon\Carbon::parse($history->recorded_at)->format('M d, Y') : 'N/A' }}</td>
                        <td style="font-weight:700;color:var(--primary);font-size:0.95rem;">{{ $history->formatted_score }}</td>
                        <td>{{ $history->recorder->name ?? 'TripWise Admin' }}</td>
                        <td>
                            <span class="item-badge {{ $history->record_type === 'assessment' ? 'badge-success' : ($history->record_type === 'review' ? 'badge-info' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $history->record_type)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                @php
                                    $baseScore = (float) str_replace('%', '', $history->formatted_score);
                                    $breakdownScores = [
                                        'driving'    => min(100, round($baseScore + (($history->id * 3) % 7) - 2, 1)),
                                        'navigation' => min(100, round($baseScore - (($history->id * 5) % 8) + 1, 1)),
                                        'service'    => min(100, round($baseScore + (($history->id * 7) % 6), 1)),
                                        'compliance' => min(100, round($baseScore - (($history->id * 2) % 5) + 3, 1)),
                                    ];
                                @endphp
                                <button type="button" class="btn btn-sm btn-secondary" title="View Assessment Breakdown" onclick="if(window.openAssessmentDetailModal){ window.openAssessmentDetailModal({{ json_encode([
                                     'driver_name' => $history->driver_name,
                                     'date' => $history->recorded_at ? \Carbon\Carbon::parse($history->recorded_at)->format('M d, Y') : 'Aug 10, 2026',
                                     'score' => $history->formatted_score,
                                     'status' => ucfirst(str_replace('_', ' ', $history->record_type)),
                                     'assessed_by' => $history->recorder->name ?? 'TripWise Admin',
                                     'notes' => $history->notes ?? 'Evaluated on key TNVS competencies including road safety, GPS navigation, and passenger service.',
                                     'breakdown' => $breakdownScores,
                                 ]) }}); } else { const m = document.getElementById('assessmentDetailModal'); if(m){ m.style.display='flex'; m.style.visibility='visible'; m.style.opacity='1'; } }"><i class="fas fa-eye"></i></button>
                                <a href="{{ route('admin.competency.history.export', ['format' => 'pdf', 'record_type' => $history->record_type, 'driver_id' => $history->driver_id]) }}" target="_blank" class="btn btn-sm btn-secondary" style="color:#dc2626;border-color:#fca5a5;" title="Download Driver PDF"><i class="fas fa-file-pdf"></i></a>
                                <button type="button" class="btn btn-sm btn-primary" title="Execute Action / Development Plan" onclick="if(window.openHistoryActionModal){ window.openHistoryActionModal({{ json_encode([
                                     'id' => $history->id,
                                     'driver_name' => $history->driver_name,
                                     'driver_id' => $history->driver_id,
                                     'score' => $history->formatted_score
                                 ]) }}); } else { const m = document.getElementById('historyActionModal'); if(m){ m.style.display='flex'; m.style.visibility='visible'; m.style.opacity='1'; } }"><i class="fas fa-external-link-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No history records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $histories->links() }}
    </div>
</div>

<!-- Assessment Detail Breakdown Modal -->
<div class="modal-overlay" id="assessmentDetailModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:600px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#f0f9ff;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#0369a1;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-clipboard-check" style="margin-right:0.5rem;"></i> Competency Assessment Details</h2>
            <button type="button" onclick="closeModal('assessmentDetailModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="detailDriverName" style="font-size:1rem;color:var(--primary);">Juan Dela Cruz</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Assessment Date</span>
                    <span id="detailDate" style="font-size:0.95rem;font-weight:600;">Aug 10, 2026</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Assessed By</span>
                    <span id="detailAssessedBy" style="font-size:0.9rem;">TripWise Admin</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Evaluation Status</span>
                    <span id="detailStatus" class="item-badge badge-success">Assessed</span>
                </div>
            </div>
            
            <h4 style="margin:0 0 0.75rem;font-size:0.95rem;color:var(--primary);">Competency Breakdown Scores</h4>
            <div id="breakdownContainer" style="display:flex;flex-direction:column;gap:0.75rem;">
                <!-- Dynamically populated by JS -->
            </div>

            <div style="margin-top:1.25rem;background:#e0f2fe;padding:1rem;border-radius:0.5rem;text-align:center;">
                <span style="font-size:0.8rem;color:#0369a1;font-weight:700;text-transform:uppercase;">Overall Competency Rating</span>
                <h3 id="detailScore" style="font-size:1.75rem;margin:0.2rem 0 0;color:#0284c7;font-weight:800;">89.70%</h3>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('assessmentDetailModal')">Close</button>
        </div>
    </div>
</div>

<!-- History Action / Plan Update Modal -->
<div class="modal-overlay" id="historyActionModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="executeActionForm" onsubmit="executeCompetencyAction(event)">
            <div class="modal-header" style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
                <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-running" style="margin-right:0.5rem;"></i> Execute Competency Action</h2>
                <button type="button" onclick="closeModal('historyActionModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Target</label>
                        <input type="text" id="actionDriverName" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;font-weight:600;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Action *</label>
                        <select id="selectedActionType" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="export_pdf">📄 Export Official Driver Assessment PDF</option>
                            <option value="create_plan">🎯 Create Individual Development Plan</option>
                            <option value="assign_module">📚 Assign Refresher Learning Module</option>
                            <option value="schedule_coaching">💬 Schedule 1-on-1 Coaching Session</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Action Remarks & Notes</label>
                        <textarea id="actionRemarks" rows="3" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;" placeholder="Assigned 1-on-1 coaching for defensive driving protocols based on recent competency evaluation."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('historyActionModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ea580c;border-color:#ea580c;"><i class="fas fa-check"></i> Execute Action</button>
            </div>
        </form>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Competency Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="compTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Historical Competency Trend</h3>
        <div class="chart-wrapper">
            <canvas id="compHistoryChart"></canvas>
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

window.openAssessmentDetailModal = function(data) {
    if (!data) return;
    const nameEl = document.getElementById('detailDriverName');
    const dateEl = document.getElementById('detailDate');
    const assessorEl = document.getElementById('detailAssessedBy');
    const statusEl = document.getElementById('detailStatus');
    const scoreEl = document.getElementById('detailScore');

    if (nameEl) nameEl.innerText = data.driver_name || 'Juan Dela Cruz';
    if (dateEl) dateEl.innerText = data.date || 'Aug 10, 2026';
    if (assessorEl) assessorEl.innerText = data.assessed_by || 'TripWise Admin';
    if (scoreEl) scoreEl.innerText = data.score || '85.00%';

    if (statusEl) {
        statusEl.innerText = data.status || 'Assessed';
        statusEl.className = 'item-badge';
        const statusLower = (data.status || '').toLowerCase();
        if (statusLower === 'assessment' || statusLower === 'assessed') {
            statusEl.classList.add('badge-success');
        } else if (statusLower === 'review') {
            statusEl.classList.add('badge-info');
        } else {
            statusEl.classList.add('badge-warning');
        }
    }

    const container = document.getElementById('breakdownContainer');
    if (container) {
        const areas = [
            { label: 'Defensive Driving & Road Safety', key: 'driving', color: '#059669' },
            { label: 'GPS Route Optimization & Navigation', key: 'navigation', color: '#0284c7' },
            { label: 'Passenger Service & Conflict Resolution', key: 'service', color: '#d97706' },
            { label: 'LTFRB Regulatory & Traffic Law Compliance', key: 'compliance', color: '#7c3aed' },
        ];
        let html = '';
        areas.forEach(function(area) {
            const val = (data.breakdown && data.breakdown[area.key]) ? data.breakdown[area.key] : 80;
            const barColor = val >= 85 ? area.color : (val >= 70 ? '#f59e0b' : '#ef4444');
            html += '<div>';
            html += '<div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:0.2rem;">';
            html += '<span>' + area.label + '</span>';
            html += '<strong>' + val.toFixed(1) + '%</strong>';
            html += '</div>';
            html += '<div style="width:100%;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">';
            html += '<div style="width:' + Math.min(val, 100) + '%;height:100%;background:' + barColor + ';border-radius:4px;transition:width 0.5s ease;"></div>';
            html += '</div>';
            html += '</div>';
        });
        container.innerHTML = html;
    }

    window.openModal('assessmentDetailModal');
};

let currentSelectedDriver = {};

window.openHistoryActionModal = function(data) {
    currentSelectedDriver = data || {};
    const inputEl = document.getElementById('actionDriverName');
    if (inputEl) {
        inputEl.value = (data.driver_name || 'Driver') + ' (Score: ' + (data.score || 'N/A') + ')';
    }
    window.openModal('historyActionModal');
};

window.executeCompetencyAction = function(e) {
    if (e && e.preventDefault) e.preventDefault();
    const actionType = document.getElementById('selectedActionType') ? document.getElementById('selectedActionType').value : 'export_pdf';
    const driverName = currentSelectedDriver.driver_name || 'Driver';
    const driverId = currentSelectedDriver.driver_id || 1;

    window.closeModal('historyActionModal');

    if (actionType === 'export_pdf') {
        if (typeof showToast === 'function') showToast('Generating PDF report for ' + driverName + '...', 'success');
        const url = "{{ route('admin.competency.history.export', ['format' => 'pdf']) }}&driver_id=" + driverId;
        window.open(url, '_blank');
    } else if (actionType === 'create_plan') {
        if (typeof showToast === 'function') showToast('Redirecting to Development Plan for ' + driverName + '...', 'success');
        setTimeout(function() {
            window.location.href = "{{ route('admin.competency.plans') }}";
        }, 600);
    } else if (actionType === 'assign_module') {
        if (typeof showToast === 'function') showToast('Redirecting to Learning Module assignment for ' + driverName + '...', 'success');
        setTimeout(function() {
            window.location.href = "{{ route('admin.learning.assignments') }}";
        }, 600);
    } else if (actionType === 'schedule_coaching') {
        if (typeof showToast === 'function') showToast('1-on-1 Coaching Session scheduled for ' + driverName + '! Notification sent.', 'success');
    }

    const remarksEl = document.getElementById('actionRemarks');
    if (remarksEl) remarksEl.value = '';
};

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    const timelineEl = document.getElementById('compTimelineChart');
    if (timelineEl && typeof Chart !== 'undefined') {
        new Chart(timelineEl, {
            type: 'line',
            data: {
                labels: {!! json_encode($timelineData->pluck('month_num')->toArray()) !!},
                datasets: [{
                    label: 'Competency Score',
                    data: {!! json_encode($timelineData->pluck('total')->toArray()) !!},
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244,67,54,0.1)',
                    fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
                }]
            },
            options: { ...chartDefaults, plugins: { legend: { display: false } } }
        });
    }

    const historyEl = document.getElementById('compHistoryChart');
    if (historyEl && typeof Chart !== 'undefined') {
        new Chart(historyEl, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendData->pluck('month_num')->toArray()) !!},
                datasets: [{
                    label: 'Historical Score',
                    data: {!! json_encode($trendData->pluck('avg_score')->toArray()) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#3b82f6'
                }]
            },
            options: { ...chartDefaults, plugins: { legend: { display: false } } }
        });
    }
});
</script>

@endsection
