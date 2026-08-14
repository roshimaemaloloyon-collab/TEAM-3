@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Evaluation')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Training Evaluation</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training Evaluation</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Evaluate the effectiveness of completed training sessions.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Evaluation Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-smile"></i></div>
        <div class="card-info">
            <h3>{{ $stats['satisfaction'] }}</h3>
            <p>Training Satisfaction</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Completed Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Pending Evaluations</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.training.evaluations') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <div style="position:relative;">
                <input type="text" id="driverSearchInput" name="search" value="{{ request('search') }}" placeholder="Search driver name, ID, or training..." style="width:100%;padding:0.6rem 2.2rem 0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <button type="button" id="clearSearchBtn" onclick="document.getElementById('driverSearchInput').value='';this.form.submit();" style="position:absolute;right:0.6rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;display:{{ request('search') ? 'block' : 'none' }};"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary" style="background:#ef4444;border-color:#ef4444;color:#fff;"><i class="fas fa-search"></i> Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.training.evaluations') }}" class="btn btn-secondary" style="background:#f1f5f9;color:#475569;border-color:#cbd5e1;"><i class="fas fa-undo"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Evaluations Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Training Evaluations</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Training</th>
                    <th>Driver</th>
                    <th>Evaluation Score</th>
                    <th>Feedback</th>
                    <th>Suggestions</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $evaluation)
                    <tr>
                        <td><strong>{{ $evaluation->training->title ?? 'N/A' }}</strong></td>
                        <td>{{ $evaluation->driver->name ?? 'N/A' }}</td>
                        <td><strong>{{ $evaluation->overall_rating ?? 'N/A' }}/5</strong></td>
                        <td>{{ Str::limit($evaluation->driver_feedback, 50) }}</td>
                        <td>{{ Str::limit($evaluation->recommendations, 50) }}</td>
                        <td>{{ $evaluation->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="item-badge {{ $evaluation->status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($evaluation->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <button type="button" class="btn btn-sm btn-secondary" title="View Evaluation Details" style="color:#dc2626;border-color:#fca5a5;" onclick="openViewEvaluationModal({{ json_encode([
                                    'training' => $evaluation->training->title ?? 'N/A',
                                    'driver' => $evaluation->driver->name ?? 'N/A',
                                    'rating' => ($evaluation->overall_rating ?? 'N/A') . '/5 Stars',
                                    'feedback' => $evaluation->driver_feedback ?? 'No feedback specified.',
                                    'suggestions' => $evaluation->recommendations ?? 'No suggestions specified.',
                                    'date' => $evaluation->created_at->format('M d, Y'),
                                    'status' => ucfirst($evaluation->status)
                                ]) }})"><i class="fas fa-eye"></i></button>

                                <button type="button" class="btn btn-sm btn-primary" title="Edit Evaluation Record" style="background:#ef4444;border-color:#ef4444;" onclick="openEditEvaluationModal({{ json_encode([
                                    'id' => $evaluation->id,
                                    'training' => $evaluation->training->title ?? 'N/A',
                                    'driver' => $evaluation->driver->name ?? 'N/A',
                                    'rating' => $evaluation->overall_rating ?? 5,
                                    'feedback' => $evaluation->driver_feedback,
                                    'suggestions' => $evaluation->recommendations,
                                    'status' => $evaluation->status
                                ]) }})"><i class="fas fa-edit"></i></button>

                                <form action="{{ route('admin.training.evaluations.destroy', $evaluation->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive/delete this evaluation record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="background:#dc2626;border-color:#dc2626;" title="Archive / Delete Record"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No evaluations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $evaluations->links() }}
    </div>
</div>

<!-- View Evaluation Modal -->
<div id="viewEvaluationModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-clipboard-check" style="margin-right:0.5rem;"></i> Training Evaluation Details</h2>
            <button type="button" onclick="closeModal('viewEvaluationModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Name</span>
                    <strong id="evalModalDriver" style="font-size:0.95rem;color:var(--primary);">Juan Dela Cruz</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Evaluation Score</span>
                    <strong id="evalModalRating" style="font-size:0.95rem;color:#059669;">5/5 Stars</strong>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Training Program</span>
                    <strong id="evalModalTraining" style="font-size:1rem;color:#c2410c;">Training Title</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Date Submitted</span>
                    <span id="evalModalDate" style="font-size:0.85rem;font-weight:600;">Aug 13, 2026</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Status</span>
                    <span id="evalModalStatusBadge" class="item-badge badge-success">Completed</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Driver Feedback</span>
                    <p id="evalModalFeedback" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">Feedback text...</p>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Recommendations / Suggestions</span>
                    <p id="evalModalSuggestions" style="font-size:0.85rem;margin:0.2rem 0 0;color:var(--text-dark);">Suggestions text...</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewEvaluationModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Evaluation Modal -->
