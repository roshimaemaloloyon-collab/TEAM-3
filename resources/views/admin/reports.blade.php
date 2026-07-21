@extends('admin.layouts.admin')

@section('title', 'TripWise — Reports & Analytics')

@section('content')
<div class="page-header">
    <div>
        <h1>Reports & Analytics</h1>
        <p>Generate and download comprehensive reports on driver performance and system metrics.</p>
    </div>
    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Generate Report</a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-file-alt"></i></div>
        <div class="card-info">
            <h3>24</h3>
            <p>Reports Generated</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-download"></i></div>
        <div class="card-info">
            <h3>156</h3>
            <p>Total Downloads</p>
        </div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> Available Reports</h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Report Name</th>
                    <th>Type</th>
                    <th>Generated</th>
                    <th>Format</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Monthly Performance Summary</strong></td>
                    <td>Performance</td>
                    <td>July 1, 2026</td>
                    <td>PDF</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;"><i class="fas fa-download"></i> Download</button></td>
                </tr>
                <tr>
                    <td><strong>Training Completion Report</strong></td>
                    <td>Training</td>
                    <td>June 30, 2026</td>
                    <td>Excel</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;"><i class="fas fa-download"></i> Download</button></td>
                </tr>
                <tr>
                    <td><strong>Competency Gap Analysis</strong></td>
                    <td>Competency</td>
                    <td>June 28, 2026</td>
                    <td>PDF</td>
                    <td><button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;"><i class="fas fa-download"></i> Download</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
