<?php $__env->startSection('title', 'TripWise — Performance History'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.performance.index')); ?>">Performance Management</a>
    <span>/</span>
    <span>Performance History</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Performance History</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Maintain complete historical records of driver performance.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-database"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['historical_records']); ?></h3>
            <p>Historical Records</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-archive"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['archived_reviews']); ?></h3>
            <p>Archived Reviews</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-stream"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['timeline_events']); ?></h3>
            <p>Performance Timeline</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-sort-numeric-up-alt"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['ranking_changes']); ?></h3>
            <p>Ranking Changes</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.performance.history')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Record Type</label>
            <select name="record_type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Types</option>
                <option value="snapshot" <?php echo e(request('record_type') === 'snapshot' ? 'selected' : ''); ?>>Snapshot</option>
                <option value="review" <?php echo e(request('record_type') === 'review' ? 'selected' : ''); ?>>Review</option>
                <option value="kpi_update" <?php echo e(request('record_type') === 'kpi_update' ? 'selected' : ''); ?>>KPI Update</option>
                <option value="ranking_change" <?php echo e(request('record_type') === 'ranking_change' ? 'selected' : ''); ?>>Ranking Change</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- History Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-history"></i> Performance History</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Performance Score</th>
                    <th>KPI Score</th>
                    <th>Review Date</th>
                    <th>Ranking</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($history->driver->name ?? 'N/A'); ?></strong></td>
                        <td><strong><?php echo e($history->overall_score ?? 'N/A'); ?></strong></td>
                        <td><?php echo e($history->kpi_score ?? 'N/A'); ?></td>
                        <td><?php echo e($history->recorded_at ? \Carbon\Carbon::parse($history->recorded_at)->format('M d, Y') : 'N/A'); ?></td>
                        <td>#<?php echo e($history->ranking ?? 'N/A'); ?></td>
                        <td>
                            <span class="item-badge <?php echo e($history->performance_status === 'excellent' ? 'badge-success' : ($history->performance_status === 'good' ? 'badge-info' : 'badge-warning')); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $history->performance_status))); ?>

                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Archive"><i class="fas fa-archive"></i></button>
                                <button class="btn btn-sm btn-primary" title="Restore"><i class="fas fa-undo"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No history records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($histories->links()); ?>

    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;background:var(--charcoal);color:#fff;padding:0.75rem 1.25rem;border-radius:0.75rem;box-shadow:0 8px 20px rgba(0,0,0,0.2);z-index:3000;align-items:center;gap:0.75rem;font-size:0.85rem;font-family:'Inter',sans-serif;">
    <i class="fas fa-check-circle" style="color:var(--success);"></i>
    <span id="toastMessage"></span>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Performance Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="perfTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Historical KPI Trend</h3>
        <div class="chart-wrapper">
            <canvas id="kpiHistoryChart"></canvas>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function exportReport(format) { showToast('Exporting ' + format.toUpperCase() + ' report...'); }
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

    new Chart(document.getElementById('perfTimelineChart'), {
        type: 'line',
        data: {
            labels: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025', 'Q1 2026'],
            datasets: [{
                label: 'Performance Score',
                data: [4.5, 4.6, 4.7, 4.8, 4.9],
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('kpiHistoryChart'), {
        type: 'line',
        data: {
            labels: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025', 'Q1 2026'],
            datasets: [{
                label: 'KPI Score',
                data: [85, 87, 88, 90, 92],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#3b82f6'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/performance/performance-history.blade.php ENDPATH**/ ?>