<?php $__env->startSection('title', 'TripWise — Training Analytics'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.training.index')); ?>">Training Management</a>
    <span>/</span>
    <span>Training Analytics</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training Analytics</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Analyze training participation, completion, and effectiveness.</p>
    </div>
    <button class="btn btn-primary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export Report</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['completion_rate']); ?></h3>
            <p>Overall Completion Rate</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['avg_attendance']); ?></h3>
            <p>Average Attendance</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-chart-bar"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['avg_evaluation']); ?></h3>
            <p>Average Evaluation Score</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-chart-area"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['effectiveness']); ?></h3>
            <p>Training Effectiveness</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.training.analytics')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Category</label>
            <select name="category" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Categories</option>
                <option value="safety" <?php echo e(request('category') === 'safety' ? 'selected' : ''); ?>>Safety</option>
                <option value="technical" <?php echo e(request('category') === 'technical' ? 'selected' : ''); ?>>Technical</option>
                <option value="soft_skills" <?php echo e(request('category') === 'soft_skills' ? 'selected' : ''); ?>>Soft Skills</option>
                <option value="compliance" <?php echo e(request('category') === 'compliance' ? 'selected' : ''); ?>>Compliance</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Trainer</label>
            <select name="trainer" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Trainers</option>
                <?php $__currentLoopData = \App\Models\Training::select('instructor')->distinct()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($trainer->instructor); ?>" <?php echo e(request('trainer') == $trainer->instructor ? 'selected' : ''); ?>><?php echo e($trainer->instructor); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Year</label>
            <select name="year" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="2026" <?php echo e(request('year') == '2026' ? 'selected' : ''); ?>>2026</option>
                <option value="2025" <?php echo e(request('year') == '2025' ? 'selected' : ''); ?>>2025</option>
                <option value="2024" <?php echo e(request('year') == '2024' ? 'selected' : ''); ?>>2024</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Apply Filters</button>
    </form>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Training Completion</h3>
        <div class="chart-wrapper">
            <canvas id="trainingCompletionChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Category Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="categoryDistChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Completion Trend</h3>
        <div class="chart-wrapper">
            <canvas id="completionTrendChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-area"></i> Attendance Trend</h3>
        <div class="chart-wrapper">
            <canvas id="attendanceTrendAnalyticsChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Comparative Analysis</h3>
        <div class="chart-wrapper">
            <canvas id="trainingComparativeChart"></canvas>
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

    new Chart(document.getElementById('trainingCompletionChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($trainingCompletion->pluck('category')->toArray()); ?>,
            datasets: [{
                label: 'Completion %',
                data: <?php echo json_encode($trainingCompletion->pluck('total')->toArray()); ?>,
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    new Chart(document.getElementById('categoryDistChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($categoryDist->pluck('category')->toArray()); ?>,
            datasets: [{ data: <?php echo json_encode($categoryDist->pluck('total')->toArray()); ?>, backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('completionTrendChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($completionTrend->pluck('month_num')->toArray()); ?>,
            datasets: [{
                label: 'Completion %',
                data: <?php echo json_encode($completionTrend->pluck('total')->toArray()); ?>,
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F44336'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('attendanceTrendAnalyticsChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($attendanceTrend->pluck('month_num')->toArray()); ?>,
            datasets: [{
                label: 'Attendance %',
                data: <?php echo json_encode($attendanceTrend->pluck('total')->toArray()); ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#3b82f6'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('trainingComparativeChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($comparative->pluck('category')->toArray()); ?>,
            datasets: [
                { label: 'Completion', data: <?php echo json_encode($comparative->pluck('total')->toArray()); ?>, backgroundColor: '#F44336', borderRadius: 8 },
                { label: 'Attendance', data: <?php echo json_encode($attendanceTrend->pluck('total')->toArray()); ?>, backgroundColor: '#3b82f6', borderRadius: 8 }
            ]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/training/analytics.blade.php ENDPATH**/ ?>