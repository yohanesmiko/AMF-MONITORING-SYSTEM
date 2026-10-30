

<?php $__env->startSection('title', 'Barang Masuk'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Topbar dengan Style Hijau -->
    <div class="topbar" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-cart-plus me-2"></i>Barang Masuk</h4>
                <p class="mb-0" style="opacity: 0.9;">Catat dan pantau semua barang yang masuk ke gudang</p>
            </div>
            <a href="<?php echo e(route('barang-masuk.create')); ?>" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Catat Barang Masuk
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
                <div class="icon success"><i class="bi bi-receipt"></i></div>
                <h3><?php echo e(number_format($totalTransaksi)); ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-box-seam"></i></div>
                <h3><?php echo e(number_format($totalBarang)); ?></h3>
                <p>Total Barang Masuk</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-cash-stack"></i></div>
                <h3>Rp <?php echo e(number_format($totalNilai, 0, ',', '.')); ?></h3>
                <p>Total Nilai</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger"><i class="bi bi-calendar-check"></i></div>
                <h3><?php echo e($transaksiHariIni); ?></h3>
                <p>Transaksi Hari Ini</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-custom mt-4">
        <form action="<?php echo e(route('barang-masuk.index')); ?>" method="GET">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cari Nomor Transaksi</label>
                    <input type="text" name="search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="BM-20261021-XXXXXX">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="<?php echo e(request('tanggal_dari')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="<?php echo e(request('tanggal_sampai')); ?>">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="<?php echo e(route('barang-masuk.index')); ?>" class="btn btn-outline-secondary ms-2">Reset</a>
            </div>
        </form>
    </div>

    <!-- Timeline View -->
    <div class="card-custom mt-4">
        <h5 class="mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Barang Masuk</h5>
        
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="timeline-item" style="display: flex; gap: 20px; padding: 20px 0; <?php echo e($index < $transactions->count() - 1 ? 'border-bottom: 1px solid #e2e8f0;' : ''); ?>">
            <div class="timeline-icon" style="width: 50px; height: 50px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-arrow-down-circle text-success" style="font-size: 1.5rem;"></i>
            </div>
            <div class="timeline-content" style="flex: 1;">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div style="flex: 1;">
                        <h6 class="mb-1">
                            <strong><?php echo e($transaction->inventory->nama_barang ?? 'Barang tidak ditemukan'); ?></strong>
                            <span class="badge bg-success ms-2"><?php echo e($transaction->nomor_transaksi); ?></span>
                        </h6>
                        <p class="text-muted mb-1 small">
                            <i class="bi bi-calendar me-1"></i><?php echo e($transaction->tanggal->format('d M Y')); ?>

                            <?php if($transaction->supplier): ?>
                            | <i class="bi bi-building me-1"></i><?php echo e($transaction->supplier->nama_supplier); ?>

                            <?php endif; ?>
                            <?php if($transaction->user): ?>
                            | <i class="bi bi-person me-1"></i><?php echo e($transaction->user->name); ?>

                            <?php endif; ?>
                        </p>
                        <?php if($transaction->keterangan): ?>
                        <p class="mb-0 small"><i class="bi bi-chat-left-text me-1"></i><?php echo e($transaction->keterangan); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-end mt-2 mt-md-0">
                        <h5 class="text-success mb-1">+<?php echo e($transaction->jumlah); ?> <?php echo e($transaction->inventory->satuan ?? ''); ?></h5>
                        <small class="text-muted">Rp <?php echo e(number_format(($transaction->harga ?? 0) * $transaction->jumlah, 0, ',', '.')); ?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
            <p class="text-muted mt-3">Belum ada transaksi barang masuk</p>
            <a href="<?php echo e(route('barang-masuk.create')); ?>" class="btn btn-success mt-2">
                <i class="bi bi-plus-circle me-2"></i>Catat Transaksi Pertama
            </a>
        </div>
        <?php endif; ?>

        <?php if($transactions->hasPages()): ?>
        <div class="mt-3">
            <?php echo e($transactions->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/barang-masuk/index.blade.php ENDPATH**/ ?>