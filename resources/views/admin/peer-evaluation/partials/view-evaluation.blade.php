@extends('admin.layouts.admin')

@section('title', 'TripWise — View Evaluation')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.driver-evaluation') }}">Driver Evaluation</a>
    <span>/</span>
    <span>View Evaluation #{{ $peerEvaluation->id }}</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Evaluation Details</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Peer Evaluation #PE-{{ str_pad($peerEvaluation->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.evaluation.driver-evaluation') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="{{ route('admin.evaluation.driver-evaluation.edit', $peerEvaluation) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
    </div>
</div>

<div class="section-grid">
    <div class="section-card">
        <h3><i class="fas fa-user"></i> Evaluator</h3>
        <div class="list-item">
            <div class="item-icon blue"><i class="fas fa-user"></i></div>
            <div class="item-content">
                <div class="item-title">{{ $peerEvaluation->evaluator->name ?? 'N/A' }}</div>
                <div class="item-subtitle">Evaluator ID: {{ $peerEvaluation->evaluator_id }}</div>
            </div>
        </div>
    </div>
    <div class="section-card">
        <h3><i class="fas fa-user-check"></i> Evaluated Driver</h3>
        <div class="list-item">
            <div class="item-icon green"><i class="fas fa-user-check"></i></div>
            <div class="item-content">
                <div class="item-title">{{ $peerEvaluation->evaluatedDriver->name ?? 'N/A' }}</div>
                <div class="item-subtitle">Driver ID: {{ $peerEvaluation->evaluated_driver_id }}</div>
            </div>
        </div>
    </div>
    <div class="section-card">
        <h3><i class="fas fa-calendar"></i> Evaluation Date</h3>
        <div class="list-item">
            <div class="item-icon orange"><i class="fas fa-calendar"></i></div>
            <div class="item-content">
                <div class="item-title">{{ $peerEvaluation->evaluation_date->format('F d, Y') }}</div>
                <div class="item-subtitle">Status: {{ ucfirst($peerEvaluation->status) }}</div>
            </div>
        </div>
    </div>
    <div class="section-card">
        <h3><i class="fas fa-star"></i> Overall Score</h3>
        <div class="list-item">
            <div class="item-icon gold"><i class="fas fa-star"></i></div>
            <div class="item-content">
                <div class="item-title" style="font-size:1.5rem;color:var(--primary);">{{ number_format($peerEvaluation->overall_score ?? 0, 2) }}/5</div>
                <div class="item-subtitle">Anonymous: {{ $peerEvaluation->is_anonymous ? 'Yes' : 'No' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-sliders-h"></i> Category Scores</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Category</th><th>Score</th><th>Rating</th></tr>
            </thead>
            <tbody>
                @if($peerEvaluation->category_scores && is_array($peerEvaluation->category_scores))
                    @foreach($peerEvaluation->category_scores as $category => $score)
                    <tr>
                        <td><strong>{{ ucfirst($category) }}</strong></td>
                        <td><strong>{{ $score }}/5</strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div style="width:100px;height:8px;background:var(--beige-dark);border-radius:999px;overflow:hidden;">
                                    <div style="width:{{ ($score / 5) * 100 }}%;height:100%;background:{{ $score >= 4 ? '#10b981' : ($score >= 3 ? '#f59e0b' : '#ef4444') }};border-radius:999px;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);">No category scores recorded.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@if($peerEvaluation->comments || $peerEvaluation->suggestions)
<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-comments"></i> Comments & Suggestions</h3>
    @if($peerEvaluation->comments)
        <div style="margin-bottom:1rem;">
            <strong style="color:var(--primary);">Comments:</strong>
            <p style="color:var(--text-dark);margin-top:0.5rem;line-height:1.6;">{{ $peerEvaluation->comments }}</p>
        </div>
    @endif
    @if($peerEvaluation->suggestions)
        <div>
            <strong style="color:var(--primary);">Suggestions:</strong>
            <p style="color:var(--text-dark);margin-top:0.5rem;line-height:1.6;">{{ $peerEvaluation->suggestions }}</p>
        </div>
    @endif
</div>
@endif

@if($peerEvaluation->admin_remarks)
<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-user-shield"></i> Admin Remarks</h3>
    <p style="color:var(--text-dark);line-height:1.6;">{{ $peerEvaluation->admin_remarks }}</p>
    <p style="font-size:0.8rem;color:var(--text-muted);margin-top:0.5rem;">Reviewed by: {{ $peerEvaluation->reviewer->name ?? 'N/A' }} on {{ $peerEvaluation->reviewed_at ? $peerEvaluation->reviewed_at->format('M d, Y H:i') : '-' }}</p>
</div>
@endif

@endsection
