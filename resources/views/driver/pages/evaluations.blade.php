@extends('driver.layouts.driver')

@section('title', 'TripWise — Peer-to-Peer Evaluation')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Peer-to-Peer Evaluation</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Peer-to-Peer Evaluation</h1>
            <p>Review feedback and evaluations from your peers.</p>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="card-icon blue"><i class="fas fa-users"></i></div>
            <div class="card-info">
                <h3>{{ $myEvaluations ?? 0 }}</h3>
                <p>Evaluations Received</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon green"><i class="fas fa-star"></i></div>
            <div class="card-info">
                <h3>{{ number_format($myAvgScore ?? 0, 1) }}</h3>
                <p>Average Score</p>
            </div>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-list"></i> Evaluation History</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Evaluator</th>
                        <th>Teamwork</th>
                        <th>Professionalism</th>
                        <th>Communication</th>
                        <th>Safety</th>
                        <th>Reliability</th>
                        <th>Overall</th>
                        <th>Date</th>
                    </tr>
                </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr>
                                <td><strong>{{ $evaluation->evaluator->name ?? 'Peer' }}</strong></td>
                                <td>{{ number_format($evaluation->getCategoryScore('teamwork') ?? 0, 1) }}</td>
                                <td>{{ number_format($evaluation->getCategoryScore('professionalism') ?? 0, 1) }}</td>
                                <td>{{ number_format($evaluation->getCategoryScore('communication') ?? 0, 1) }}</td>
                                <td>{{ number_format($evaluation->getCategoryScore('technical_skill') ?? 0, 1) }}</td>
                                <td>{{ number_format($evaluation->getCategoryScore('punctuality') ?? 0, 1) }}</td>
                                <td><strong>{{ number_format($evaluation->overall_score ?? 0, 1) }}</strong></td>
                                <td>{{ $evaluation->created_at ? \Carbon\Carbon::parse($evaluation->created_at)->format('M dd, Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                No evaluations received yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $evaluations->links() }}
        </div>
    </div>
@endsection
