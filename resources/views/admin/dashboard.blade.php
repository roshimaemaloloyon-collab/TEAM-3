@extends('admin.layouts.admin')

@section('title', 'TripWise — Admin Dashboard')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <span>Dashboard</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Dashboard Overview</h1>
        <p>Welcome back, Admin. Here's what's happening across your TNVS agency today.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Driver</a>
    </div>
</div>

<!-- SECTION 1 — SUMMARY CARDS -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-car"></i></div>
        <div class="card-info">
            <h3>{{ $totalDrivers ?? 0 }}</h3>
            <p>Total Drivers</p>
            <small class="text-muted">Registered in system</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $activeDrivers ?? 0 }}</h3>
            <p>Active Drivers</p>
            <small class="text-muted">Currently operational</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $inactiveDrivers ?? 0 }}</h3>
            <p>Inactive Drivers</p>
            <small class="text-muted">Not currently active</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-user-clock"></i></div>
        <div class="card-info">
            <h3>{{ $driversUnderReview ?? 0 }}</h3>
            <p>Drivers Under Review</p>
            <small class="text-muted">Pending approval</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon teal"><i class="fas fa-star"></i></div>
        <div class="card-info">
            <h3>{{ $avgPerformanceScore ?? '0.00' }}/5</h3>
            <p>Average Performance Score</p>
            <small class="text-muted">Agency-wide average</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon gold"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>{{ $totalPendingPeerEvaluations ?? 0 }}</h3>
            <p>Pending Peer Evaluations</p>
            <small class="text-muted">Awaiting review</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>{{ $upcomingTrainings ?? 0 }}</h3>
            <p>Upcoming Trainings</p>
            <small class="text-muted">Scheduled sessions</small>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-graduation-cap"></i></div>
        <div class="card-info">
            <h3>{{ $learningCompletionRate ?? '0.0' }}%</h3>
            <p>Learning Completion Rate</p>
            <small class="text-muted">Assigned modules</small>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <select id="filterMonth">
        <option value="">This Month</option>
        <option value="last">Last Month</option>
    </select>
    <select id="filterQuarter">
        <option value="">This Quarter</option>
        <option value="last">Last Quarter</option>
    </select>
    <select id="filterYear">
        <option value="2026">2026</option>
        <option value="2025">2025</option>
    </select>
    <select id="filterBranch">
        <option value="all">All Branches</option>
        <option value="north">North Branch</option>
        <option value="south">South Branch</option>
        <option value="east">East Branch</option>
        <option value="west">West Branch</option>
    </select>
</div>

<!-- SECTION 2 — PERFORMANCE OVERVIEW -->
<div class="charts-grid" style="margin-bottom: 2rem;">
    <div class="chart-card" style="grid-column: 1 / -1;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-chart-line"></i> Performance Trend</h3>
            <div style="display:flex; gap:2rem; align-items:center; flex-wrap:wrap;">
                <div style="text-align:center;">
                    <small class="text-muted">Overall Performance Score</small>
                    <div style="font-size:1.5rem; font-weight:700; color:var(--primary);">{{ $overallPerformanceScore ?? '0.00' }}/5</div>
                </div>
                <div style="text-align:center;">
                    <small class="text-muted">KPI Achievement Rate</small>
                    <div style="font-size:1.5rem; font-weight:700; color:var(--success);">{{ $kpiAchievementRate ?? '0.0' }}%</div>
                </div>
                <a href="{{ route('admin.performance.drivers') }}" class="btn btn-primary">View Performance Management</a>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>
</div>

