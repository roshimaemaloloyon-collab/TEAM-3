<?php $__env->startSection('title', 'TripWise — Training History'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.training.index')); ?>">Training Management</a>
    <span>/</span>
    <span>Training History</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training History</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Maintain complete historical records of all training activities.</p>
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
            <h3><?php echo e($stats['historical_trainings']); ?></h3>
            <p>Historical Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-archive"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['archived_sessions']); ?></h3>
            <p>Archived Sessions</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-stream"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['timeline_events']); ?></h3>
            <p>Training Timeline</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['completed_programs']); ?></h3>
            <p>Completed Programs</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.training.history')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Training</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by title..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- History Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-history"></i> Training History</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Training</th>
                    <th>Trainer</th>
                    <th>Schedule</th>
                    <th>Completion Date</th>
                    <th>Participants</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($training->title); ?></strong></td>
                        <td><?php echo e($training->instructor); ?></td>
                        <td><?php echo e($training->start_datetime->format('M d, Y')); ?></td>
                        <td><?php echo e($training->end_datetime->format('M d, Y')); ?></td>
                        <td><?php echo e($training->capacity); ?></td>
                        <td>
                            <span class="item-badge badge-success"><?php echo e(ucfirst($training->status)); ?></span>
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
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No training history found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($history->links()); ?>

    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Training Timeline</h3>
        <div class="chart-wrapper">
            <canvas id="trainingTimelineChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Historical Completion Trend</h3>
        <div class="chart-wrapper">
            <canvas id="historicalCompletionChart"></canvas>
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

    new Chart(document.getElementById('trainingTimelineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($timelineData->pluck('month_num')->toArray()); ?>,
            datasets: [{
                label: 'Trainings Completed',
                data: <?php echo json_encode($timelineData->pluck('total')->toArray()); ?>,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('historicalCompletionChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($trendData->pluck('month_num')->toArray()); ?>,
            datasets: [{
                label: 'Completion %',
                data: <?php echo json_encode($trendData->pluck('total')->toArray()); ?>,
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

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/training/history.blade.php ENDPATH**/ ?>