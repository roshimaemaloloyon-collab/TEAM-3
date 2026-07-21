@extends('admin.layouts.admin')

@section('title', 'TripWise — Evaluation Reports')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.evaluation.index') }}">Peer-to-Peer Evaluation</a>
    <span>/</span>
    <span>Evaluation Reports</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Evaluation Reports</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Generate professional peer evaluation reports. Export in PDF, Excel, or Print format.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
        <button class="btn btn-primary" onclick="openModal('generateReportModal')"><i class="fas fa-plus"></i> Generate Report</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-file-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['reports_generated'] }}</h3>
            <p>Reports Generated</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['monthly_reports'] }}</h3>
            <p>Monthly Reports</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3>{{ number_format($stats['avg_evaluation_score'] ?? 0, 2) }}</h3>
            <p>Average Evaluation Score</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <form method="GET" action="{{ route('admin.evaluation.reports') }}" class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <input type="text" name="search" placeholder="Search reports..." value="{{ request('search') }}" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:200px;">
        <select name="type" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Types</option>
            <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="department" {{ request('type') === 'department' ? 'selected' : '' }}>Department</option>
            <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="quarterly" {{ request('type') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('admin.evaluation.reports') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Reports Table -->
<div class="table-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;"><i class="fas fa-list"></i> Generated Reports</h3>
        <span style="font-size:0.85rem;color:var(--text-muted);">Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} results</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Report Type</th>
                    <th>Title</th>
                    <th>Export Format</th>
                    <th>Generated At</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#RPT-{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td><span class="status-badge status-review">{{ ucfirst($report->report_type) }}</span></td>
                    <td><strong>{{ $report->title }}</strong></td>
                    <td>
                        @if($report->export_format)
                            <span class="status-badge status-{{ $report->export_format === 'pdf' ? 'pending' : ($report->export_format === 'excel' ? 'success' : 'review') }}">{{ strtoupper($report->export_format) }}</span>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>{{ $report->generated_at ? $report->generated_at->format('M d, Y H:i') : '-' }}</td>
                    <td style="text-align:center;">
                        <button class="icon-btn" title="View" onclick="showToast('View report')"><i class="fas fa-eye"></i></button>
                        <button class="icon-btn" title="Download" onclick="showToast('Download report')"><i class="fas fa-download"></i></button>
                        <button class="icon-btn" title="Print" onclick="showToast('Print report')"><i class="fas fa-print"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:var(--text-muted);">No reports found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;display:flex;justify-content:center;">
        {{ $reports->links() }}
    </div>
</div>

<!-- Generate Report Modal -->
<div id="generateReportModal" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:var(--primary);margin:0;"><i class="fas fa-file-alt"></i> Generate Evaluation Report</h2>
            <button class="icon-btn" onclick="closeModal('generateReportModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.evaluation.reports.store') }}">
            @csrf
            <div style="display:grid;gap:1rem;">
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Report Type</label>
                    <select name="report_type" required style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="">-- Select Type --</option>
                        <option value="individual">Individual Driver Report</option>
                        <option value="department">Department Report</option>
                        <option value="monthly">Monthly Report</option>
                        <option value="quarterly">Quarterly Report</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Report Title</label>
                    <input type="text" name="title" required placeholder="e.g., Q1 2026 Peer Evaluation Report" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                </div>
                <div>
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Export Format</label>
                    <select name="export_format" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                        <option value="">-- Select Format --</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="print">Print</option>
                    </select>
                </div>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('generateReportModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-magic"></i> Generate</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function exportReport(format) {
    showToast('Exporting as ' + format.toUpperCase() + '...');
}
</script>
@endpush
