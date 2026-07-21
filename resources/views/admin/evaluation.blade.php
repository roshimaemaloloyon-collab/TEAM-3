@extends('admin.layouts.admin')

@section('title', 'TripWise — Peer-to-Peer Evaluation Review')

@section('content')
<div class="page-header">
    <div>
        <h1>Peer-to-Peer Evaluation Review</h1>
        <p>Review and manage peer evaluations submitted by drivers across the organization.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-download"></i> Export Report</a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-clipboard-check"></i></div>
        <div class="card-info">
            <h3>156</h3>
            <p>Total Evaluations</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check"></i></div>
        <div class="card-info">
            <h3>132</h3>
            <p>Completed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-hourglass-half"></i></div>
        <div class="card-info">
            <h3>24</h3>
            <p>Pending Review</p>
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> Recent Evaluations</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Evaluator</th>
                    <th>Reviewed Driver</th>
                    <th>Date</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Juan Dela Cruz</strong></td>
                    <td>Maria Santos</td>
                    <td>July 10, 2026</td>
                    <td>4.8</td>
                    <td><span class="status-badge status-success">Approved</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View</button></td>
                </tr>
                <tr>
                    <td><strong>Pedro Reyes</strong></td>
                    <td>Ana Lim</td>
                    <td>July 9, 2026</td>
                    <td>4.5</td>
                    <td><span class="status-badge status-success">Approved</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View</button></td>
                </tr>
                <tr>
                    <td><strong>Rosa Garcia</strong></td>
                    <td>Luis Tan</td>
                    <td>July 11, 2026</td>
                    <td>3.9</td>
                    <td><span class="status-badge status-pending">Pending</span></td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Review</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
