

<?php $__env->startSection('title', 'Produksi Shift'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">🏭 Produksi Shift - Monitoring</h4>
                <p class="mb-0">Pencatatan produksi 3 shift dengan cycle time</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('produksi-shift.setup')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-gear me-2"></i>Setup Produk
                </a>
                <a href="<?php echo e(route('produksi-shift.laporan', ['product_id' => $defaultProduct->id ?? 1, 'tanggal' => $today])); ?>" class="btn btn-primary-custom">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Harian
                </a>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success mt-3 alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Pilih Produk (Dengan Tombol Edit/Hapus) -->
    <?php if($products->count() > 1): ?>
    <div class="card-custom mb-4">
        <h5><i class="bi bi-box-seam me-2"></i>Pilih Produk</h5>
        <div class="row g-3">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card-custom position-relative" style="cursor: pointer; transition: transform 0.2s;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1"><?php echo e($product->nama_produk); ?></h6>
                            <small class="text-muted"><?php echo e($product->kode_produk); ?></small>
                        </div>
                        <!-- Dropdown Menu Edit/Hapus -->
                        <div class="dropdown" style="z-index: 10; position: relative;">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('produksi-shift.edit-produk', $product->id)); ?>">
                                        <i class="bi bi-pencil me-2"></i>Edit Produk
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('produksi-shift.destroy-produk', $product->id)); ?>" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini? Data produksi terkait mungkin terpengaruh.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <span class="badge bg-info">CT: <?php echo e($product->cycle_time_standar); ?>s</span>
                        <span class="badge bg-primary">Target: <?php echo e(number_format($product->target_per_shift)); ?>/shift</span>
                    </div>

                    <!-- Link ini membuat seluruh card bisa diklik untuk memilih produk -->
                    <a href="<?php echo e(route('produksi-shift.index', ['product_id' => $product->id])); ?>" class="stretched-link"></a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($defaultProduct): ?>
    <!-- Info Produk dengan Cycle Time yang BISA DIEDIT -->
    <div class="card-custom mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-1"><?php echo e($defaultProduct->nama_produk); ?></h4>
                <p class="text-muted mb-0"><?php echo e($defaultProduct->deskripsi ?? 'Produk produksi reguler'); ?></p>
                
                <div class="mt-3">
                    <h6 class="mb-2">📦 Material per Unit:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $defaultProduct->materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-circle-fill text-<?php echo e($loop->index == 0 ? 'primary' : 'success'); ?> me-1"></i>
                            <?php echo e($pm->material->nama_material); ?>: <strong><?php echo e(number_format($pm->qty_per_unit, 2)); ?> <?php echo e($pm->satuan); ?></strong>
                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 text-end">
                <div class="p-3 rounded" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);">
                    <small class="text-muted d-block mb-1">Cycle Time Standar (Detik/Unit)</small>
                    
                    <!-- Form Edit Cycle Time -->
                    <form action="<?php echo e(route('produksi-shift.update-cycle-time', $defaultProduct->id)); ?>" method="POST" class="d-flex align-items-center justify-content-end gap-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="input-group input-group-sm" style="max-width: 180px;">
                            <input type="number" step="0.01" name="cycle_time_standar" 
                                   class="form-control text-end fw-bold" 
                                   value="<?php echo e($defaultProduct->cycle_time_standar); ?>" 
                                   min="0.01" required>
                            <span class="input-group-text">s</span>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary" title="Simpan Cycle Time">
                            <i class="bi bi-check-lg"></i>
                        </button>
                    </form>
                    
                    <div class="mt-2">
                        <small class="text-muted">Target per Shift:</small>
                        <h5 class="mb-0 text-primary fw-bold"><?php echo e(number_format($defaultProduct->target_per_shift)); ?> unit</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status 3 Shift Hari Ini (Tanpa Jam) -->
    <h5 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Status Shift Hari Ini (<?php echo e(\Carbon\Carbon::parse($today)->format('d/m/Y')); ?>)</h5>
    <div class="row g-4 mb-4">
        <?php $__currentLoopData = ['1' => '🌅 Shift 1', '2' => '☀️ Shift 2', '3' => '🌙 Shift 3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shiftNum => $shiftName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4">
            <div class="card-custom" style="border-top: 4px solid <?php echo e($shiftsToday[$shiftNum] ? '#10b981' : '#cbd5e1'); ?>;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="mb-0"><?php echo e($shiftName); ?></h6>
                    <?php if($shiftsToday[$shiftNum]): ?>
                        <span class="badge bg-success">✅ Terinput</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">⏳ Belum</span>
                    <?php endif; ?>
                </div>

                <?php if($shiftsToday[$shiftNum]): ?>
                    <?php $run = $shiftsToday[$shiftNum]; ?>
                    <div class="mb-2">
                        <small class="text-muted">Output Aktual</small>
                        <h4 class="mb-0"><?php echo e(number_format($run->output_aktual, 0)); ?> <small class="text-muted">unit</small></h4>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Efisiensi</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-<?php echo e($run->efisiensi >= 90 ? 'success' : ($run->efisiensi >= 70 ? 'warning' : 'danger')); ?>" 
                                 style="width: <?php echo e(min(100, $run->efisiensi)); ?>%"></div>
                        </div>
                        <small><strong><?php echo e(number_format($run->efisiensi, 1)); ?>%</strong> (Target: <?php echo e(number_format($run->target_output, 0)); ?>)</small>
                    </div>
                    <div class="small text-muted">
                        <div>⏱️ CT Aktual: <?php echo e(number_format($run->cycle_time_aktual, 1)); ?>s</div>
                        <div>🔧 Downtime: <?php echo e($run->downtime_menit); ?> menit</div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history" style="font-size: 2rem; color: #cbd5e1;"></i>
                        <p class="text-muted small mt-2">Belum ada input</p>
                    </div>
                <?php endif; ?>

                <a href="<?php echo e(route('produksi-shift.create', ['product_id' => $defaultProduct->id, 'shift' => $shiftNum, 'tanggal' => $today])); ?>" 
                   class="btn btn-<?php echo e($shiftsToday[$shiftNum] ? 'outline-primary' : 'primary'); ?> w-100 mt-3">
                    <i class="bi bi-<?php echo e($shiftsToday[$shiftNum] ? 'pencil' : 'plus-circle'); ?> me-2"></i>
                    <?php echo e($shiftsToday[$shiftNum] ? 'Edit Input' : 'Input Produksi'); ?>

                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="card-custom text-center py-5">
        <i class="bi bi-box-seam" style="font-size: 3rem; color: #cbd5e1;"></i>
        <p class="text-muted mt-3">Belum ada produk produksi. Silakan setup terlebih dahulu.</p>
        <a href="<?php echo e(route('produksi-shift.setup')); ?>" class="btn btn-primary-custom">Setup Produk</a>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/produksi-shift/index.blade.php ENDPATH**/ ?>