<!-- Lower Sections -->
<div class="section-grid">
    <!-- SECTION 3 — TOP PERFORMING DRIVERS -->
    <div class="table-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-trophy"></i> Top Performing Drivers</h3>
            <a href="{{ route('admin.performance.reports') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View Full Ranking</a>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Driver Name</th>
                        <th>Performance Score</th>
                        <th>Competency Score</th>
                        <th>Safety Score</th>
                        <th>Overall Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPerformers ?? [] as $index => $perf)
                        @php
                            $driver = $perf['driver'] ?? null;
                            $competencyScore = $perf['competency_score'] ?? '0.00';
                            $safetyScore = $perf['safety_score'] ?? 0;
                            $overallRating = $perf['overall_rating'] ?? 0;
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <span class="rank-badge rank-{{ $index + 1 <= 3 ? $index + 1 : 'other' }}">{{ $index + 1 }}</span>
                                    <strong>{{ $driver->name ?? 'N/A' }}</strong>
                                </div>
                            </td>
                            <td>{{ number_format($perf['performance_score'] ?? 0, 2) }}</td>
                            <td>{{ $competencyScore }}</td>
                            <td>{{ number_format($safetyScore, 2) }}</td>
                            <td>
                                @php
                                    $rating = $overallRating;
                                    if ($rating >= 4.5) $badgeClass = 'badge-warning';
                                    elseif ($rating >= 4.0) $badgeClass = 'badge-info';
                                    elseif ($rating >= 3.5) $badgeClass = 'badge-purple';
                                    else $badgeClass = 'status-success';
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">{{ number_format($rating, 1) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--text-muted); padding:2rem;">No Records Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 4 — KPI OVERVIEW -->
    <div class="table-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-bullseye"></i> KPI Overview</h3>
            <a href="{{ route('admin.performance.kpi') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View KPI Monitoring</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:1.25rem;">
            @foreach(['safety_score' => 'Safety Score', 'attendance_rate' => 'Attendance Rate', 'trip_completion_rate' => 'Trip Completion Rate', 'customer_rating' => 'Customer Rating'] as $key => $label)
                @php
                    $value = $kpiStats[$key] ?? '0%';
                    $numeric = (float) str_replace('%', '', $value);
                @endphp
                <div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem;">
                        <span style="font-weight:600; font-size:0.9rem;">{{ $label }}</span>
                        <span style="font-weight:700; color:var(--primary);">{{ $value }}</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $numeric }}%;"></div>
                    </div>
                    <small class="text-muted">
                        @if($numeric >= 80)
                            <i class="fas fa-arrow-up" style="color:var(--success);"></i> On Track
                        @elseif($numeric >= 50)
                            <i class="fas fa-minus" style="color:var(--warning);"></i> Needs Attention
                        @else
                            <i class="fas fa-arrow-down" style="color:var(--danger);"></i> Below Target
                        @endif
                    </small>
                </div>
            @endforeach
        </div>
    </div>

    <!-- SECTION 5 — TRAINING & LEARNING -->
    <div class="section-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-calendar-alt"></i> Training</h3>
            <a href="{{ route('admin.training.schedule') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage Trainings</a>
        </div>
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem; text-align:center;">
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <div style="font-size:1.5rem; font-weight:700; color:var(--info);">{{ $upcomingTrainings ?? 0 }}</div>
                <small class="text-muted">Upcoming Trainings</small>
            </div>
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <div style="font-size:1.5rem; font-weight:700; color:var(--warning);">{{ $ongoingTrainings ?? 0 }}</div>
                <small class="text-muted">Ongoing Trainings</small>
            </div>
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <div style="font-size:1.5rem; font-weight:700; color:var(--success);">{{ $completedTrainings ?? 0 }}</div>
                <small class="text-muted">Completed Trainings</small>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-book-open"></i> Learning</h3>
            <a href="{{ route('admin.learning.modules') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Open Learning Management</a>
        </div>
        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:1rem; text-align:center; margin-bottom:1rem;">
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <div style="font-size:1.5rem; font-weight:700; color:var(--primary);">{{ $assignedLearningModules ?? 0 }}</div>
                <small class="text-muted">Assigned Learning Modules</small>
            </div>
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <div style="font-size:1.5rem; font-weight:700; color:var(--success);">{{ $completedLearningModules ?? 0 }}</div>
                <small class="text-muted">Completed Modules</small>
            </div>
        </div>
        <div style="background:var(--beige); border-radius:0.75rem; padding:1rem; text-align:center; margin-bottom:0.75rem;">
            <div style="font-size:1.5rem; font-weight:700; color:var(--primary);">{{ $learningCompletionRate ?? '0.0' }}%</div>
            <small class="text-muted">Learning Completion Rate</small>
        </div>
        <div style="background:var(--beige); border-radius:0.75rem; padding:1rem; text-align:center;">
            <div style="font-size:1.5rem; font-weight:700; color:var(--warning);">{{ $certificatesEarned ?? 0 }}</div>
            <small class="text-muted">Certificates Earned</small>
        </div>
    </div>

    <!-- SECTION 6 — COMPETENCY OVERVIEW -->
    <div class="table-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-brain"></i> Competency Overview</h3>
            <a href="{{ route('admin.competency.assessments') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View Competency Management</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:1rem; margin-bottom:1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <span style="font-weight:600;">Average Competency Score</span>
                <span style="font-weight:700; color:var(--primary); font-size:1.1rem;">{{ $avgCompetencyScore ?? '0.00' }}/100</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <span style="font-weight:600;">Drivers with Skill Gaps</span>
                <span style="font-weight:700; color:var(--danger);">{{ $driversWithSkillGaps ?? 0 }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; background:var(--beige); border-radius:0.75rem; padding:1rem;">
                <span style="font-weight:600;">Most Improved Competency</span>
                <span style="font-weight:700; color:var(--success);">{{ $mostImprovedCompetency->max_score ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="chart-wrapper" style="height:220px;">
            <canvas id="competencyChart"></canvas>
        </div>
    </div>

    <!-- SECTION 7 — SOCIAL RECOGNITION -->
    <div class="section-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-trophy"></i> Social Recognition</h3>
            <a href="{{ route('admin.recognition.awards') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View Recognition</a>
        </div>
        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:1rem;">
            <div style="background:linear-gradient(135deg, #FFD700, #FFA500); border-radius:0.75rem; padding:1rem; color:#fff; text-align:center;">
                <i class="fas fa-crown" style="font-size:1.5rem; margin-bottom:0.5rem;"></i>
                <div style="font-weight:700;">Driver of the Month</div>
                <small>{{ optional(optional($topPerformers ?? collect())->first())['driver']->name ?? 'No Records' }}</small>
            </div>
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem; text-align:center;">
                <i class="fas fa-medal" style="font-size:1.5rem; margin-bottom:0.5rem; color:var(--primary);"></i>
                <div style="font-weight:700;">Top Performer</div>
                <small>{{ optional(optional($topPerformers ?? collect())->first())['driver']->name ?? 'No Records' }}</small>
            </div>
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem; text-align:center;">
                <i class="fas fa-shield-alt" style="font-size:1.5rem; margin-bottom:0.5rem; color:var(--success);"></i>
                <div style="font-weight:700;">Safest Driver</div>
                <small>{{ optional(optional($topPerformers ?? collect())->first())['driver']->name ?? 'No Records' }}</small>
            </div>
            <div style="background:var(--beige); border-radius:0.75rem; padding:1rem; text-align:center;">
                <i class="fas fa-award" style="font-size:1.5rem; margin-bottom:0.5rem; color:var(--warning);"></i>
                <div style="font-weight:700;">Awards This Month</div>
                <small>{{ $certificatesEarned ?? 0 }}</small>
            </div>
        </div>
    </div>

    <!-- SECTION 8 — SUCCESSION PLANNING -->
    <div class="section-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-rocket"></i> Succession Planning</h3>
            <a href="{{ route('admin.succession.leadership') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Open Succession Planning</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:0.75rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; background:var(--beige); border-radius:0.75rem; padding:0.85rem 1rem;">
                <span style="font-weight:600; font-size:0.9rem;"><i class="fas fa-users" style="color:var(--primary); margin-right:0.5rem;"></i> Leadership Candidates</span>
                <span style="font-weight:700; color:var(--primary);">{{ $leadershipCandidates ?? 0 }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; background:var(--beige); border-radius:0.75rem; padding:0.85rem 1rem;">
                <span style="font-weight:600; font-size:0.9rem;"><i class="fas fa-user-tie" style="color:var(--success); margin-right:0.5rem;"></i> Promotion Ready Drivers</span>
                <span style="font-weight:700; color:var(--success);">{{ $promotionReadyDrivers ?? 0 }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; background:var(--beige); border-radius:0.75rem; padding:0.85rem 1rem;">
                <span style="font-weight:600; font-size:0.9rem;"><i class="fas fa-tasks" style="color:var(--warning); margin-right:0.5rem;"></i> Active Development Plans</span>
                <span style="font-weight:700; color:var(--warning);">{{ $activeDevelopmentPlans ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 9 — RECENT NOTIFICATIONS -->
<div class="section-grid">
    <div class="section-card" style="grid-column: 1 / -1;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 style="margin-bottom:0;"><i class="fas fa-bell"></i> Recent Notifications</h3>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View All Notifications</a>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Message</th>
                        <th style="width:180px;">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications ?? [] as $notification)
                        <tr>
                            <td>
                                @php
                                    $iconMap = [
                                        'training' => 'fa-calendar-alt',
                                        'performance' => 'fa-chart-line',
                                        'system' => 'fa-cog',
                                        'announcement' => 'fa-bullhorn',
                                        'alert' => 'fa-exclamation-triangle',
                                        'reminder' => 'fa-clock',
                                    ];
                                    $icon = $iconMap[$notification->type] ?? 'fa-bell';
                                @endphp
                                <i class="fas {{ $icon }}" style="color:var(--primary);"></i>
                            </td>
                            <td>
                                <div style="font-weight:600; font-size:0.9rem;">{{ $notification->title }}</div>
                                <small class="text-muted">{{ $notification->message }}</small>
                            </td>
                            <td><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; color:var(--text-muted); padding:2rem;">No Data Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SECTION 10 — QUICK ACTIONS -->
<div class="quick-actions">
    <a href="{{ route('admin.drivers.index') }}" class="action-btn">
        <i class="fas fa-user-plus"></i>
        <span>Add Driver</span>
    </a>
    <a href="{{ route('admin.training.schedule') }}" class="action-btn">
        <i class="fas fa-calendar-plus"></i>
        <span>Create Training</span>
    </a>
    <a href="{{ route('admin.learning.modules') }}" class="action-btn">
        <i class="fas fa-book-open"></i>
        <span>Assign Learning Module</span>
    </a>
    <a href="{{ route('admin.performance.drivers') }}" class="action-btn">
        <i class="fas fa-clipboard-check"></i>
        <span>Review Performance</span>
    </a>
    <a href="{{ route('admin.evaluation.review') }}" class="action-btn">
        <i class="fas fa-users"></i>
        <span>Review Peer Evaluation</span>
    </a>
    <a href="{{ route('admin.reports.driver-reports') }}" class="action-btn">
        <i class="fas fa-file-alt"></i>
        <span>Generate Report</span>
    </a>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawLabels = @json($performanceTrend->pluck('month')->filter());
    const rawData = @json($performanceTrend->pluck('avg_score')->filter()->map(fn($v) => round($v, 2)));

    const labels = rawLabels.length ? rawLabels : [];
    const data = rawData.length ? rawData : [];

    const perfCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(perfCtx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['No Data Available'],
            datasets: [{
                label: 'Monthly Average Performance Score',
                data: data.length ? data : [0],
                borderColor: '#F44336',
                backgroundColor: 'rgba(244, 67, 54, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#F44336'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 5.0, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    const compCtx = document.getElementById('competencyChart').getContext('2d');
    new Chart(compCtx, {
        type: 'bar',
        data: {
            labels: ['Safety', 'Customer Service', 'Navigation', 'Vehicle Maint.', 'Time Mgmt'],
            datasets: [{
                label: 'Current Score',
                data: [0, 0, 0, 0, 0],
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endsection
