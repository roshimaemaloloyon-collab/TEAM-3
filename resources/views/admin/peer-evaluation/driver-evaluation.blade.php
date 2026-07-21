@extends('admin.layouts.admin')

@section('title', 'TripWise — Driver Evaluation')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <span>Driver Evaluation</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Driver Evaluation</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Evaluate fellow drivers using standardized criteria. Professionalism, Communication, Teamwork, Safety, Reliability, and Respectfulness.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-primary" onclick="openModal('addEvaluationModal')"><i class="fas fa-plus"></i> New Evaluation</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total'] }}</h3>
            <p>Total Evaluations Submitted</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-hourglass-half"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Pending Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Completed Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-star"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['avg_rating'] ?? 0, 2) }}</h3>
            <p>Average Peer Rating</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.evaluation.driver-evaluation') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search evaluations..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.evaluation.driver-evaluation') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Evaluations Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Evaluations</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $evaluations->firstItem() ?? 0 }} to {{ $evaluations->lastItem() ?? 0 }} of {{ $evaluations->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Evaluation ID</th>
                    <th>Evaluator</th>
                    <th>Evaluated Driver</th>
                    <th>Overall Score</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $evaluation)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#PE-{{ str_pad($evaluation->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $evaluation->evaluator->name ?? 'N/A' }}</strong></td>
                    <td><strong>{{ $evaluation->evaluatedDriver->name ?? 'N/A' }}</strong></td>
                    <td>
                        @if($evaluation->overall_score)
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div style="width:60px;height:8px;background:var(--beige-dark);border-radius:999px;overflow:hidden;">
                                    <div style="width:{{ ($evaluation->overall_score / 5) * 100 }}%;height:100%;background:{{ $evaluation->overall_score >= 4 ? '#10b981' : ($evaluation->overall_score >= 3 ? '#f59e0b' : '#ef4444') }};border-radius:999px;"></div>
                                </div>
                                <strong style="color:{{ $evaluation->overall_score >= 4 ? '#10b981' : ($evaluation->overall_score >= 3 ? '#f59e0b' : '#ef4444') }};">{{ number_format($evaluation->overall_score, 2) }}</strong>
                            </div>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusClass = match($evaluation->status) {
                                'approved' => 'status-success',
                                'rejected' => 'status-inactive',
                                'submitted' => 'status-pending',
                                'under_review' => 'status-review',
                                default => 'status-pending',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $evaluation->status)) }}</span>
                    </td>
                    <td>{{ $evaluation->evaluation_date->format('M d, Y') }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="viewEvaluation({{ $evaluation->id }})"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Edit" onclick="editEvaluation({{ $evaluation->id }})"><i class="fas fa-edit"></i></button>
                        <button class="icon-btn" title="Delete" onclick="deleteEvaluation({{ $evaluation->id }})" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No evaluations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $evaluations->links() }}
    </div>
</div>

<!-- Add Evaluation Modal -->
<div id="addEvaluationModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:700px;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-plus-circle"></i> New Peer Evaluation</h2>
            <button class="icon-btn" onclick="closeModal('addEvaluationModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.evaluation.driver-evaluation.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver to Evaluate</label>
                    <select name="evaluated_driver_id" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="">-- Select Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Evaluation Date</label>
                    <input type="date" name="evaluation_date" required value="{{ date('Y-m-d') }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Professionalism (1-5)</label>
                        <input type="number" name="category_scores[professionalism]" min="1" max="5" step="0.1" value="5" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Communication (1-5)</label>
                        <input type="number" name="category_scores[communication]" min="1" max="5" step="0.1" value="5" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Teamwork (1-5)</label>
                        <input type="number" name="category_scores[teamwork]" min="1" max="5" step="0.1" value="5" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Safety (1-5)</label>
                        <input type="number" name="category_scores[safety]" min="1" max="5" step="0.1" value="5" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Reliability (1-5)</label>
                        <input type="number" name="category_scores[reliability]" min="1" max="5" step="0.1" value="5" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Respectfulness (1-5)</label>
                        <input type="number" name="category_scores[respectfulness]" min="1" max="5" step="0.1" value="5" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Overall Score (1-5)</label>
                    <input type="number" name="overall_score" min="1" max="5" step="0.1" value="5" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Comments</label>
                    <textarea name="comments" rows="3" placeholder="Enter your comments..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Suggestions</label>
                    <textarea name="suggestions" rows="3" placeholder="Enter suggestions for improvement..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="is_anonymous" style="font-size:0.9rem;cursor:pointer;">Submit anonymously</label>
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addEvaluationModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Evaluation</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function viewEvaluation(id) {
    window.location.href = "{{ url('admin/evaluation/driver-evaluation') }}/" + id;
}
function editEvaluation(id) {
    window.location.href = "{{ url('admin/evaluation/driver-evaluation') }}/" + id + "/edit";
}
function deleteEvaluation(id) {
    if (confirm('Are you sure you want to delete this evaluation?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ url('admin/evaluation/driver-evaluation') }}/" + id;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
