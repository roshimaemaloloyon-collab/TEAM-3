<?php $__env->startSection('title', 'TripWise — Assessment Results'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.competency.index')); ?>">Competency Management</a>
    <span>/</span>
    <span>Assessment Results</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Assessment Results</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Display competency assessment results and identify areas for improvement.</p>
    </div>
    <button class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print Assessment</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-trophy"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['high_competency']); ?></h3>
            <p>High Competency Drivers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-exclamation-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['needs_improvement']); ?></h3>
            <p>Drivers Requiring Improvement</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['avg_score']); ?></h3>
            <p>Average Assessment Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-puzzle-piece"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['skill_gaps']); ?></h3>
            <p>Skill Gap Count</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.competency.results')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="assessed" <?php echo e(request('status') === 'assessed' ? 'selected' : ''); ?>>Assessed</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="reviewed" <?php echo e(request('status') === 'reviewed' ? 'selected' : ''); ?>>Reviewed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Results Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Assessment Results</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Competency Score</th>
                    <th>Strengths</th>
                    <th>Weaknesses</th>
                    <th>Skill Gaps</th>
                    <th>Assessment Date</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($result->driver->name ?? 'N/A'); ?></strong></td>
                        <td><strong><?php echo e($result->score ?? 'N/A'); ?></strong></td>
                        <td>
                            <?php
                                $strengths = ['Safe Driving', 'Professionalism'];
                                echo implode(', ', $strengths);
                            ?>
                        </td>
                        <td>
                            <?php
                                $weaknesses = ['Time Management'];
                                echo implode(', ', $weaknesses);
                            ?>
                        </td>
                        <td>
                            <?php
                                $gaps = ['Navigation'];
                                echo implode(', ', $gaps);
                            ?>
                        </td>
                        <td><?php echo e($result->assessed_at ? \Carbon\Carbon::parse($result->assessed_at)->format('M d, Y') : 'N/A'); ?></td>
                        <td>
                            <span class="item-badge <?php echo e($result->status === 'assessed' ? 'badge-success' : ($result->status === 'pending' ? 'badge-warning' : 'badge-info')); ?>">
                                <?php echo e(ucfirst($result->status)); ?>

                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-secondary" title="Print" onclick="window.print()"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessment results found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($results->links()); ?>

    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Skill Gap Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="skillGapChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Competency Trend</h3>
        <div class="chart-wrapper">
            <canvas id="compTrendChart"></canvas>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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

    new Chart(document.getElementById('skillGapChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($skillGapData->pluck('competency.name')->toArray()); ?>,
            datasets: [{
                data: <?php echo json_encode($skillGapData->pluck('avg_score')->toArray()); ?>,
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#8b5cf6', '#f97316', '#14b8a6', '#f43f5e', '#84cc16', '#06b6d4']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('compTrendChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($trendData->pluck('month_num')->toArray()); ?>,
            datasets: [{
                label: 'Average Score',
                data: <?php echo json_encode($trendData->pluck('avg_score')->toArray()); ?>,
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/competency/assessment-results.blade.php ENDPATH**/ ?>