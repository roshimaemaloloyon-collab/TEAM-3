@extends('admin.layouts.admin')

@section('title', 'TripWise — Certificates')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Certificates</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Certificates</h1>
        <p>View and manage training certificates issued to drivers.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="showToast('Issue new certificate')"><i class="fas fa-certificate"></i> Issue Certificate</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting PDF...')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="showToast('Exporting Excel...')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Certificates Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Certificate Number</th>
                    <th>Driver Name</th>
                    <th>Training Name</th>
                    <th>Date Issued</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#CERT-2026-0001</strong></td>
                    <td>Juan Dela Cruz</td>
                    <td>Defensive Driving Workshop</td>
                    <td>July 15, 2026</td>
                    <td><span class="status-badge status-active">Issued</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View certificate')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Download" onclick="showToast('Downloading PDF...')"><i class="fas fa-download"></i></button>
                            <button class="icon-btn" title="Print" onclick="showToast('Printing certificate...')"><i class="fas fa-print"></i></button>
                            <button class="icon-btn" title="Reissue" onclick="showToast('Reissue certificate')"><i class="fas fa-redo"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CERT-2026-0002</strong></td>
                    <td>Maria Santos</td>
                    <td>Customer Service Excellence</td>
                    <td>July 18, 2026</td>
                    <td><span class="status-badge status-active">Issued</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <button class="icon-btn" title="View" onclick="showToast('View certificate')"><i class="fas fa-eye"></i></button>
                            <button class="icon-btn" title="Download" onclick="showToast('Downloading PDF...')"><i class="fas fa-download"></i></button>
                            <button class="icon-btn" title="Print" onclick="showToast('Printing certificate...')"><i class="fas fa-print"></i></button>
                            <button class="icon-btn" title="Reissue" onclick="showToast('Reissue certificate')"><i class="fas fa-redo"></i></button>
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
