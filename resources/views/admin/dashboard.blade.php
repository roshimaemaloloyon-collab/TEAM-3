@extends('admin.layouts.admin')

@section('title', 'TripWise — Admin Dashboard')

@section('content')
<style>
    /* Ultra-Minimalist High-Density Dashboard CSS */
    .glance-bar {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        margin-bottom: 1.25rem;
    }
    .glance-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        color: var(--text-dark);
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .glance-item:hover { opacity: 0.8; }
    .glance-item i { font-size: 0.85rem; }
    .glance-divider { width: 1px; height: 18px; background: var(--border); }
    
    .mini-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 1024px) { .mini-grid { grid-template-columns: 1fr; } }

    .minimal-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .minimal-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.85rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
    }
    .minimal-card-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .compact-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .compact-table th { text-align: left; padding: 0.4rem 0.5rem; color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; border-bottom: 1px solid var(--border); }
    .compact-table td { padding: 0.5rem; border-bottom: 1px dashed var(--border); color: var(--text-dark); }

    .kpi-row { margin-bottom: 0.65rem; }
    .kpi-row-header { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.2rem; }
    .thin-progress-bg { width: 100%; height: 5px; background: var(--beige-dark); border-radius: 3px; overflow: hidden; }
    .thin-progress-fill { height: 100%; border-radius: 3px; }

    .bottom-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) { .bottom-row { grid-template-columns: 1fr; } }
</style>

<!-- SECTION 1: "At a Glance" Summary Bar (Top Row) -->
<div class="glance-bar">
    <a href="{{ route('admin.drivers.index') }}" class="glance-item">
        <i class="fas fa-car" style="color:#3b82f6;"></i>
        <span>Total: <strong>{{ $totalDrivers ?? 95 }}</strong></span>
    </a>
    <div class="glance-divider"></div>
    <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" class="glance-item">
        <i class="fas fa-check-circle" style="color:#10b981;"></i>
        <span>Active: <strong>{{ $activeDrivers ?? 37 }}</strong></span>
    </a>
    <div class="glance-divider"></div>
    <a href="{{ route('admin.drivers.index', ['status' => 'inactive']) }}" class="glance-item">
        <i class="fas fa-times-circle" style="color:#f59e0b;"></i>
        <span>Inactive: <strong>{{ $inactiveDrivers ?? 24 }}</strong></span>
    </a>
    <div class="glance-divider"></div>
    <a href="{{ route('admin.drivers.index', ['status' => 'under_review']) }}" class="glance-item">
        <i class="fas fa-clock" style="color:#8b5cf6;"></i>
        <span>Review: <strong>{{ $driversUnderReview ?? 0 }}</strong></span>
    </a>
    <div class="glance-divider"></div>
    <a href="{{ route('admin.evaluation.review') }}" class="glance-item">
        <i class="fas fa-clipboard-list" style="color:#ec4899;"></i>
        <span>Pending Peer: <strong>{{ $totalPendingPeerEvaluations ?? 17 }}</strong></span>
    </a>
    <div class="glance-divider"></div>
    <a href="{{ route('admin.training.schedule') }}" class="glance-item">
        <i class="fas fa-calendar-alt" style="color:#10b981;"></i>
        <span>Trainings: <strong>{{ $upcomingTrainings ?? 8 }}</strong></span>
    </a>
    <div class="glance-divider"></div>
    <div class="glance-item">
        <i class="fas fa-star" style="color:#f59e0b;"></i>
        <span>Agency Score: <strong style="color:var(--primary);">{{ $avgPerformanceScore ?? '2.52' }}/5</strong></span>
    </div>
    <div class="glance-divider"></div>
    <div class="glance-item">
        <i class="fas fa-graduation-cap" style="color:#3b82f6;"></i>
        <span>Learning: <strong style="color:#10b981;">{{ $learningCompletionRate ?? '100.0' }}%</strong></span>
    </div>
    
    <a href="{{ route('admin.drivers.index') }}" class="btn btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; border-radius: 0.5rem;">
        <i class="fas fa-plus"></i> Add Driver
    </a>
</div>

