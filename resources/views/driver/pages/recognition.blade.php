@extends('driver.layouts.driver')

@section('title', 'TripWise — Recognition & Achievements')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Recognition & Achievements</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Recognition & Achievements</h1>
            <p>View your awards, badges, and peer recognition.</p>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-trophy"></i> Achievements</h3>
        <p style="color:var(--text-muted);">Your awards and recognition will appear here as they are recorded.</p>
    </div>
@endsection
