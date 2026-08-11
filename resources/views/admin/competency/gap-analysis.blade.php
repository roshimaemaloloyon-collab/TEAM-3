@extends('admin.layouts.admin')

@section('title', 'TripWise — Gap Analysis')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.competency.index') }}">Competency Management</a>
    <span>/</span>
    <span>Gap Analysis</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Competency Gap Analysis</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Identify, measure, and analyze performance gaps between actual driver skills and target operational benchmarks.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Export Gap Report</button>
        <a href="{{ route('admin.competency.plans') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create Development Plan</a>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['critical_gaps'] }}</h3>
            <p>Critical Skill Gaps (&lt;60%)</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-chart-pie"></i></div>
        <div class="card-info">
            <h3>{{ $stats['moderate_gaps'] }}</h3>
            <p>Moderate Skill Gaps (60-74%)</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bullseye"></i></div>
        <div class="card-info">
            <h3>{{ $stats['overall_gap'] }}%</h3>
            <p>Average Skill Variance</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>{{ $stats['proficient_count'] }}</h3>
            <p>Proficient Drivers (&ge;85%)</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('admin.competency.gap-analysis') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver by name or email..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency Category</label>
            <select name="category" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Categories</option>
                <option value="safety" {{ request('category') === 'safety' ? 'selected' : '' }}>Safety & Regulations</option>
                <option value="customer_service" {{ request('category') === 'customer_service' ? 'selected' : '' }}>Customer Service</option>
                <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>Technical Driving Skills</option>
                <option value="behavioral" {{ request('category') === 'behavioral' ? 'selected' : '' }}>Behavioral & Conduct</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Gap Level</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Gap Levels</option>
                <option value="critical" {{ request('status') === 'critical' ? 'selected' : '' }}>Critical Gap (&lt;60%)</option>
                <option value="moderate" {{ request('status') === 'moderate' ? 'selected' : '' }}>Moderate Gap (60-74%)</option>
                <option value="minimal" {{ request('status') === 'minimal' ? 'selected' : '' }}>Minimal Gap (75-84%)</option>
                <option value="proficient" {{ request('status') === 'proficient' ? 'selected' : '' }}>Proficient (&ge;85%)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Analyze</button>
    </form>
</div>

<!-- Real-time Skill Gap Analysis Table -->
<div class="table-card" style="margin-bottom:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.5rem;">
        <h3 style="margin:0;"><i class="fas fa-tasks"></i> Driver Skill Gap Matrix</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Real-time competency assessment vs operational target (85.0%)</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>Competency Skill</th>
                    <th>Category</th>
                    <th>Target Score</th>
                    <th>Actual Score</th>
                    <th>Skill Gap</th>
                    <th>Gap Severity</th>
                    <th>Recommended Action</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                    @php
                        $target = $assessment->competency->target_score ?? 85;
                        $actual = $assessment->score ?? 0;
                        $gap = max(0, round($target - $actual, 1));
                        
                        if ($actual < 60) {
                            $badgeClass = 'badge-danger';
                            $severity = 'Critical Gap';
                            $action = 'Mandatory Re-training';
                        } elseif ($actual < 75) {
                            $badgeClass = 'badge-warning';
                            $severity = 'Moderate Gap';
                            $action = 'Assigned Mentorship';
                        } elseif ($actual < 85) {
                            $badgeClass = 'badge-info';
                            $severity = 'Minimal Gap';
                            $action = 'Refresher Course';
                        } else {
                            $badgeClass = 'badge-success';
                            $severity = 'Proficient';
                            $action = 'Skill Maintenance';
                        }
                    @endphp
                    <tr>
                        <td><strong>{{ $assessment->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $assessment->competency->name ?? 'Operational Safety' }}</td>
                        <td><span style="text-transform:capitalize;font-size:0.82rem;padding:0.2rem 0.6rem;background:var(--beige-dark);border-radius:0.4rem;">{{ str_replace('_', ' ', $assessment->competency->category ?? 'General') }}</span></td>
                        <td><strong>{{ number_format($target, 1) }}%</strong></td>
                        <td><strong style="color: {{ $actual < 70 ? 'var(--danger)' : 'inherit' }}">{{ number_format($actual, 1) }}%</strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.4rem;">
                                <span style="font-weight:700;color:{{ $gap > 15 ? 'var(--danger)' : ($gap > 0 ? 'var(--warning)' : 'var(--success)') }}">
                                    -{{ $gap }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="item-badge {{ $badgeClass }}">
                                {{ $severity }}
                            </span>
                        </td>
                        <td><span style="font-size:0.85rem;font-weight:500;">{{ $action }}</span></td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <a href="{{ route('admin.competency.plans') }}" class="btn btn-sm btn-primary" title="Assign Plan"><i class="fas fa-user-graduate"></i></a>
                                <a href="{{ route('admin.competency.results') }}" class="btn btn-sm btn-secondary" title="View Assessment"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:2rem;">No competency gap assessments found matching your filter criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $assessments->links() }}
    </div>
</div>

<!-- Recommended Training Programs Section -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-chalkboard-teacher"></i> Automated Training Intervention Recommendations</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1rem;">
        @foreach($recommendedTrainings as $training)
            <div style="border:1px solid var(--border);border-radius:0.75rem;padding:1rem;background:var(--white);display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:var(--primary);text-transform:uppercase;">Intervention Course</span>
                        <span class="item-badge badge-info">{{ ucfirst($training->status ?? 'upcoming') }}</span>
                    </div>
                    <h4 style="margin:0 0 0.4rem;font-size:1rem;color:var(--text-dark);">{{ $training->title ?? $training->name }}</h4>
                    <p style="font-size:0.82rem;color:var(--text-muted);margin:0 0 0.75rem;">Designed to close critical skill variances in driving & safety protocols.</p>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--border);padding-top:0.75rem;margin-top:0.5rem;">
                    <span style="font-size:0.8rem;color:var(--text-muted);"><i class="fas fa-clock"></i> {{ $training->duration ?? '4 Hours' }}</span>
                    <a href="{{ route('admin.competency.plans') }}" class="btn btn-sm btn-secondary" style="font-size:0.78rem;">Enroll Gap Drivers</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