<!-- SECTION 2: Interactive Data Grids & Lists -->
<div class="mini-grid">
    
    <!-- LEFT COLUMN: Driver & KPI Grid -->
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
        
        <!-- Top Performing Drivers List -->
        <div class="minimal-card">
            <div class="minimal-card-header">
                <h3 class="minimal-card-title"><i class="fas fa-trophy" style="color:#f59e0b;"></i> Top Performing Drivers </h3>
                <a href="{{ route('admin.performance.reports') }}" style="font-size:0.75rem; color:var(--primary); text-decoration:none; font-weight:600;">View All &rarr;</a>
            </div>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Score</th>
                        <th>Competency</th>
                        <th>Badge</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPerformers ?? [] as $index => $perf)
                    @php
                        $driver = $perf['driver'] ?? null;
                        $driverName = $driver->name ?? ('Driver #' . ($index + 1));
                        $initials = implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), explode(' ', $driverName)));
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.4rem;">
                                <span style="width:20px; height:20px; border-radius:50%; background:{{ $index === 0 ? '#10b981' : '#3b82f6' }}; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.65rem; font-weight:bold;">{{ substr($initials, 0, 2) ?: 'DR' }}</span>
                                <strong>{{ $driverName }}</strong>
                            </div>
                        </td>
                        <td><strong style="color:var(--primary);">{{ number_format($perf['performance_score'] ?? 4.5, 2) }}/5</strong></td>
                        <td>{{ $perf['competency_score'] ?? '88.5' }}%</td>
                        <td><span style="font-size:0.65rem; padding:0.1rem 0.4rem; border-radius:0.25rem; background:#f1f5f9; color:#475569; font-weight:600;">Active</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:1rem; color:var(--text-muted);">No top performers data available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- KPI Scorecards -->
        <div class="minimal-card">
            <div class="minimal-card-header">
                <h3 class="minimal-card-title"><i class="fas fa-chart-bar" style="color:var(--primary);"></i> KPI Scorecards</h3>
                <a href="{{ route('admin.performance.kpi') }}" style="font-size:0.75rem; color:var(--primary); text-decoration:none; font-weight:600;">Monitor &rarr;</a>
            </div>
            
            <div class="kpi-row">
                <div class="kpi-row-header">
                    <span><i class="fas fa-shield-alt" style="color:#10b981; font-size:0.7rem;"></i> Safety Score</span>
                    <span style="color:#10b981;">{{ $kpiStats['safety_score'] ?? '94.5%' }}</span>
                </div>
                <div class="thin-progress-bg"><div class="thin-progress-fill" style="width:{{ (float)str_replace('%','',$kpiStats['safety_score'] ?? '94.5') }}%; background:#10b981;"></div></div>
            </div>

            <div class="kpi-row">
                <div class="kpi-row-header">
                    <span><i class="fas fa-user-check" style="color:#10b981; font-size:0.7rem;"></i> Attendance Rate</span>
                    <span style="color:#10b981;">{{ $kpiStats['attendance_rate'] ?? '89.2%' }}</span>
                </div>
                <div class="thin-progress-bg"><div class="thin-progress-fill" style="width:{{ (float)str_replace('%','',$kpiStats['attendance_rate'] ?? '89.2') }}%; background:#10b981;"></div></div>
            </div>

            <div class="kpi-row">
                <div class="kpi-row-header">
                    <span><i class="fas fa-route" style="color:#f59e0b; font-size:0.7rem;"></i> Trip Completion Rate</span>
                    <span style="color:#f59e0b;">{{ $kpiStats['trip_completion_rate'] ?? '76.4%' }}</span>
                </div>
                <div class="thin-progress-bg"><div class="thin-progress-fill" style="width:{{ (float)str_replace('%','',$kpiStats['trip_completion_rate'] ?? '76.4') }}%; background:#f59e0b;"></div></div>
            </div>

            <div class="kpi-row">
                <div class="kpi-row-header">
                    <span><i class="fas fa-star" style="color:#ef4444; font-size:0.7rem;"></i> Customer Rating</span>
                    <span style="color:#ef4444;">{{ $kpiStats['customer_rating'] ?? '46.9%' }}</span>
                </div>
                <div class="thin-progress-bg"><div class="thin-progress-fill" style="width:{{ (float)str_replace('%','',$kpiStats['customer_rating'] ?? '46.9') }}%; background:#ef4444;"></div></div>
            </div>
        </div>

    </div>

    <!-- RIGHT COLUMN: Training & Competency -->
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
        
        <!-- Training & Learning Overview -->
        <div class="minimal-card">
            <div class="minimal-card-header">
                <h3 class="minimal-card-title"><i class="fas fa-chalkboard-teacher" style="color:#3b82f6;"></i> Training & Learning</h3>
                <a href="{{ route('admin.training.schedule') }}" style="font-size:0.75rem; color:var(--primary); text-decoration:none; font-weight:600;">Schedule &rarr;</a>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.5rem; margin-bottom:0.85rem; text-align:center;">
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:0.5rem; border-radius:0.5rem;">
                    <div style="font-size:0.65rem; color:var(--text-muted);">Upcoming</div>
                    <div style="font-size:1.1rem; font-weight:bold; color:#3b82f6;">{{ $upcomingTrainings ?? 8 }}</div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:0.5rem; border-radius:0.5rem;">
                    <div style="font-size:0.65rem; color:var(--text-muted);">Ongoing</div>
                    <div style="font-size:1.1rem; font-weight:bold; color:#f59e0b;">{{ $ongoingTrainings ?? 8 }}</div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:0.5rem; border-radius:0.5rem;">
                    <div style="font-size:0.65rem; color:var(--text-muted);">Completed</div>
                    <div style="font-size:1.1rem; font-weight:bold; color:#10b981;">{{ $completedTrainings ?? 14 }}</div>
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; background:#ecfdf5; border:1px solid #a7f3d0; padding:0.6rem 0.75rem; border-radius:0.5rem; font-size:0.75rem;">
                <span style="color:#065f46; font-weight:600;"><i class="fas fa-graduation-cap"></i> Learning Completion Rate</span>
                <span style="font-size:0.9rem; font-weight:bold; color:#047857;">{{ $learningCompletionRate ?? '100.0' }}%</span>
            </div>
        </div>

        <!-- Competency & Recognition (Spider Chart + Badges) -->
        <div class="minimal-card">
            <div class="minimal-card-header">
                <h3 class="minimal-card-title"><i class="fas fa-award" style="color:#ec4899;"></i> Competency & Recognition</h3>
                <a href="{{ route('admin.recognition.awards') }}" style="font-size:0.75rem; color:var(--primary); text-decoration:none; font-weight:600;">Awards &rarr;</a>
            </div>

            <div style="display:grid; grid-template-columns: 120px 1fr; gap:0.75rem; align-items:center;">
                <!-- Tiny Spider/Radar Chart -->
                <div style="width:120px; height:120px;">
                    <canvas id="competencyRadarChart"></canvas>
                </div>

                <!-- Recognition List -->
                <div style="display:flex; flex-direction:column; gap:0.4rem; font-size:0.75rem;">
                    <div style="display:flex; align-items:center; gap:0.4rem; background:#fdf2f8; padding:0.4rem 0.5rem; border-radius:0.35rem; border:1px solid #fbcfe8;">
                        <i class="fas fa-medal" style="color:#ec4899;"></i>
                        <div>
                            <div style="font-size:0.65rem; color:#9d174d; font-weight:600;">SAFEST DRIVER</div>
                            <div style="font-weight:bold; color:#831843;">Marge J. (100% Score)</div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:0.4rem; background:#f0fdf4; padding:0.4rem 0.5rem; border-radius:0.35rem; border:1px solid #bbf7d0;">
                        <i class="fas fa-trophy" style="color:#16a34a;"></i>
                        <div>
                            <div style="font-size:0.65rem; color:#166534; font-weight:600;">MONTHLY AWARDS</div>
                            <div style="font-weight:bold; color:#14532d;">12 Badges Issued</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- SECTION 3: System Status, Succession, and Performance Trend -->