<div id="editEvaluationModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editEvaluationForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Evaluation Record</h2>
                <button type="button" onclick="closeModal('editEvaluationModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editEvalDriver" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Training Title</label>
                    <input type="text" id="editEvalTraining" readonly style="width:100%;padding:0.6rem;background:#f1f5f9;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Score Rating (1-5)</label>
                        <select name="overall_rating" id="editEvalRating" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="5">5 / 5 Stars</option>
                            <option value="4">4 / 5 Stars</option>
                            <option value="3">3 / 5 Stars</option>
                            <option value="2">2 / 5 Stars</option>
                            <option value="1">1 / 5 Stars</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                        <select name="status" id="editEvalStatus" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Feedback</label>
                    <textarea name="driver_feedback" id="editEvalFeedback" rows="2" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommendations & Suggestions</label>
                    <textarea name="recommendations" id="editEvalSuggestions" rows="2" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editEvaluationModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Evaluation Trend</h3>
        <div class="chart-wrapper">
            <canvas id="evaluationTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Satisfaction Rating</h3>
        <div class="chart-wrapper">
            <canvas id="satisfactionChart"></canvas>
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

window.openViewEvaluationModal = function(data) {
    if (!data) return;
    const driverEl = document.getElementById('evalModalDriver');
    const trnEl = document.getElementById('evalModalTraining');
    const ratEl = document.getElementById('evalModalRating');
    const dateEl = document.getElementById('evalModalDate');
    const statEl = document.getElementById('evalModalStatusBadge');
    const fbEl = document.getElementById('evalModalFeedback');
    const sugEl = document.getElementById('evalModalSuggestions');

    if (driverEl) driverEl.innerText = data.driver || 'N/A';
    if (trnEl) trnEl.innerText = data.training || 'N/A';
    if (ratEl) ratEl.innerText = data.rating || '0/5';
    if (dateEl) dateEl.innerText = data.date || 'N/A';
    if (statEl) statEl.innerText = data.status || 'Completed';
    if (fbEl) fbEl.innerText = data.feedback || 'No feedback specified.';
    if (sugEl) sugEl.innerText = data.suggestions || 'No suggestions specified.';

    window.openModal('viewEvaluationModal');
};

window.openEditEvaluationModal = function(data) {
    if (!data) return;
    const form = document.getElementById('editEvaluationForm');
    if (form) form.action = '/admin/training/evaluations/' + data.id;

    const driverEl = document.getElementById('editEvalDriver');
    const trnEl = document.getElementById('editEvalTraining');
    const ratEl = document.getElementById('editEvalRating');
    const fbEl = document.getElementById('editEvalFeedback');
    const sugEl = document.getElementById('editEvalSuggestions');
    const statEl = document.getElementById('editEvalStatus');

    if (driverEl) driverEl.value = data.driver || '';
    if (trnEl) trnEl.value = data.training || '';
    if (ratEl) ratEl.value = data.rating || 5;
    if (fbEl) fbEl.value = data.feedback || '';
    if (sugEl) sugEl.value = data.suggestions || '';
    if (statEl) statEl.value = data.status || 'completed';

    window.openModal('editEvaluationModal');
};

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    const trendEl = document.getElementById('evaluationTrendChart');
    if (trendEl && typeof Chart !== 'undefined') {
        new Chart(trendEl, {
            type: 'line',
            data: {
                labels: {!! json_encode($evaluationTrend->pluck('month_num')->toArray()) !!},
                datasets: [{
                    label: 'Avg Score',
                    data: {!! json_encode($evaluationTrend->pluck('avg_rating')->toArray()) !!},
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244,67,54,0.1)',
                    fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
                }]
            },
            options: { ...chartDefaults, plugins: { legend: { display: false } } }
        });
    }

    const satEl = document.getElementById('satisfactionChart');
    if (satEl && typeof Chart !== 'undefined') {
        new Chart(satEl, {
            type: 'bar',
            data: {
                labels: {!! json_encode($satisfactionByCategory->pluck('category')->toArray()) !!},
                datasets: [{
                    label: 'Satisfaction %',
                    data: {!! json_encode($satisfactionByCategory->pluck('avg_rating')->toArray()) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 8
                }]
            },
            options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 5 } } }
        });
    }
});
</script>

@endsection
