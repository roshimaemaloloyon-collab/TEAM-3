<?php $__env->startSection('title', 'TripWise — Performance Reviews'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.performance.index')); ?>">Performance Management</a>
    <span>/</span>
    <span>Performance Reviews</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Performance Reviews</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage periodic driver performance evaluations.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addReviewModal')"><i class="fas fa-plus"></i> Create Review</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['completed']); ?></h3>
            <p>Reviews Completed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['pending']); ?></h3>
            <p>Pending Reviews</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-calendar"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['monthly']); ?></h3>
            <p>Monthly Reviews</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3><?php echo e($stats['quarterly']); ?></h3>
            <p>Quarterly Reviews</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="<?php echo e(route('admin.performance.reviews')); ?>" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Review Type</label>
            <select name="type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Types</option>
                <option value="monthly" <?php echo e(request('type') === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                <option value="quarterly" <?php echo e(request('type') === 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                <option value="annual" <?php echo e(request('type') === 'annual' ? 'selected' : ''); ?>>Annual</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                <option value="archived" <?php echo e(request('status') === 'archived' ? 'selected' : ''); ?>>Archived</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Reviews Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Performance Reviews</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Review Type</th>
                    <th>Review Date</th>
                    <th>Performance Score</th>
                    <th>Reviewer</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($review->driver->name ?? 'N/A'); ?></strong></td>
                        <td style="text-transform:capitalize;"><?php echo e($review->review_type); ?></td>
                        <td><?php echo e($review->review_date ? \Carbon\Carbon::parse($review->review_date)->format('M d, Y') : 'N/A'); ?></td>
                        <td><strong><?php echo e($review->performance_score ?? 'N/A'); ?>/5</strong></td>
                        <td><?php echo e($review->reviewer->name ?? 'N/A'); ?></td>
                        <td>
                            <span class="item-badge <?php echo e($review->status === 'completed' ? 'badge-success' : ($review->status === 'pending' ? 'badge-warning' : 'badge-info')); ?>">
                                <?php echo e(ucfirst($review->status)); ?>

                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-primary" title="Submit Review"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No reviews found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        <?php echo e($reviews->links()); ?>

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
        <h3><i class="fas fa-chart-bar"></i> Review Completion</h3>
        <div class="chart-wrapper">
            <canvas id="reviewChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Performance Review Trend</h3>
        <div class="chart-wrapper">
            <canvas id="reviewTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- Add Review Modal -->
<div id="addReviewModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Create Performance Review</h2>
            <button onclick="closeModal('addReviewModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.25rem 1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Review Type</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Monthly</option><option>Quarterly</option><option>Annual</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Performance Score</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommendations</label>
                <textarea rows="3" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);resize:vertical;"></textarea>
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addReviewModal')">Cancel</button>
            <button class="btn btn-primary" onclick="closeModal('addReviewModal');showToast('Review created successfully.');">Save Review</button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/performance/performance-reviews.blade.php ENDPATH**/ ?>