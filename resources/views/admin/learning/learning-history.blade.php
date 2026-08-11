@extends('admin.layouts.admin')

@section('title', 'TripWise — Learning History')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Learning History</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Learning History</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Maintain complete historical learning records.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-database"></i></div>
        <div class="card-info">
            <h3>{{ $stats['historical_records'] }}</h3>
            <p>Historical Learning Records</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed_courses'] }}</h3>
            <p>Completed Courses</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-certificate"></i></div>
        <div class="card-info">
            <h3>{{ $stats['certificates_earned'] }}</h3>
            <p>Certificates Earned</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['assessments_taken'] }}</h3>
            <p>Assessments Taken</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.learning.history') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Record Type</label>
            <select name="record_type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Types</option>
                <option value="assignment" {{ request('record_type') === 'assignment' ? 'selected' : '' }}>Assignment</option>
                <option value="completion" {{ request('record_type') === 'completion' ? 'selected' : '' }}>Completion</option>
                <option value="assessment" {{ request('record_type') === 'assessment' ? 'selected' : '' }}>Assessment</option>
                <option value="certificate" {{ request('record_type') === 'certificate' ? 'selected' : '' }}>Certificate</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- History Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-history"></i> Learning History</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Learning Module</th>
                    <th>Completion Date</th>
                    <th>Quiz Score</th>
                    <th>Certificate Status</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td><strong>{{ $history->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $history->module->title ?? 'N/A' }}</td>
                        <td>{{ $history->recorded_at ? \Carbon\Carbon::parse($history->recorded_at)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            @php
                                $assessment = \App\Models\LearningAssessment::where('driver_id', $history->driver_id)->where('learning_module_id', $history->learning_module_id)->first();
                                echo $assessment->score ?? 'N/A';
                            @endphp
                        </td>
                        <td>
                            @php
                                $cert = \App\Models\Certificate::where('driver_id', $history->driver_id)->first();
                                echo $cert ? 'Issued' : 'Not Issued';
                            @endphp
                        </td>
                        <td>
                            <span class="item-badge {{ $history->record_type === 'completion' ? 'badge-success' : ($history->record_type === 'assessment' ? 'badge-info' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $history->record_type)) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Archive"><i class="fas fa-archive"></i></button>
                                <button class="btn btn-sm btn-primary" title="Restore"><i class="fas fa-undo"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No history records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $histories->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Learning Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="learningTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Historical Progress</h3>
        <div class="chart-wrapper">
            <canvas id="historicalProgressChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function exportReport(format) { showToast('Exporting ' + format.toUpperCase() + ' report...'); }
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

    new Chart(document.getElementById('learningTimelineChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($timelineData->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Courses Completed',
                data: {!! json_encode($timelineData->pluck('total')->toArray()) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('historicalProgressChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trendData->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Completion %',
                data: {!! json_encode($trendData->pluck('total')->toArray()) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#3b82f6'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });
});
</script>
@endsection
