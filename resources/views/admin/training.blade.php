@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Management')

@section('content')
<div class="page-header">
    <div>
        <h1>Training Management</h1>
        <p>Schedule, manage, and track training sessions for drivers.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Schedule Training</a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>8</h3>
            <p>Upcoming Sessions</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>42</h3>
            <p>Completed This Month</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-clock"></i></div>
        <div class="card-info">
            <h3>15</h3>
            <p>Pending Attendance</p>
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> Upcoming Training Sessions</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Training</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Instructor</th>
                    <th>Enrolled</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Defensive Driving Workshop</strong></td>
                    <td>July 15, 2026</td>
                    <td>9:00 AM</td>
                    <td>Internal SecOps</td>
                    <td>25</td>
                    <td><span class="status-badge status-info">Scheduled</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage</button></td>
                </tr>
                <tr>
                    <td><strong>First Aid Certification</strong></td>
                    <td>July 22, 2026</td>
                    <td>1:00 PM</td>
                    <td>Red Cross</td>
                    <td>18</td>
                    <td><span class="status-badge status-success">Enrolled</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage</button></td>
                </tr>
                <tr>
                    <td><strong>Eco-Driving Techniques</strong></td>
                    <td>Aug 5, 2026</td>
                    <td>10:00 AM</td>
                    <td>Fleet Mgmt</td>
                    <td>30</td>
                    <td><span class="status-badge status-warning">Upcoming</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Manage</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
