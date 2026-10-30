

<?php $__env->startSection('title', 'Stock Material'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Topbar -->
    <div class="topbar" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-layers me-2"></i>Stock Material</h4>
                <p class="mb-0" style="opacity: 0.9;">Kelola stok material (CT & Masterbatch)</p>
            </div>
            <a href="<?php echo e(route('material.create')); ?>" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Tambah Material
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-layers"></i></div>
                <h3><?php echo e($totalMaterial); ?></h3>
                <p>Total Material</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-box-seam"></i></div>
                <h3><?php echo e(number_format($totalStok, 2)); ?></h3>
                <p>Total Stok</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-exclamation-triangle"></i></div>
                <h3><?php echo e($materialMenipis); ?></h3>
                <p>Stok Menipis</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger"><i class="bi bi-x-circle"></i></div>
                <h3><?php echo e($materialHabis); ?></h3>
                <p>Stok Habis</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-custom mt-4">
        <form action="<?php echo e(route('material.index')); ?>" method="GET">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Cari Material</label>
                    <input type="text" name="search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="Nama atau kode material...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="ct" <?php echo e(request('jenis') == 'ct' ? 'selected' : ''); ?>>CT (Carton)</option>
                        <option value="masterbatch" <?php echo e(request('jenis') == 'masterbatch' ? 'selected' : ''); ?>>Masterbatch</option>
                        <option value="lainnya" <?php echo e(request('jenis') == 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="tersedia" <?php echo e(request('status') == 'tersedia' ? 'selected' : ''); ?>>Tersedia</option>
                        <option value="menipis" <?php echo e(request('status') == 'menipis' ? 'selected' : ''); ?>>Menipis</option>
                        <option value="habis" <?php echo e(request('status') == 'habis' ? 'selected' : ''); ?>>Habis</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-info w-100"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>

    <!-- Grid Material -->
    <div class="row g-4 mt-2">
        <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 col-lg-4">
            <div class="card-custom" style="border-left: 4px solid <?php echo e($material->status == 'tersedia' ? '#10b981' : ($material->status == 'menipis' ? '#f59e0b' : '#ef4444')); ?>;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge <?php echo e($material->jenis == 'ct' ? 'bg-primary' : ($material->jenis == 'masterbatch' ? 'bg-purple' : 'bg-secondary')); ?>" 
                              style="<?php echo e($material->jenis == 'masterbatch' ? 'background: #8b5cf6;' : ''); ?>">
                            <?php echo e(strtoupper($material->jenis)); ?>

                        </span>
                        <h6 class="mt-2 mb-1"><?php echo e($material->nama_material); ?></h6>
                        <small class="text-muted"><?php echo e($material->kode_material); ?></small>
                    </div>
                    <span class="badge <?php echo e($material->status == 'tersedia' ? 'bg-success' : ($material->status == 'menipis' ? 'bg-warning text-dark' : 'bg-danger')); ?>">
                        <?php echo e(ucfirst($material->status)); ?>

                    </span>
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Stok Saat Ini</small>
                        <strong><?php echo e(number_format($material->stok, 2)); ?> <?php echo e($material->satuan); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Stok Minimum</small>
                        <span><?php echo e(number_format($material->stok_minimum, 2)); ?> <?php echo e($material->satuan); ?></span>
                    </div>
                    <?php if($material->harga_satuan): ?>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Harga Satuan</small>
                        <span>Rp <?php echo e(number_format($material->harga_satuan, 0, ',', '.')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if($material->lokasi): ?>
                <div class="mt-3 pt-3" style="border-top: 1px solid #e2e8f0;">
                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($material->lokasi); ?></small>
                </div>
                <?php endif; ?>

                <div class="d-flex gap-2 mt-3">
                    <a href="<?php echo e(route('material.edit', $material)); ?>" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="<?php echo e(route('material.destroy', $material)); ?>" method="POST" class="flex-fill"
                          onsubmit="return confirm('Yakin ingin menghapus material ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-layers" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">Belum ada data material</p>
                <a href="<?php echo e(route('material.create')); ?>" class="btn btn-info mt-2">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Material Pertama
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if($materials->hasPages()): ?>
    <div class="mt-4">
        <?php echo e($materials->links()); ?>

    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/material/index.blade.php ENDPATH**/ ?>