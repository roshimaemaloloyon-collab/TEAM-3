@extends('admin.layouts.admin')

@section('title', 'TripWise — Development Plan')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Development Plan</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Development Plan</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Create competency improvement plans for drivers.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('planModal')"><i class="fas fa-plus"></i> Create Plan</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-clipboard-list"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Development Plans</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Completed Plans</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-pause-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['on_hold'] }}</h3>
            <p>On Hold</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_progress'] }}</h3>
            <p>Average Progress</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.competency.plans') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Development Plans Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-list"></i> Development Plans</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Development Plan</th>
                    <th>Assigned Learning</th>
                    <th>Assigned Training</th>
                    <th>Progress</th>
                    <th>Completion %</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td><strong>{{ $plan->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $plan->plan_name }}</td>
                        <td>
                            @php
                                $learning = $plan->assigned_learning_modules ?? [];
                                echo is_array($learning) ? count($learning) . ' modules' : 'N/A';
                            @endphp
                        </td>
                        <td>
                            @php
                                $trainings = $plan->assigned_trainings ?? [];
                                echo is_array($trainings) ? count($trainings) . ' trainings' : 'N/A';
                            @endphp
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div class="progress-bar" style="width:100px;height:8px;">
                                    <div class="progress-fill" style="width:{{ $plan->completion_percentage }}%;"></div>
                                </div>
                                <span style="font-size:0.85rem;font-weight:600;">{{ $plan->completion_percentage }}%</span>
                            </div>
                        </td>
                        <td><strong>{{ $plan->completion_percentage }}%</strong></td>
                        <td>
                            <span class="item-badge {{ $plan->status === 'completed' ? 'badge-success' : ($plan->status === 'active' ? 'badge-info' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-primary" title="Update Progress"><i class="fas fa-sync-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No development plans found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $plans->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Development Progress</h3>
        <div class="chart-wrapper">
            <canvas id="devProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
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

    new Chart(document.getElementById('devProgressChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($progressData->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Completion %',
                data: {!! json_encode($progressData->pluck('avg_progress')->toArray()) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('trainingCompChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($trainingData->pluck('status')->toArray()) !!},
            datasets: [{
                label: 'Plans',
                data: {!! json_encode($trainingData->pluck('total')->toArray()) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@endsection
