@extends('admin.layouts.admin')

@section('title', 'TripWise — Evaluation History')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <span>Evaluation History</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Evaluation History</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Complete historical records of all peer evaluations. Track changes, audits, and timeline events.</p>
    </div>
    <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-download"></i> Export History</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-history"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total_historical'] }}</h3>
            <p>Total Historical Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-archive"></i></div>
        <div class="card-info">
            <h3>{{ $stats['archived'] }}</h3>
            <p>Archived Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-stream"></i></div>
        <div class="card-info">
            <h3>{{ $stats['timeline_entries'] }}</h3>
            <p>Evaluation Timeline</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['historical_avg_score'] ?? 0, 2) }}</h3>
            <p>Historical Average Score</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.evaluation.history') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search history..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="action" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Actions</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="submitted">Submitted</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="archived">Archived</option>
            <option value="restored">Restored</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.evaluation.history') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- History Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Evaluation History</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $history->firstItem() ?? 0 }} to {{ $history->lastItem() ?? 0 }} of {{ $history->total() }} results</span>
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
                    <th>Action</th>
                    <th>Date</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $entry)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#PE-{{ str_pad($entry->peer_evaluation_id ?? 0, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><strong>{{ $entry->peerEvaluation->evaluator->name ?? 'N/A' }}</strong></td>
                    <td><strong>{{ $entry->peerEvaluation->evaluatedDriver->name ?? 'N/A' }}</strong></td>
                    <td>
                        @if($entry->peerEvaluation && $entry->peerEvaluation->overall_score)
                            <strong style="color:{{ $entry->peerEvaluation->overall_score >= 4 ? '#10b981' : ($entry->peerEvaluation->overall_score >= 3 ? '#f59e0b' : '#ef4444') }};">{{ number_format($entry->peerEvaluation->overall_score, 2) }}</strong>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $status = $entry->peerEvaluation->status ?? 'unknown';
                            $statusClass = match($status) {
                                'approved' => 'status-success',
                                'rejected' => 'status-inactive',
                                'submitted' => 'status-pending',
                                'under_review' => 'status-review',
                                default => 'status-pending',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    </td>
                    <td>
                        @php
                            $actionColors = [
                                'created' => 'status-review',
                                'updated' => 'status-warning',
                                'submitted' => 'status-pending',
                                'approved' => 'status-success',
                                'rejected' => 'status-inactive',
                                'archived' => 'status-pending',
                                'restored' => 'status-success',
                            ];
                        @endphp
                        <span class="status-badge {{ $actionColors[$entry->action] ?? 'status-pending' }}">{{ ucfirst($entry->action) }}</span>
                    </td>
                    <td>{{ $entry->performed_at->format('M d, Y H:i') }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="viewHistory({{ $entry->id }})"><i class="fas fa-eye"></i></button>
                        @if($entry->action === 'archived')
                            <button class="icon-btn" title="Restore" onclick="restoreEvaluation({{ $entry->peer_evaluation_id }})" style="color:var(--success);"><i class="fas fa-undo"></i></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">No history records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $history->links() }}
    </div>
</div>

<!-- Timeline Chart -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Evaluation History Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="historyTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Historical Performance Trend</h3>
        <div class="chart-wrapper">
            <canvas id="historicalTrendChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function viewHistory(id) {
    showToast('Viewing history entry #' + id);
}
function restoreEvaluation(id) {
    if (confirm('Are you sure you want to restore this evaluation?')) {
        showToast('Evaluation restored successfully.');
    }
}
function exportReport(format) {
    showToast('Exporting history as ' + format.toUpperCase() + '...');
}

document.addEventListener('DOMContentLoaded', function() {
    const timelineCtx = document.getElementById('historyTimelineChart');
    if (timelineCtx) {
        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Evaluations',
                    data: [15, 22, 18, 25, 30, 28],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
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

    const trendCtx = document.getElementById('historicalTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025', 'Q1 2026'],
                datasets: [{
                    label: 'Average Score',
                    data: [4.2, 4.3, 4.1, 4.4, 4.5],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: false, min: 3.5, max: 5 }
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
