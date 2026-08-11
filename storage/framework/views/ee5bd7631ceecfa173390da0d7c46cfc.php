<?php $__env->startSection('title', 'TripWise — Vehicle Information'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo e(route('admin.drivers.index')); ?>">Manage Drivers</a>
    <span>/</span>
    <span>Vehicle Information</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Vehicle Information Management</h1>
        <p>Fleet vehicle assignments, plate registration records, maintenance status, and vehicle route distribution.</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <button class="btn btn-primary" onclick="showToast('Vehicle assignment modal opened')"><i class="fas fa-car"></i> Assign New Vehicle</button>
    </div>
</div>

<!-- Fleet Statistics Cards -->
<div class="summary-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1.25rem;margin-bottom:1.5rem;">
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-car-side"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:var(--primary);"><?php echo e($drivers->count()); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Total Fleet Vehicles</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-key"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#059669;"><?php echo e(intval($drivers->count() * 0.85)); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Active & On-Route</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-tools"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#ea580c;"><?php echo e(intval($drivers->count() * 0.1) + 1); ?></h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Scheduled Maintenance</p>
        </div>
    </div>
    <div class="table-card" style="padding:1.25rem;display:flex;align-items:center;gap:1rem;">
        <div style="width:48px;height:48px;border-radius:12px;background:#f3e8ff;color:#9333ea;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
            <i class="fas fa-route"></i>
        </div>
        <div>
            <h3 style="font-size:1.5rem;margin:0;color:#9333ea;">5 Branches</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Active Operating Zones</p>
        </div>
    </div>
</div>

<!-- Vehicle Information Table -->
<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Vehicle Code</th>
                    <th>Plate Number</th>
                    <th>Vehicle Model</th>
                    <th>Type</th>
                    <th>Assigned Driver</th>
                    <th>Branch Zone</th>
                    <th>Route</th>
                    <th>Vehicle Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong>VH-2026-<?php echo e(str_pad($driver->id, 3, '0', STR_PAD_LEFT)); ?></strong></td>
                    <td><span style="font-family:monospace;font-weight:700;letter-spacing:1px;background:#f1f5f9;padding:4px 8px;border-radius:4px;border:1px solid #cbd5e1;"><?php echo e(strtoupper(substr($driver->last_name ?? 'ABC', 0, 3))); ?>-<?php echo e(1000 + $driver->id); ?></span></td>
                    <td><strong><?php echo e($driver->vehicle_assignment ?? 'Toyota Hiace'); ?></strong></td>
                    <td><?php echo e($driver->vehicle_type ?? 'Van'); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.drivers.profile', $driver->id)); ?>" style="display:flex;align-items:center;gap:0.5rem;color:inherit;text-decoration:none;">
                            <img src="<?php echo e($driver->photo ?: asset('drivers/photo/' . $driver->id)); ?>" alt="<?php echo e($driver->first_name); ?>" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            <span><?php echo e($driver->full_name); ?></span>
                        </a>
                    </td>
                    <td><?php echo e($driver->branch ?? 'North Branch'); ?></td>
                    <td><?php echo e($driver->route_assignment ?? 'Main Route'); ?></td>
                    <td>
                        <?php if($index % 7 == 0): ?>
                            <span class="status-badge" style="background:#ffedd5;color:#c2410c;">🛠 Under Maintenance</span>
                        <?php else: ?>
                            <span class="status-badge" style="background:#d1fae5;color:#065f46;">🟢 Active & Operational</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:0.35rem;justify-content:center;">
                            <a href="<?php echo e(route('admin.drivers.profile', ['id' => $driver->id, 'tab' => 'tab-vehicle'])); ?>" class="icon-btn" title="Vehicle Details"><i class="fas fa-eye"></i></a>
                            <button class="icon-btn" title="Reassign Driver" onclick="showToast('Reassign vehicle modal opened')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align:center;padding:2rem;">No vehicle records found.</td>
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

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/vehicles.blade.php ENDPATH**/ ?>