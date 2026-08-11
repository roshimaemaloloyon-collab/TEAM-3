@extends('driver.layouts.driver')

@section('title', 'TripWise — My Trainings')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>My Trainings</span>
    </div>

    <div class="page-header">
        <div>
            <h1>My Trainings</h1>
            <p>View your training schedules, attendance, and completion status.</p>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="card-icon blue"><i class="fas fa-graduation-cap"></i></div>
            <div class="card-info">
                <h3>{{ $myTrainings ?? 0 }}</h3>
                <p>Total Trainings</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="card-info">
                <h3>{{ $myCompletedTrainings ?? 0 }}</h3>
                <p>Completed</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon orange"><i class="fas fa-percentage"></i></div>
            <div class="card-info">
                <h3>{{ $myAttendanceRate ?? 0 }}%</h3>
                <p>Completion Rate</p>
            </div>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-list"></i> Training Records</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Training</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainings as $training)
                        <tr>
                            <td><strong>{{ $training->training->title ?? 'Training' }}</strong></td>
                            <td>{{ $training->training->type ?? 'General' }}</td>
                            <td>{{ $training->created_at ? \Carbon\Carbon::parse($training->created_at)->format('M dd, Y') : 'N/A' }}</td>
                            <td>
                                <span class="status-badge {{ $training->status === 'approved' ? 'status-active' : ($training->status === 'pending' ? 'status-pending' : 'status-review') }}">
                                    {{ ucfirst($training->status ?? 'Pending') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                No training records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $trainings->links() }}
        </div>
    </div>
@endsection
