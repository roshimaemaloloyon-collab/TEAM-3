@extends('admin.layouts.admin')

@section('title', 'TripWise — Agency Settings')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span>/</span>
    <span>Agency Settings</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Agency Settings</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage agency information displayed throughout the TripWise system.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('agencyForm').submit();"><i class="fas fa-save"></i> Save Changes</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-building"></i></div>
        <div class="card-info">
            <h3>{{ $settings->agency_name ? 'Complete' : 'Incomplete' }}</h3>
            <p>Agency Profile Status</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-address-book"></i></div>
        <div class="card-info">
            <h3>{{ $settings->contact_number ? 'Set' : 'Not Set' }}</h3>
            <p>Contact Information</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-image"></i></div>
        <div class="card-info">
            <h3>{{ $settings->logo_path ? 'Uploaded' : 'Not Uploaded' }}</h3>
            <p>Logo Status</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $settings->updated_at->format('M d, Y') }}</h3>
            <p>Last Updated</p>
        </div>
    </div>
</div>

<!-- Agency Settings Form -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-building"></i> Agency Information</h3>
    <form id="agencyForm" method="POST" action="{{ route('admin.settings.agency.update') }}">
        @csrf
        @method('PUT')
        <div style="display:grid;gap:1.5rem;">
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Agency Name</label>
                <input type="text" name="agency_name" value="{{ $settings->agency_name }}" required placeholder="Enter agency name" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Agency Address</label>
                <textarea name="address" rows="3" placeholder="Enter agency address" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;">{{ $settings->address }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ $settings->contact_number }}" placeholder="Enter contact number" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Email Address</label>
                    <input type="email" name="email" value="{{ $settings->email }}" required placeholder="Enter email address" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Agency Description</label>
                <textarea name="description" rows="4" placeholder="Enter agency description..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;resize:vertical;">{{ $settings->description }}</textarea>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Agency Logo</label>
                <div style="border:2px dashed var(--border);border-radius:0.75rem;padding:2rem;text-align:center;background:var(--beige);">
                    @if($settings->logo_path)
                        <img src="{{ asset($settings->logo_path) }}" alt="Agency Logo" style="max-height:100px;margin-bottom:1rem;">
                    @endif
                    <p style="color:var(--text-muted);font-size:0.9rem;margin:0 0 0.5rem;">Drag and drop your logo here, or click to browse</p>
                    <input type="file" name="logo" accept="image/*" style="display:none;" id="logoInput">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('logoInput').click();"><i class="fas fa-upload"></i> Upload Logo</button>
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </div>
    </form>
</div>

@endsection
