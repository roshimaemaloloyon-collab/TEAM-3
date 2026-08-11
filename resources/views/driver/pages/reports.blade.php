@extends('driver.layouts.driver')

@section('title', 'TripWise — Reports')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Reports</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Reports</h1>
            <p>View and download your performance and activity reports.</p>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-file-alt"></i> Available Reports</h3>
        <p style="color:var(--text-muted);">Your generated reports will appear here once available.</p>
    </div>
@endsection
