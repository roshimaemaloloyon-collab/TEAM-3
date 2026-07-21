@extends('admin.layouts.admin')

@section('title', 'TripWise — Driver Feedback Detail')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.feedback-summary') }}">Feedback Summary</a>
    <span>/</span>
    <span>{{ $driver->name }}</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Feedback Detail</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Peer feedback details for {{ $driver->name }}.</p>
    </div>
    <a href="{{ route('admin.evaluation.feedback-summary') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="section-grid">
    <div class="section-card">
        <h3><i class="fas fa-user"></i> Driver Information</h3>
        <div class="list-item">
            <div class="item-icon blue"><i class="fas fa-user"></i></div>
            <div class="item-content">
                <div class="item-title">{{ $driver->name }}</div>
                <div class="item-subtitle">Driver ID: {{ $driver->id }} | Role: {{ ucfirst($driver->role) }}</div>
            </div>
        </div>
    </div>
    @if($feedback)
    <div class="section-card">
        <h3><i class="fas fa-chart-bar"></i> Feedback Summary</h3>
        <div class="list-item">
            <div class="item-icon green"><i class="fas fa-thumbs-up"></i></div>
            <div class="item-content">
                <div class="item-title">Average Peer Rating: {{ number_format($feedback->average_peer_rating ?? 0, 2) }}</div>
                <div class="item-subtitle">Total Evaluations: {{ $feedback->total_evaluations }} | Positive: {{ $feedback->positive_count }} | Improvement: {{ $feedback->improvement_count }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

@if($feedback)
<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-comments"></i> Feedback Details</h3>
    @if($feedback->positive_feedback)
        <div style="margin-bottom:1rem;padding:1rem;background:#f0fdf4;border-radius:0.75rem;border-left:4px solid var(--success);">
            <strong style="color:var(--success);">Positive Feedback:</strong>
            <p style="margin-top:0.5rem;line-height:1.6;">{{ $feedback->positive_feedback }}</p>
        </div>
    @endif
    @if($feedback->improvement_areas)
        <div style="margin-bottom:1rem;padding:1rem;background:#fff7ed;border-radius:0.75rem;border-left:4px solid var(--warning);">
            <strong style="color:var(--warning);">Areas for Improvement:</strong>
            <p style="margin-top:0.5rem;line-height:1.6;">{{ $feedback->improvement_areas }}</p>
        </div>
    @endif
    @if($feedback->recommendations)
        <div style="padding:1rem;background:#eff6ff;border-radius:0.75rem;border-left:4px solid var(--info);">
            <strong style="color:var(--info);">Recommendations:</strong>
            <p style="margin-top:0.5rem;line-height:1.6;">{{ $feedback->recommendations }}</p>
        </div>
    @endif
</div>
@endif

<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-list"></i> Evaluation History for {{ $driver->name }}</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Evaluator</th><th>Date</th><th>Overall Score</th><th>Status</th><th>Comments</th></tr>
            </thead>
            <tbody>
                @forelse($evaluations as $evaluation)
                <tr>
                    <td><strong>{{ $evaluation->evaluator->name ?? 'Anonymous' }}</strong></td>
                    <td>{{ $evaluation->evaluation_date->format('M d, Y') }}</td>
                    <td><strong style="color:{{ $evaluation->overall_score >= 4 ? '#10b981' : ($evaluation->overall_score >= 3 ? '#f59e0b' : '#ef4444') }};">{{ number_format($evaluation->overall_score ?? 0, 2) }}</strong></td>
                    <td><span class="status-badge status-{{ $evaluation->status === 'approved' ? 'success' : ($evaluation->status === 'rejected' ? 'inactive' : 'pending') }}">{{ ucfirst($evaluation->status) }}</span></td>
                    <td style="font-size:0.85rem;color:var(--text-muted);">{{ mb_substr($evaluation->comments ?? '', 0, 100) }}{{ strlen($evaluation->comments ?? '') > 100 ? '...' : '' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">No evaluations found for this driver.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $evaluations->links() }}
    </div>
</div>

@endsection
