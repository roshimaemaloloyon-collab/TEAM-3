<?php $__env->startSection('title', 'TripWise — Training Schedule'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.training.index')); ?>">Training Management</a>
    <span>/</span>
    <span>Training Schedule</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training Schedule</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Create, organize, and manage all driver training schedules.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addTrainingModal')"><i class="fas fa-plus"></i> Add Training</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['upcoming']); ?></h3>
            <p>Upcoming Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-spinner"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['ongoing']); ?></h3>
            <p>Ongoing Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['completed']); ?></h3>
            <p>Completed Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-layer-group"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['total']); ?></h3>
            <p>Total Training Sessions</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.training.schedule')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search trainings..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
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
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="upcoming" <?php echo e(request('status') === 'upcoming' ? 'selected' : ''); ?>>Upcoming</option>
                <option value="ongoing" <?php echo e(request('status') === 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Training Schedule Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-calendar-alt"></i> Training Schedule</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Training ID</th>
                    <th>Training Title</th>
                    <th>Category</th>
                    <th>Trainer</th>
                    <th>Venue</th>
                    <th>Schedule</th>
                    <th>Available Slots</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#TRN-<?php echo e(str_pad($training->id, 5, '0', STR_PAD_LEFT)); ?></td>
                        <td><strong><?php echo e($training->title); ?></strong></td>
                        <td style="text-transform:capitalize;"><?php echo e($training->category); ?></td>
                        <td><?php echo e($training->instructor); ?></td>
                        <td><?php echo e($training->venue ?? 'N/A'); ?></td>
                        <td><?php echo e($training->start_datetime->format('M d, Y h:i A')); ?></td>
                        <td><?php echo e($training->capacity); ?></td>
                        <td>
                            <span class="item-badge <?php echo e($training->status === 'upcoming' ? 'badge-info' : ($training->status === 'ongoing' ? 'badge-success' : ($training->status === 'completed' ? 'badge-success' : 'badge-danger'))); ?>">
                                <?php echo e(ucfirst($training->status)); ?>

                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Cancel"><i class="fas fa-times"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:2rem;">No trainings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($trainings->links()); ?>

    </div>
</div>

<!-- Calendar View -->
<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-calendar"></i> Training Calendar</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Week</th>
                    <th>Day</th>
                    <th>Training</th>
                    <th>Time</th>
                    <th>Instructor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $calendarData = [
                        ['month'=>'July 2026','week'=>'Week 3','day'=>'15','training'=>'Defensive Driving Workshop','time'=>'9:00 AM','instructor'=>'Internal SecOps','status'=>'upcoming'],
                        ['month'=>'July 2026','week'=>'Week 4','day'=>'22','training'=>'First Aid Certification','time'=>'1:00 PM','instructor'=>'Red Cross','status'=>'upcoming'],
                        ['month'=>'August 2026','week'=>'Week 1','day'=>'05','training'=>'Eco-Driving Techniques','time'=>'10:00 AM','instructor'=>'Fleet Mgmt','status'=>'upcoming'],
                    ];
                ?>
                <?php $__currentLoopData = $calendarData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($event['month']); ?></td>
                    <td><?php echo e($event['week']); ?></td>
                    <td><strong><?php echo e($event['day']); ?></strong></td>
                    <td><strong><?php echo e($event['training']); ?></strong></td>
                    <td><?php echo e($event['time']); ?></td>
                    <td><?php echo e($event['instructor']); ?></td>
                    <td>
                        <span class="item-badge badge-info"><?php echo e(ucfirst($event['status'])); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Training Schedule</h3>
        <div class="chart-wrapper">
            <canvas id="trainingScheduleChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Training Status Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="trainingStatusChart"></canvas>
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

    new Chart(document.getElementById('trainingScheduleChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($scheduleData->pluck('month_num')->toArray()); ?>,
            datasets: [{
                label: 'Trainings Scheduled',
                data: <?php echo json_encode($scheduleData->pluck('total')->toArray()); ?>,
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('trainingStatusChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($statusData->pluck('status')->toArray()); ?>,
            datasets: [{ data: <?php echo json_encode($statusData->pluck('total')->toArray()); ?>, backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/training/schedule.blade.php ENDPATH**/ ?>