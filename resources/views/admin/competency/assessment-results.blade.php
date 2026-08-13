@extends('admin.layouts.admin')

@section('title', 'TripWise — Assessment Results')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Assessment Results</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Assessment Results</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Display competency assessment results and identify areas for improvement.</p>
    </div>
    <a href="{{ route('admin.competency.reports.export', ['format' => 'pdf']) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print"></i> Print Assessment</a>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3>{{ $stats['high_competency'] }}</h3>
            <p>High Competency Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['needs_improvement'] }}</h3>
            <p>Drivers Requiring Improvement</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ $stats['avg_score'] }}</h3>
            <p>Average Assessment Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-puzzle-piece"></i></div>
        <div class="card-info">
            <h3>{{ $stats['skill_gaps'] }}</h3>
            <p>Skill Gap Count</p>
        </div>
    </div>
</div>

<!-- Search / Filter Bar -->
<div class="filter-bar" style="background:var(--white);padding:1rem;border-radius:0.75rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border:1px solid var(--border);">
    <form action="{{ route('admin.competency.results') }}" method="GET" style="display:flex;gap:0.75rem;flex:1;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:0.25rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;">
        </div>
        <div style="min-width:150px;">
            <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:0.25rem;">Status</label>
            <select name="status" style="width:100%;">
                <option value="">All Statuses</option>
                <option value="assessed" {{ request('status') === 'assessed' ? 'selected' : '' }}>Assessed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
            </select>
        </div>
        <div style="display:flex;align-items:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Assessment Results Table -->
<div style="background:var(--white);border-radius:0.75rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);border:1px solid var(--border);padding:1.25rem;margin-top:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="font-size:1.1rem;color:var(--primary);margin:0;"><i class="fas fa-clipboard-check"></i> Assessment Results</h3>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Competency Score</th>
                    <th>Strengths</th>
                    <th>Weaknesses</th>
                    <th>Skill Gaps</th>
                    <th>Assessment Date</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr>
                        <td><strong>{{ $result->driver_name }}</strong></td>
                        <td><strong>{{ number_format($result->score, 2) }}</strong></td>
                        <td>
                            @php
                                $scoreVal = $result->score ?? 80;
                                $compName = $result->competency->name ?? 'Safety';
                                if ($scoreVal >= 85) {
                                    $strengthsStr = $compName . ', Defensive Driving';
                                } elseif ($scoreVal >= 75) {
                                    $strengthsStr = 'Defensive Driving';
                                } else {
                                    $strengthsStr = 'Basic Vehicle Operation';
                                }
                            @endphp
                            <span style="color:var(--success);font-weight:600;font-size:0.85rem;">{{ $strengthsStr }}</span>
                        </td>
                        <td>
                            @php
                                if ($scoreVal >= 85) {
                                    $weaknessStr = 'None Identified';
                                } elseif ($scoreVal >= 70) {
                                    $weaknessStr = 'Time Management';
                                } else {
                                    $weaknessStr = $compName . ', GPS Navigation';
                                }
                            @endphp
                            <span style="color:{{ $scoreVal < 75 ? 'var(--danger)' : 'var(--text-muted)' }};font-size:0.85rem;">{{ $weaknessStr }}</span>
                        </td>
                        <td>
                            @php
                                $targetScore = $result->competency->target_score ?? 85;
                                $gapVal = max(0, round($targetScore - $scoreVal, 1));
                            @endphp
                            @if($gapVal > 0)
                                <span class="item-badge badge-warning">-{{ $gapVal }}% Gap</span>
                            @else
                                <span class="item-badge badge-success">No Gap</span>
                            @endif
                        </td>
                        <td>{{ $result->assessed_at ? \Carbon\Carbon::parse($result->assessed_at)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="item-badge {{ $result->status === 'assessed' ? 'badge-success' : ($result->status === 'pending' ? 'badge-warning' : 'badge-info') }}">
                                {{ ucfirst($result->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <a href="{{ route('admin.competency.assessments.driver.pdf', $result->driver_id ?? 1) }}" target="_blank" class="btn btn-sm btn-secondary" title="View Assessment Document"><i class="fas fa-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-primary edit-result-btn" title="Edit Assessment" data-id="{{ $result->id }}" data-name="{{ $result->driver_name }}" data-score="{{ $result->score ?? 85 }}" data-status="{{ $result->status }}"><i class="fas fa-edit"></i></button>
                                <a href="{{ route('admin.competency.assessments.driver.pdf', $result->driver_id ?? 1) }}" target="_blank" class="btn btn-sm btn-secondary" title="Print Driver PDF"><i class="fas fa-print"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessment results found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $results->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Skill Gap Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="skillGapChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Competency Trend</h3>
        <div class="chart-wrapper">
            <canvas id="compTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- Edit Result Modal -->
<div id="editResultModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form id="editResultForm" method="POST">
            @csrf
            @method('PUT')
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Assessment Result</h2>
                <button type="button" onclick="closeModal('editResultModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editResultDriverName" readonly style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:#f1f5f9;color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Score (0 - 100%)</label>
                    <input type="number" name="score" id="editResultScore" min="0" max="100" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" id="editResultStatus" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="assessed">Assessed</option>
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                    </select>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editResultModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update Result</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.edit-result-btn');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-name');
    const score = btn.getAttribute('data-score');
    const status = btn.getAttribute('data-status');

    const modal = document.getElementById('editResultModal');
    if (modal) {
        document.getElementById('editResultForm').action = '/admin/competency/assessments/' + id;
        document.getElementById('editResultDriverName').value = name;
        document.getElementById('editResultScore').value = score;
        document.getElementById('editResultStatus').value = status;
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('skillGapChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($skillGapData->pluck('competency.name')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($skillGapData->pluck('avg_score')->toArray()) !!},
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#8b5cf6', '#f97316', '#14b8a6', '#f43f5e', '#84cc16', '#06b6d4']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('compTrendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trendData->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($trendData->pluck('avg_score')->toArray()) !!},
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });
});
</script>

@endsection
