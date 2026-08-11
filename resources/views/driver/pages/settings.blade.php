@extends('driver.layouts.driver')

@section('title', 'TripWise — Settings')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Settings</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Settings</h1>
            <p>Manage your account preferences and settings.</p>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-cog"></i> Account Settings</h3>
        <p style="color:var(--text-muted);">Account settings and preferences will be available here.</p>
    </div>
@endsection
