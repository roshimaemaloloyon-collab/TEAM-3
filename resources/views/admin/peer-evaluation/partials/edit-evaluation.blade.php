@extends('admin.layouts.admin')

@section('title', 'TripWise — Edit Evaluation')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.driver-evaluation') }}">Driver Evaluation</a>
    <span>/</span>
    <span>Edit Evaluation #{{ $peerEvaluation->id }}</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Edit Evaluation</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Update peer evaluation #PE-{{ str_pad($peerEvaluation->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    <a href="{{ route('admin.evaluation.driver-evaluation.show', $peerEvaluation) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="table-card">
    <form method="POST" action="{{ route('admin.evaluation.driver-evaluation.update', $peerEvaluation) }}">
        @csrf
        @method('PUT')
        <div style="display:grid;gap:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Evaluated Driver</label>
                    <select name="evaluated_driver_id" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="">-- Select Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ $peerEvaluation->evaluated_driver_id == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Evaluation Date</label>
                    <input type="date" name="evaluation_date" required value="{{ $peerEvaluation->evaluation_date->format('Y-m-d') }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;">
                @foreach(['professionalism' => 'Professionalism', 'communication' => 'Communication', 'teamwork' => 'Teamwork', 'safety' => 'Safety', 'reliability' => 'Reliability', 'respectfulness' => 'Respectfulness'] as $key => $label)
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">{{ $label }} (1-5)</label>
                    <input type="number" name="category_scores[{{ $key }}]" min="1" max="5" step="0.1" value="{{ $peerEvaluation->category_scores[$key] ?? 5 }}" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                @endforeach
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Overall Score (1-5)</label>
                <input type="number" name="overall_score" min="1" max="5" step="0.1" value="{{ $peerEvaluation->overall_score ?? 5 }}" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Comments</label>
                <textarea name="comments" rows="3" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;">{{ $peerEvaluation->comments }}</textarea>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Suggestions</label>
                <textarea name="suggestions" rows="3" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;">{{ $peerEvaluation->suggestions }}</textarea>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" {{ $peerEvaluation->is_anonymous ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                <label for="is_anonymous" style="font-size:0.9rem;cursor:pointer;">Submit anonymously</label>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.evaluation.driver-evaluation.show', $peerEvaluation) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Evaluation</button>
            </div>
        </div>
    </form>
</div>

@endsection
