<?php $__env->startSection('title', 'TripWise — KPI Monitoring'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.performance.index')); ?>">Performance Management</a>
    <span>/</span>
    <span>KPI Monitoring</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">KPI Monitoring</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor Key Performance Indicators (KPIs) for every driver.</p>
    </div>
    <button class="btn btn-primary"><i class="fas fa-plus"></i> Add KPI</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-bullseye"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['avg_kpi']); ?></h3>
            <p>Average KPI Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['target_achievement']); ?></h3>
            <p>Target Achievement Rate</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['meeting_kpi']); ?></h3>
            <p>Drivers Meeting KPI</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-times-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['below_target']); ?></h3>
            <p>Drivers Below Target</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.performance.kpi')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search KPIs..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Category</label>
            <select name="category" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Categories</option>
                <option value="safety" <?php echo e(request('category') === 'safety' ? 'selected' : ''); ?>>Safety</option>
                <option value="attendance" <?php echo e(request('category') === 'attendance' ? 'selected' : ''); ?>>Attendance</option>
                <option value="customer_service" <?php echo e(request('category') === 'customer_service' ? 'selected' : ''); ?>>Customer Service</option>
                <option value="efficiency" <?php echo e(request('category') === 'efficiency' ? 'selected' : ''); ?>>Efficiency</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- KPI Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-bullseye"></i> KPI Overview</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>KPI Score</th>
                    <th>Monthly Target</th>
                    <th>Progress</th>
                    <th>Achievement %</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($kpi->driver->name ?? 'N/A'); ?></strong></td>
                        <td><strong><?php echo e($kpi->actual_value ?? 'N/A'); ?></strong></td>
                        <td><?php echo e($kpi->target_value); ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div class="progress-bar" style="width:100px;height:8px;">
                                    <div class="progress-fill" style="width:<?php echo e($kpi->achievement_percentage ?? 0); ?>%;"></div>
                                </div>
                                <span style="font-size:0.85rem;font-weight:600;"><?php echo e($kpi->achievement_percentage ?? 0); ?>%</span>
                            </div>
                        </td>
                        <td><strong><?php echo e($kpi->achievement_percentage ?? 0); ?>%</strong></td>
                        <td>
                            <span class="item-badge <?php echo e($kpi->status === 'achieved' ? 'badge-success' : ($kpi->status === 'missed' ? 'badge-danger' : 'badge-warning')); ?>">
                                <?php echo e(ucfirst($kpi->status)); ?>

                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit KPI"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-primary" title="Update Target"><i class="fas fa-bullseye"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No KPI records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($kpis->links()); ?>

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
        <h3><i class="fas fa-chart-line"></i> KPI Trend</h3>
        <div class="chart-wrapper">
            <canvas id="kpiTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Target Achievement</h3>
        <div class="chart-wrapper">
            <canvas id="targetChart"></canvas>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/performance/kpi-monitoring.blade.php ENDPATH**/ ?>