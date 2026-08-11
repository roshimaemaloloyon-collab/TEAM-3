@extends('driver.layouts.driver')

@section('title', 'TripWise — My Competencies')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>My Competencies</span>
    </div>

    <div class="page-header">
        <div>
            <h1>My Competencies</h1>
            <p>View your competency assessments and skill development progress.</p>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="card-icon blue"><i class="fas fa-brain"></i></div>
            <div class="card-info">
                <h3>{{ number_format($avgScore ?? 0, 1) }}%</h3>
                <p>Average Score</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="card-info">
                <h3>{{ $skillGaps ?? 0 }}</h3>
                <p>Skill Gaps</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon green"><i class="fas fa-chart-line"></i></div>
            <div class="card-info">
                <h3>{{ $assessments->count() }}</h3>
                <p>Assessments</p>
            </div>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-list"></i> Competency Assessments</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Assessment</th>
                        <th>Category</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assessments as $assessment)
                        <tr>
                            <td><strong>{{ $assessment->name ?? 'Assessment' }}</strong></td>
                            <td>{{ $assessment->category ?? 'General' }}</td>
                            <td><strong>{{ number_format($assessment->score ?? 0, 1) }}%</strong></td>
                            <td>
                                <span class="status-badge {{ ($assessment->score ?? 0) >= 80 ? 'status-active' : (($assessment->score ?? 0) >= 60 ? 'status-review' : 'status-pending') }}">
                                    {{ ($assessment->score ?? 0) >= 80 ? 'Proficient' : (($assessment->score ?? 0) >= 60 ? 'Developing' : 'Needs Improvement') }}
                                </span>
                            </td>
                            <td>{{ $assessment->created_at ? \Carbon\Carbon::parse($assessment->created_at)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                No competency assessments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $assessments->links() }}
        </div>
    </div>
@endsection
