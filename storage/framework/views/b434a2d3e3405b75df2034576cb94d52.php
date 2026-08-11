<?php $__env->startSection('title', 'TripWise — Skills Assessment'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.competency.index')); ?>">Competency Management</a>
    <span>/</span>
    <span>Skills Assessment</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Skills Assessment</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Assess every driver's competencies based on predefined competency standards.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('assessModal')"><i class="fas fa-plus"></i> New Assessment</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['avg_score']); ?></h3>
            <p>Average Competency Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['drivers_assessed']); ?></h3>
            <p>Drivers Assessed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['pending']); ?></h3>
            <p>Assessments Pending</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['completion_rate']); ?></h3>
            <p>Competency Completion Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.competency.assessments')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Competency</label>
            <select name="competency_id" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Competencies</option>
                <?php $__currentLoopData = $competencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($comp->id); ?>" <?php echo e(request('competency_id') == $comp->id ? 'selected' : ''); ?>><?php echo e($comp->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="assessed" <?php echo e(request('status') === 'assessed' ? 'selected' : ''); ?>>Assessed</option>
                <option value="reviewed" <?php echo e(request('status') === 'reviewed' ? 'selected' : ''); ?>>Reviewed</option>
                <option value="archived" <?php echo e(request('status') === 'archived' ? 'selected' : ''); ?>>Archived</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Assessment Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-tasks"></i> Skills Assessment</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Competency</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Assessment Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#DRV-<?php echo e(str_pad($assessment->driver_id, 4, '0', STR_PAD_LEFT)); ?></td>
                        <td><strong><?php echo e($assessment->driver->name ?? 'N/A'); ?></strong></td>
                        <td><?php echo e($assessment->competency->name ?? 'N/A'); ?></td>
                        <td><strong><?php echo e($assessment->score ?? 'N/A'); ?></strong></td>
                        <td>
                            <span class="item-badge <?php echo e($assessment->status === 'assessed' ? 'badge-success' : ($assessment->status === 'pending' ? 'badge-warning' : 'badge-info')); ?>">
                                <?php echo e(ucfirst($assessment->status)); ?>

                            </span>
                        </td>
                        <td><?php echo e($assessment->assessed_at ? \Carbon\Carbon::parse($assessment->assessed_at)->format('M d, Y') : 'N/A'); ?></td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-primary" title="Assess"><i class="fas fa-clipboard-check"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No assessments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($assessments->links()); ?>

    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Competency Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="compDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Skills Comparison</h3>
        <div class="chart-wrapper">
            <canvas id="skillsCompChart"></canvas>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
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

    new Chart(document.getElementById('compDistChart'), {
        type: 'pie',
        data: {
            labels: ['Excellent', 'Proficient', 'Developing', 'Needs Coaching'],
            datasets: [{
                data: [
                    <?php echo e($assessments->where('score', '>=', 90)->count()); ?>,
                    <?php echo e($assessments->whereBetween('score', [75, 89.99])->count()); ?>,
                    <?php echo e($assessments->whereBetween('score', [60, 74.99])->count()); ?>,
                    <?php echo e($assessments->where('score', '<', 60)->count()); ?>

                ],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('skillsCompChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($competencies->pluck('name')->toArray()); ?>,
            datasets: [{
                label: 'Average Score',
                data: <?php echo json_encode($competencies->map(fn($c) => $assessments->where('competency_id', $c->id)->avg('score') ?? 0)->toArray()); ?>,
                backgroundColor: '#F44336',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 100 } } }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/competency/skills-assessment.blade.php ENDPATH**/ ?>