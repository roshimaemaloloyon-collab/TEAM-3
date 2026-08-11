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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
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
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
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

    new Chart(document.getElementById('evaluationTrendChart'), {
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

    new Chart(document.getElementById('satisfactionChart'), {
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
});
</script>
@endsection
