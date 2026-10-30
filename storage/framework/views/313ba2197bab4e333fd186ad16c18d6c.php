

<?php $__env->startSection('title', 'Purchase Order'); ?>

<?php $__env->startSection('content'); ?>
    <div class="topbar" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-file-earmark-text me-2"></i>Purchase Order</h4>
                <p class="mb-0" style="opacity: 0.9;">Kelola PO berdasarkan quantity kebutuhan</p>
            </div>
            <a href="<?php echo e(route('po.create')); ?>" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Buat PO Baru
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Statistik -->
    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-file-earmark-text"></i></div>
                <h3><?php echo e($stats['total_po']); ?></h3>
                <p>Total PO</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-pencil"></i></div>
                <h3><?php echo e($stats['draft']); ?></h3>
                <p>Draft</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon info"><i class="bi bi-send"></i></div>
                <h3><?php echo e($stats['terkirim']); ?></h3>
                <p>Terkirim</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-cash-stack"></i></div>
                <h3>Rp <?php echo e(number_format($stats['total_nilai'], 0, ',', '.')); ?></h3>
                <p>Total Nilai PO</p>
            </div>
        </div>
    </div>

    <!-- Tabel PO -->
    <div class="card-custom mt-4">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Material</th>
                        <th>Qty</th>
                        <th>Total Harga</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $purchaseOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($po->nomor_po); ?></strong></td>
                        <td><?php echo e($po->material->nama_material); ?></td>
                        <td><?php echo e(number_format($po->qty, 2)); ?> <?php echo e($po->material->satuan); ?></td>
                        <td>Rp <?php echo e(number_format($po->total_harga, 0, ',', '.')); ?></td>
                        <td><?php echo e($po->supplier); ?></td>
                        <td><?php echo e($po->tanggal_po->format('d/m/Y')); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($po->status == 'selesai' ? 'success' : ($po->status == 'draft' ? 'secondary' : 'primary')); ?>">
                                <?php echo e(ucfirst($po->status)); ?>

                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo e(route('po.show', $po)); ?>"><i class="bi bi-eye me-2"></i>Detail</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('po.edit', $po)); ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Update Status</h6></li>
                                    <li>
                                        <form action="<?php echo e(route('po.update-status', $po)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="draft" <?php echo e($po->status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                                <option value="terkirim" <?php echo e($po->status == 'terkirim' ? 'selected' : ''); ?>>Terkirim</option>
                                                <option value="diproses" <?php echo e($po->status == 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                                                <option value="selesai" <?php echo e($po->status == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                                <option value="dibatalkan" <?php echo e($po->status == 'dibatalkan' ? 'selected' : ''); ?>>Batal</option>
                                            </select>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-file-earmark-text" style="font-size: 3rem;"></i>
                            <p class="mt-3">Belum ada Purchase Order</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($purchaseOrders->hasPages()): ?>
        <div class="mt-3"><?php echo e($purchaseOrders->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/po/index.blade.php ENDPATH**/ ?>