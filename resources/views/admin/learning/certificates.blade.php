@extends('admin.layouts.admin')

@section('title', 'TripWise — Certificates')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Certificates</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Certificates</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage certificates awarded after successful completion of learning modules.</p>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-certificate"></i></div>
        <div class="card-info">
            <h3>{{ $stats['issued'] }}</h3>
            <p>Certificates Issued</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Pending Certificates</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active'] }}</h3>
            <p>Active Certificates</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['expired'] }}</h3>
            <p>Expired Certificates</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.learning.certificates') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Certificates Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-certificate"></i> Certificates</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Certificate No.</th>
                    <th>Driver</th>
                    <th>Learning Module</th>
                    <th>Date Issued</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $certificate)
                    <tr>
                        <td>#CERT-{{ str_pad($certificate->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $certificate->driver->name ?? 'N/A' }}</strong></td>
                        <td>{{ $certificate->training->title ?? 'N/A' }}</td>
                        <td>{{ $certificate->issue_date ? \Carbon\Carbon::parse($certificate->issue_date)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="item-badge {{ $certificate->status === 'issued' ? 'badge-success' : ($certificate->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($certificate->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Download PDF"><i class="fas fa-file-pdf"></i></button>
                                <button class="btn btn-sm btn-secondary" title="Print" onclick="window.print()"><i class="fas fa-print"></i></button>
                                <button class="btn btn-sm btn-primary" title="Reissue"><i class="fas fa-redo"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No certificates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $certificates->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Certificates Issued Per Month</h3>
        <div class="chart-wrapper">
            <canvas id="certsPerMonthChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Certificate Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="certDistChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('certsPerMonthChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Certificates Issued',
                data: [12, 18, 15, 22, 19, 25],
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('certDistChart'), {
        type: 'pie',
        data: {
            labels: ['Road Safety', 'Defensive Driving', 'Customer Service', 'Vehicle Maintenance'],
            datasets: [{ data: [30, 25, 20, 25], backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });
});
</script>
@endsection