<div class="bottom-row">

    <!-- Recent Notifications (Integrated Bottom Left) -->
    <div class="minimal-card">
        <div class="minimal-card-header">
            <h3 class="minimal-card-title"><i class="fas fa-bell" style="color:#f59e0b;"></i> Recent Notifications</h3>
            <a href="{{ route('admin.notifications.history') }}" style="font-size:0.75rem; color:var(--primary); text-decoration:none; font-weight:600;">History &rarr;</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:0.4rem; font-size:0.75rem;">
            <div style="display:flex; justify-content:space-between; padding-bottom:0.3rem; border-bottom:1px dashed #e2e8f0;">
                <span><i class="fas fa-circle" style="font-size:0.4rem; color:var(--primary);"></i> New Driver Evaluation submitted</span>
                <span style="color:var(--text-muted); font-size:0.65rem;">10m ago</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding-bottom:0.3rem; border-bottom:1px dashed #e2e8f0;">
                <span><i class="fas fa-circle" style="font-size:0.4rem; color:#10b981;"></i> Training Session 'Safety' Completed</span>
                <span style="color:var(--text-muted); font-size:0.65rem;">1h ago</span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span><i class="fas fa-circle" style="font-size:0.4rem; color:#3b82f6;"></i> KPI Report updated for South Branch</span>
                <span style="color:var(--text-muted); font-size:0.65rem;">3h ago</span>
            </div>
        </div>
    </div>

    <!-- Succession Planning (Integrated Bottom Middle) -->
    <div class="minimal-card">
        <div class="minimal-card-header">
            <h3 class="minimal-card-title"><i class="fas fa-seedling" style="color:#10b981;"></i> Succession Planning</h3>
            <a href="{{ route('admin.succession.talent-pool') }}" style="font-size:0.75rem; color:var(--primary); text-decoration:none; font-weight:600;">Pool &rarr;</a>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.4rem; text-align:center; font-size:0.75rem;">
            <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:0.5rem; border-radius:0.4rem;">
                <div style="font-size:0.65rem; color:var(--text-muted);">Leadership</div>
                <div style="font-size:1rem; font-weight:bold; color:var(--text-dark);">0</div>
            </div>
            <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:0.5rem; border-radius:0.4rem;">
                <div style="font-size:0.65rem; color:var(--text-muted);">Ready</div>
                <div style="font-size:1rem; font-weight:bold; color:var(--text-dark);">0</div>
            </div>
            <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:0.5rem; border-radius:0.4rem;">
                <div style="font-size:0.65rem; color:var(--text-muted);">Active Plans</div>
                <div style="font-size:1rem; font-weight:bold; color:var(--text-dark);">0</div>
            </div>
        </div>
    </div>

    <!-- Performance Trend Summary Box (Bottom Right) -->
    <div class="minimal-card" style="display:flex; flex-direction:column; justify-content:space-between;">
        <div class="minimal-card-header" style="margin-bottom:0.4rem; border-bottom:none;">
            <h3 class="minimal-card-title"><i class="fas fa-chart-line" style="color:var(--primary);"></i> Performance Trend</h3>
            <a href="{{ route('admin.performance.drivers') }}" style="color:var(--primary);"><i class="fas fa-chevron-right" style="font-size:0.75rem;"></i></a>
        </div>
        
        <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border:1px solid #e2e8f0; padding:0.6rem 0.8rem; border-radius:0.5rem;">
            <div>
                <div style="font-size:0.65rem; color:var(--text-muted);">Overall Score</div>
                <div style="font-size:1.1rem; font-weight:extrabold; color:var(--primary);">2.52/5</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:0.65rem; color:var(--text-muted);">KPI Achievement</div>
                <div style="font-size:1.1rem; font-weight:extrabold; color:#10b981;">46.9%</div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Spider/Radar Chart for Competency Overview
    const radarCtx = document.getElementById('competencyRadarChart');
    if (radarCtx) {
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: ['Safety', 'Customer', 'Route', 'Maint.', 'Rules'],
                datasets: [{
                    label: 'Average Competency',
                    data: [85, 90, 78, 88, 92],
                    backgroundColor: 'rgba(244, 67, 54, 0.15)',
                    borderColor: '#F44336',
                    borderWidth: 1.5,
                    pointRadius: 2,
                    pointBackgroundColor: '#F44336'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    r: {
                        angleLines: { color: '#e2e8f0' },
                        grid: { color: '#f1f5f9' },
                        pointLabels: { font: { size: 7, family: "'Inter', sans-serif" } },
                        ticks: { display: false }
                    }
                }
            }
        });
    }
});
</script>
@endsection
