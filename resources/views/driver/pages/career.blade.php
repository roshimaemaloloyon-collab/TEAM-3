@extends('driver.layouts.driver')

@section('title', 'TripWise — Career Growth')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Career Growth</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Career Growth</h1>
            <p>Track your career path, development plans, and promotion readiness.</p>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-rocket"></i> Career Development</h3>
        <p style="color:var(--text-muted);">Career growth plans and progression tracking will appear here as your development records are updated.</p>
    </div>
@endsection
