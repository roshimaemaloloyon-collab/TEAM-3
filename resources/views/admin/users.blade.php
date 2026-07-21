@extends('admin.layouts.admin')

@section('title', 'TripWise — User Management')

@section('content')
<div class="page-header">
    <div>
        <h1>User Management</h1>
        <p>Manage system users, roles, permissions, and access controls.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>12</h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-user-check"></i></div>
        <div class="card-info">
            <h3>10</h3>
            <p>Active Users</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-user-shield"></i></div>
        <div class="card-info">
            <h3>3</h3>
            <p>Roles</p>
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> System Users</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Admin User</strong></td>
                    <td>admin@tripwise.app</td>
                    <td>Administrator</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td>Just now</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Edit</button></td>
                </tr>
                <tr>
                    <td><strong>HR Manager</strong></td>
                    <td>hr@tripwise.app</td>
                    <td>Manager</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td>2 hours ago</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Edit</button></td>
                </tr>
                <tr>
                    <td><strong>Fleet Supervisor</strong></td>
                    <td>fleet@tripwise.app</td>
                    <td>Supervisor</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td>5 hours ago</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Edit</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
