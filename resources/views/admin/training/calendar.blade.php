@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Calendar')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Calendar</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Training Calendar</h1>
        <p>View and manage training sessions in calendar format.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Add Training modal coming soon')"><i class="fas fa-plus"></i> Add Training</button>
        <button class="btn btn-secondary" onclick="showToast('Monthly view')"><i class="fas fa-th"></i> Monthly</button>
        <button class="btn btn-secondary" onclick="showToast('Weekly view')"><i class="fas fa-list"></i> Weekly</button>
        <button class="btn btn-secondary" onclick="showToast('Daily view')"><i class="fas fa-calendar-day"></i> Daily</button>
    </div>
</div>

<!-- Calendar Placeholder -->
<div class="table-card">
    <div style="background: var(--white); border-radius: 1rem; padding: 3rem; text-align: center; border: 2px dashed var(--border);">
        <i class="fas fa-calendar-alt" style="font-size: 4rem; color: var(--primary); margin-bottom: 1.5rem; opacity: 0.5;"></i>
        <h3 style="color: var(--primary); margin-bottom: 0.75rem;">Interactive Training Calendar</h3>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 1.5rem; font-size: 0.95rem;">Full calendar integration with monthly, weekly, and daily views. Color-coded events, drag-and-drop rescheduling, and quick-add training sessions will be implemented here.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                <span style="width: 12px; height: 12px; border-radius: 3px; background: var(--primary);"></span> Upcoming
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                <span style="width: 12px; height: 12px; border-radius: 3px; background: var(--success);"></span> Completed
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                <span style="width: 12px; height: 12px; border-radius: 3px; background: var(--warning);"></span> Ongoing
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                <span style="width: 12px; height: 12px; border-radius: 3px; background: var(--danger);"></span> Cancelled
            </div>
        </div>
    </div>
</div>
@endsection
