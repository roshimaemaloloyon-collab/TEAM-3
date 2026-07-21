@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Registration')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Registration</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Driver Registration</h1>
        <p>Assign drivers to training sessions and manage registrations.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Single registration')"><i class="fas fa-user-plus"></i> Register Driver</button>
        <button class="btn btn-secondary" onclick="showToast('Bulk registration')"><i class="fas fa-users"></i> Bulk Register</button>
        <button class="btn btn-secondary" onclick="showToast('Waiting list')"><i class="fas fa-list"></i> Waiting List</button>
    </div>
</div>

<!-- Registration Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>Training Assigned</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td>Defensive Driving Workshop</td>
                    <td>July 10, 2026</td>
                    <td><span class="status-badge status-active">Approved</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View registration')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Remove" onclick="showToast('Remove driver')" style="color: var(--danger);"><i class="fas fa-user-minus"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Maria Santos</strong></td>
                    <td>Customer Service Excellence</td>
                    <td>July 12, 2026</td>
                    <td><span class="status-badge status-pending">Pending</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View registration')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Approve" onclick="showToast('Approve registration')" style="color: var(--success);"><i class="fas fa-check"></i></button>
                            <button class="icon-btn" title="Reject" onclick="showToast('Reject registration')" style="color: var(--danger);"><i class="fas fa-times"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Pedro Reyes</strong></td>
                    <td>Safety Protocols Training</td>
                    <td>July 8, 2026</td>
                    <td><span class="status-badge status-review">Waitlisted</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View registration')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Remove" onclick="showToast('Remove driver')" style="color: var(--danger);"><i class="fas fa-user-minus"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
            <span>Rows per page:</span>
            <select style="padding: 0.4rem 0.6rem; border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.85rem;">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.4rem; align-items: center;">
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Previous</button>
            <button class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; min-width: 36px;">1</button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; min-width: 36px;">2</button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; min-width: 36px;">3</button>
            <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Next</button>
        </div>
    </div>
</div>
@endsection
