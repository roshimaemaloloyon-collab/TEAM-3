@extends('admin.layouts.admin')

@section('title', 'TripWise — Settings')

@section('content')
<div class="page-header">
    <div>
        <h1>Settings</h1>
        <p>Configure system preferences, notifications, and general settings.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</a>
</div>

<div class="section-grid">
    <div class="section-card">
        <h3><i class="fas fa-cog"></i> General Settings</h3>
        <div class="list-item">
            <div class="item-content">
                <div class="item-title">System Name</div>
                <div class="item-subtitle">TripWise Driver Performance System</div>
            </div>
        </div>
        <div class="list-item">
            <div class="item-content">
                <div class="item-title">Default Language</div>
                <div class="item-subtitle">English (US)</div>
            </div>
        </div>
        <div class="list-item">
            <div class="item-content">
                <div class="item-title">Timezone</div>
                <div class="item-subtitle">Asia/Manila (GMT+8)</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <h3><i class="fas fa-bell"></i> Notification Settings</h3>
        <div class="list-item">
            <div class="item-content">
                <div class="item-title">Email Notifications</div>
                <div class="item-subtitle">Send system alerts via email</div>
            </div>
            <span class="item-badge badge-success">Enabled</span>
        </div>
        <div class="list-item">
            <div class="item-content">
                <div class="item-title">SMS Notifications</div>
                <div class="item-subtitle">Send urgent alerts via SMS</div>
            </div>
            <span class="item-badge badge-warning">Disabled</span>
        </div>
        <div class="list-item">
            <div class="item-content">
                <div class="item-title">Push Notifications</div>
                <div class="item-subtitle">Browser push notifications</div>
            </div>
            <span class="item-badge badge-success">Enabled</span>
        </div>
    </div>
</div>
@endsection
