<?php $__env->startSection('title', 'TripWise — Driver Documents'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.drivers.index')); ?>">Manage Drivers</a>
    <span>/</span>
    <span>Driver Documents</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Driver Documents Management</h1>
        <p>Centralized repository for all driver licenses, clearance certificates, vehicle registrations, and compliance documents.</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <button class="btn btn-primary" onclick="openModal('uploadDocModal')"><i class="fas fa-upload"></i> Upload New Document</button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="summary-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1.25rem;margin-bottom:1.5rem;">
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:var(--primary);"><?php echo e($drivers->count() * 4); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Total Submitted Documents</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#059669;"><?php echo e(intval($drivers->count() * 3.2)); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Verified Documents</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#ea580c;"><?php echo e(intval($drivers->count() * 0.6)); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Pending Verification</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#dc2626;"><?php echo e(intval($drivers->count() * 0.2) + 1); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Expired / Needing Action</p>
        </div>
    </div>
</div>

<!-- Search & Filter Controls -->
<div class="table-card" style="margin-bottom:1.5rem;">
    <form method="GET" action="<?php echo e(route('admin.drivers.documents')); ?>" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
        <div style="flex:1;min-width:240px;">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search driver name, ID, or document type..." style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
        </div>
        <div style="width:180px;">
            <select name="type" style="width:100%;padding:0.6rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;">
                <option value="">All Document Types</option>
                <option value="license">Driver's License</option>
                <option value="orcr">OR / CR</option>
                <option value="nbi">NBI Clearance</option>
                <option value="police">Police Clearance</option>
                <option value="medical">Medical Certificate</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    </form>
</div>

<!-- Documents Master Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Driver Photo</th>
                    <th>Driver Name</th>
                    <th>Driver ID</th>
                    <th>Document Type</th>
                    <th>Issue / Upload Date</th>
                    <th>Expiration Date</th>
                    <th>Verification Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <a href="<?php echo e(route('admin.drivers.profile', $driver->id)); ?>">
                            <img src="<?php echo e($driver->photo ?: asset('drivers/photo/' . $driver->id)); ?>" alt="<?php echo e($driver->first_name); ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.drivers.profile', $driver->id)); ?>" style="color:inherit;text-decoration:none;">
                            <strong><?php echo e($driver->full_name); ?></strong>
                        </a>
                    </td>
                    <td><strong><?php echo e($driver->formatted_id); ?></strong></td>
                    <td>
                        <?php if($index % 4 == 0): ?>
                            <i class="fas fa-id-card" style="color:#0284c7;margin-right:0.4rem;"></i> Driver's License
                        <?php elseif($index % 4 == 1): ?>
                            <i class="fas fa-file-alt" style="color:#059669;margin-right:0.4rem;"></i> OR / CR (<?php echo e($driver->vehicle_assignment); ?>)
                        <?php elseif($index % 4 == 2): ?>
                            <i class="fas fa-file-contract" style="color:#ea580c;margin-right:0.4rem;"></i> NBI Clearance
                        <?php else: ?>
                            <i class="fas fa-notes-medical" style="color:#8b5cf6;margin-right:0.4rem;"></i> Medical Certificate
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($driver->created_at ? $driver->created_at->format('M d, Y') : 'Jan 10, 2026'); ?></td>
                    <td><?php echo e($driver->license_expiration ? \Carbon\Carbon::parse($driver->license_expiration)->format('M d, Y') : 'Dec 20, 2026'); ?></td>
                    <td>
                        <?php if($index % 5 == 0): ?>
                            <span class="status-badge" style="background:#ffedd5;color:#c2410c;">🟡 Pending Review</span>
                        <?php elseif($index % 6 == 0): ?>
                            <span class="status-badge" style="background:#fee2e2;color:#991b1b;">🔴 Expired</span>
                        <?php else: ?>
                            <span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Verified</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.35rem;justify-content:center;">
                            <a href="<?php echo e(route('admin.drivers.profile', ['id' => $driver->id, 'tab' => 'tab-documents'])); ?>" class="icon-btn" title="View Document"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Download" onclick="showToast('Downloading document for <?php echo e($driver->first_name); ?>...')"><i class="fas fa-download"></i></button>
                            <button class="icon-btn" title="Update Status" onclick="showToast('Verification status updated.')"><i class="fas fa-check"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;">No driver documents found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:1.25rem;">
        <?php echo e($drivers->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/documents.blade.php ENDPATH**/ ?>