@extends('admin.layouts.admin')

@section('title', 'TripWise — Evaluation Review')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <span>Evaluation Review</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Evaluation Review</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Review and approve submitted evaluations. Ensure quality and professionalism before finalizing.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-hourglass-half"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending_reviews'] }}</h3>
            <p>Pending Reviews</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['approved'] }}</h3>
            <p>Approved</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['rejected'] }}</h3>
            <p>Rejected</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.evaluation.review') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search evaluations..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="status" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.evaluation.review') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Evaluations Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Submitted Evaluations</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $evaluations->firstItem() ?? 0 }} to {{ $evaluations->lastItem() ?? 0 }} of {{ $evaluations->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Evaluation ID</th>
                    <th>Evaluator</th>
                    <th>Driver Evaluated</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                    <th>Reviewed By</th>
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
                            <strong style="color:{{ $evaluation->overall_score >= 4 ? '#10b981' : ($evaluation->overall_score >= 3 ? '#f59e0b' : '#ef4444') }};">{{ number_format($evaluation->overall_score, 2) }}</strong>
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
                    <td>{{ $evaluation->created_at->format('M d, Y') }}</td>
                    <td>{{ $evaluation->reviewer->name ?? '-' }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="viewEvaluation({{ $evaluation->id }})"><i class="fas fa-eye"></i></button>
                        @if($evaluation->status === 'submitted')
                            <button class="icon-btn" title="Approve" onclick="approveEvaluation({{ $evaluation->id }})" style="color:var(--success);"><i class="fas fa-check"></i></button>
                            <button class="icon-btn" title="Reject" onclick="rejectEvaluation({{ $evaluation->id }})" style="color:var(--danger);"><i class="fas fa-times"></i></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No evaluations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $evaluations->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Approval Status Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="approvalStatusChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Monthly Evaluation Review</h3>
        <div class="chart-wrapper">
            <canvas id="monthlyReviewChart"></canvas>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--success);margin:0;"><i class="fas fa-check-circle"></i> Approve Evaluation</h2>
            <button class="icon-btn" onclick="closeModal('approveModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Admin Remarks (Optional)</label>
                <textarea name="admin_remarks" rows="3" placeholder="Add remarks..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('approveModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:var(--success);"><i class="fas fa-check"></i> Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--danger);margin:0;"><i class="fas fa-times-circle"></i> Reject Evaluation</h2>
            <button class="icon-btn" onclick="closeModal('rejectModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Admin Remarks (Required)</label>
                <textarea name="admin_remarks" rows="3" placeholder="Provide reason for rejection..." required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:var(--danger);"><i class="fas fa-times"></i> Reject</button>
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
    window.location.href = "{{ route('admin.evaluation.driver-evaluation') }}/" + id;
}
function approveEvaluation(id) {
    document.getElementById('approveForm').action = "{{ route('admin.evaluation.review') }}/" + id + "/approve";
    openModal('approveModal');
}
function rejectEvaluation(id) {
    document.getElementById('rejectForm').action = "{{ route('admin.evaluation.review') }}/" + id + "/reject";
    openModal('rejectModal');
}

document.addEventListener('DOMContentLoaded', function() {
    const approvalCtx = document.getElementById('approvalStatusChart');
    if (approvalCtx) {
        new Chart(approvalCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Rejected', 'Pending'],
                datasets: [{
                    data: [{{ $stats['approved'] }}, {{ $stats['rejected'] }}, {{ $stats['pending_reviews'] }}],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    const monthlyCtx = document.getElementById('monthlyReviewChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Reviews',
                    data: [12, 19, 8, 15, 22, 10],
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
