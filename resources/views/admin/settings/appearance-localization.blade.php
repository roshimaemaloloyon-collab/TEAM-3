@extends('admin.layouts.admin')

@section('title', 'TripWise — Appearance & Localization')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}">Settings</a>
    <span>/</span>
    <span>Appearance & Localization</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Appearance & Localization</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Customize the system interface, theme, language, and accessibility options.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('restoreForm').submit();"><i class="fas fa-undo"></i> Restore Defaults</button>
        <button type="submit" form="appearanceForm" class="btn btn-primary"><i class="fas fa-save"></i> Apply Changes</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-palette"></i></div>
        <div class="card-info">
            <h3 style="text-transform:capitalize;">{{ $settings->theme }}</h3>
            <p>Active Theme</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-language"></i></div>
        <div class="card-info">
            <h3>{{ strtoupper($settings->language) }}</h3>
            <p>Current Language</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-universal-access"></i></div>
        <div class="card-info">
            <h3>{{ $settings->high_contrast ? 'High Contrast' : 'Standard' }}</h3>
            <p>Accessibility Status</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-sliders-h"></i></div>
        <div class="card-info">
            <h3 style="text-transform:capitalize;">{{ $settings->sidebar_behavior }}</h3>
            <p>UI Preferences</p>
        </div>
    </div>
</div>

<!-- Appearance & Localization Form -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-palette"></i> Interface Preferences</h3>
    <form id="appearanceForm" method="POST" action="{{ route('admin.settings.appearance.update') }}">
        @csrf
        @method('PUT')
        <div style="display:grid;gap:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Theme</label>
                    <select name="theme" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="light" {{ $settings->theme === 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ $settings->theme === 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Language</label>
                    <select name="language" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="en" {{ $settings->language === 'en' ? 'selected' : '' }}>English</option>
                        <option value="fil" {{ $settings->language === 'fil' ? 'selected' : '' }}>Filipino</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Font Size</label>
                    <select name="font_size" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="small" {{ $settings->font_size === 'small' ? 'selected' : '' }}>Small</option>
                        <option value="medium" {{ $settings->font_size === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="large" {{ $settings->font_size === 'large' ? 'selected' : '' }}>Large</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Sidebar Behavior</label>
                    <select name="sidebar_behavior" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="expanded" {{ $settings->sidebar_behavior === 'expanded' ? 'selected' : '' }}>Expanded</option>
                        <option value="collapsed" {{ $settings->sidebar_behavior === 'collapsed' ? 'selected' : '' }}>Collapsed</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.75rem;padding:1rem;background:var(--beige);border-radius:0.75rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <input type="checkbox" name="high_contrast" id="high_contrast" value="1" {{ $settings->high_contrast ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="high_contrast" style="font-size:0.9rem;cursor:pointer;"><strong>High Contrast Mode</strong></label>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <input type="checkbox" name="reduce_motion" id="reduce_motion" value="1" {{ $settings->reduce_motion ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="reduce_motion" style="font-size:0.9rem;cursor:pointer;"><strong>Reduce Motion</strong></label>
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Apply Changes</button>
            </div>
        </div>
    </form>
    <form id="restoreForm" method="POST" action="{{ route('admin.settings.appearance.restore') }}" style="display:none;">
        @csrf
        @method('POST')
    </form>
</div>

@endsection
