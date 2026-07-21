@extends('admin.layouts.admin')

@section('title', 'TripWise — Feedback Summary')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <span>Feedback Summary</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Feedback Summary</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Summarize peer feedback for each driver. Identify strengths, improvement areas, and recommendations.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addFeedbackModal')"><i class="fas fa-plus"></i> Generate Feedback</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-thumbs-up"></i></div>
        <div class="card-info">
            <h3>{{ $stats['positive_feedback'] }}</h3>
            <p>Positive Feedback Count</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-lightbulb"></i></div>
        <div class="card-info">
            <h3>{{ $stats['improvement_opportunities'] }}</h3>
            <p>Improvement Opportunities</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-star"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['average_peer_rating'] ?? 0, 2) }}</h3>
            <p>Average Peer Rating</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.evaluation.feedback-summary') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search drivers..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="driver_id" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:180px;">
            <option value="">All Drivers</option>
            @foreach($drivers as $driver)
                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
            @endforeach
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.evaluation.feedback-summary') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Feedback Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Feedback Summary by Driver</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $summaries->firstItem() ?? 0 }} to {{ $summaries->lastItem() ?? 0 }} of {{ $summaries->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Positive Feedback</th>
                    <th>Areas for Improvement</th>
                    <th>Overall Rating</th>
                    <th>Recommendation</th>
                    <th>Date</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaries as $summary)
                <tr>
                    <td><strong>Driver #{{ $summary->evaluated_driver_id }}</strong></td>
                    <td><span style="color:var(--success);font-weight:600;">{{ $summary->total_evaluations }} evaluations</span></td>
                    <td>
                        @if($summary->average_rating < 3.5)
                            <span class="status-badge status-pending">Needs Improvement</span>
                        @else
                            <span class="status-badge status-success">On Track</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color:{{ $summary->average_rating >= 4 ? '#10b981' : ($summary->average_rating >= 3 ? '#f59e0b' : '#ef4444') }};">{{ number_format($summary->average_rating ?? 0, 2) }}</strong>
                    </td>
                    <td>
                        @if($summary->average_rating >= 4)
                            <span class="status-badge status-success">Recommended</span>
                        @elseif($summary->average_rating >= 3)
                            <span class="status-badge status-warning">Monitor</span>
                        @else
                            <span class="status-badge status-pending">Action Required</span>
                        @endif
                    </td>
                    <td>{{ now()->format('M d, Y') }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View Details" onclick="viewFeedback({{ $summary->evaluated_driver_id }})"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">No feedback summaries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $summaries->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Feedback Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="feedbackDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Peer Rating Trend</h3>
        <div class="chart-wrapper">
            <canvas id="peerRatingTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- Generate Feedback Modal -->
<div id="addFeedbackModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-magic"></i> Generate Feedback Summary</h2>
            <button class="icon-btn" onclick="closeModal('addFeedbackModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.evaluation.feedback-summary') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver</label>
                    <select name="driver_id" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="">-- Select Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Period Start</label>
                        <input type="date" name="period_start" value="{{ date('Y-m-d', strtotime('-30 days')) }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Period End</label>
                        <input type="date" name="period_end" value="{{ date('Y-m-d') }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Positive Feedback Highlights</label>
                    <textarea name="positive_feedback" rows="3" placeholder="Key strengths observed..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Areas for Improvement</label>
                    <textarea name="improvement_areas" rows="3" placeholder="Areas that need development..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommendations</label>
                    <textarea name="recommendations" rows="3" placeholder="Recommended actions..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;"></textarea>
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addFeedbackModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-magic"></i> Generate</button>
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
function viewFeedback(driverId) {
    window.location.href = "{{ route('admin.evaluation.feedback-summary') }}/" + driverId;
}

document.addEventListener('DOMContentLoaded', function() {
    const feedbackCtx = document.getElementById('feedbackDistChart');
    if (feedbackCtx) {
        new Chart(feedbackCtx, {
            type: 'bar',
            data: {
                labels: ['Professionalism', 'Communication', 'Teamwork', 'Safety', 'Reliability', 'Respectfulness'],
                datasets: [{
                    label: 'Average Score',
                    data: [4.5, 4.3, 4.6, 4.8, 4.4, 4.7],
                    backgroundColor: '#F44336',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 5 }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    const trendCtx = document.getElementById('peerRatingTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Peer Rating',
                    data: [4.2, 4.3, 4.1, 4.4, 4.5, 4.6],
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
