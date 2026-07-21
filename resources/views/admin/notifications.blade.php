@extends('admin.layouts.admin')

@section('title', 'TripWise — Notifications')

@section('content')
<div class="page-header">
    <div>
        <h1>Notifications</h1>
        <p>View and manage system notifications, announcements, and alerts.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> New Notification</a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bell"></i></div>
        <div class="card-info">
            <h3>48</h3>
            <p>Total Notifications</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>12</h3>
            <p>Unread</p>
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> Recent Notifications</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>New safety protocol effective July 20, 2026</strong></td>
                    <td>Announcement</td>
                    <td>July 9, 2026</td>
                    <td><span class="status-badge status-info">Unread</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View</button></td>
                </tr>
                <tr>
                    <td><strong>Training "Defensive Driving" approved</strong></td>
                    <td>Training</td>
                    <td>July 8, 2026</td>
                    <td><span class="status-badge status-success">Read</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View</button></td>
                </tr>
                <tr>
                    <td><strong>Vehicle maintenance scheduled July 18, 2026</strong></td>
                    <td>Alert</td>
                    <td>July 7, 2026</td>
                    <td><span class="status-badge status-success">Read</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
