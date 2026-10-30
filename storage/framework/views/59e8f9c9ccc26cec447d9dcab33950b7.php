

<?php $__env->startSection('title', 'Input Material Out'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Topbar -->
    <div class="topbar" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-box-arrow-up me-2"></i>Input Material Out</h4>
                <p class="mb-0" style="opacity: 0.9;">Catat pengeluaran material untuk produksi</p>
            </div>
            <div>
                <span class="badge bg-white text-dark me-2">
                    <i class="bi bi-calendar me-1"></i><?php echo e(\Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM YYYY')); ?>

                </span>
                <span class="badge bg-light text-dark">
                    <i class="bi bi-person me-1"></i><?php echo e(auth()->user()->name); ?>

                </span>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Statistik Hari Ini -->
    <div class="row g-4 mt-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-box-arrow-up"></i></div>
                <h3><?php echo e(number_format($stats['total_out'], 2)); ?></h3>
                <p>Total Out Hari Ini (kg)</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-receipt"></i></div>
                <h3><?php echo e($stats['total_transaksi']); ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-person-check"></i></div>
                <h3><?php echo e(number_format($stats['shift_ini'], 2)); ?></h3>
                <p>Out Shift <?php echo e(auth()->user()->shift ?? '1'); ?> (kg)</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- FORM INPUT (Kiri) -->
        <div class="col-lg-5">
            <div class="card-custom" style="position: sticky; top: 20px;">
                <h5 class="mb-4"><i class="bi bi-plus-circle-fill me-2 text-warning"></i>Form Input Material Out</h5>
                
                <form action="<?php echo e(route('material-out.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Tanggal & Jam -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" 
                                   value="<?php echo e(old('tanggal', $today)); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Out <span class="text-danger">*</span></label>
                            <input type="time" name="jam_out" class="form-control" 
                                   value="<?php echo e(old('jam_out', date('H:i'))); ?>" required>
                        </div>
                    </div>

                    <!-- Shift & Operator -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Shift <span class="text-danger">*</span></label>
                            <select name="shift" class="form-select" required>
                                <option value="1" <?php echo e(old('shift', auth()->user()->shift ?? '1') == '1' ? 'selected' : ''); ?>>Shift 1 (06:00-14:00)</option>
                                <option value="2" <?php echo e(old('shift', auth()->user()->shift ?? '1') == '2' ? 'selected' : ''); ?>>Shift 2 (14:00-22:00)</option>
                                <option value="3" <?php echo e(old('shift', auth()->user()->shift ?? '1') == '3' ? 'selected' : ''); ?>>Shift 3 (22:00-06:00)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Operator <span class="text-danger">*</span></label>
                            <select name="nama_operator" class="form-select" required>
                                <option value="">-- Pilih Operator --</option>
                                <?php $__currentLoopData = $operators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($op->name); ?>" <?php echo e(old('nama_operator', auth()->user()->name) == $op->name ? 'selected' : ''); ?>>
                                    <?php echo e($op->name); ?> (Shift <?php echo e($op->shift); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <!-- Produk & Mesin -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control" 
                                   value="<?php echo e(old('nama_produk')); ?>" placeholder="Contoh: Kardus Box 500gr" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Mesin</label>
                            <input type="text" name="nama_mesin" class="form-control" 
                                   value="<?php echo e(old('nama_mesin')); ?>" placeholder="Contoh: Mesin A-01">
                        </div>
                    </div>

                    <!-- Material -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Material <span class="text-danger">*</span></label>
                            <select name="material_id" id="materialSelect" class="form-select" required>
                                <option value="">-- Pilih Material --</option>
                                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($mat->id); ?>" 
                                        data-jenis="<?php echo e($mat->jenis); ?>" 
                                        data-stok="<?php echo e($mat->stok); ?>"
                                        data-satuan="<?php echo e($mat->satuan); ?>"
                                        <?php echo e(old('material_id') == $mat->id ? 'selected' : ''); ?>>
                                    <?php echo e($mat->nama_material); ?> (Stok: <?php echo e($mat->stok); ?> <?php echo e($mat->satuan); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Material <span class="text-danger">*</span></label>
                            <input type="text" name="jenis_material" id="jenisMaterial" class="form-control" 
                                   value="<?php echo e(old('jenis_material')); ?>" placeholder="Otomatis terisi" readonly required>
                        </div>
                    </div>

                    <!-- Warna & Qty -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warna</label>
                            <input type="text" name="warna" class="form-control" 
                                   value="<?php echo e(old('warna')); ?>" placeholder="Contoh: Putih, Hitam, Merah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Out Material (kg) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="qty_out" id="qtyOut" class="form-control" 
                                       value="<?php echo e(old('qty_out')); ?>" min="0.01" required>
                                <span class="input-group-text">kg</span>
                            </div>
                            <small class="text-muted" id="stokInfo">Pilih material untuk lihat stok</small>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" 
                                  placeholder="Catatan tambahan (opsional)"><?php echo e(old('keterangan')); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-save-fill me-2"></i>Simpan Material Out
                    </button>
                </form>
            </div>
        </div>

        <!-- LIST DATA (Kanan) -->
        <div class="col-lg-7">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-warning"></i>Riwayat Material Out</h5>
                    <a href="<?php echo e(route('material-out.index')); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </a>
                </div>

                <!-- Filter -->
                <form action="<?php echo e(route('material-out.index')); ?>" method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="date" name="tanggal" class="form-control form-control-sm" 
                                   value="<?php echo e(request('tanggal', $today)); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="shift" class="form-select form-select-sm">
                                <option value="">Semua Shift</option>
                                <option value="1" <?php echo e(request('shift') == '1' ? 'selected' : ''); ?>>Shift 1</option>
                                <option value="2" <?php echo e(request('shift') == '2' ? 'selected' : ''); ?>>Shift 2</option>
                                <option value="3" <?php echo e(request('shift') == '3' ? 'selected' : ''); ?>>Shift 3</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Cari..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No. Out</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Material</th>
                                <th>Qty (kg)</th>
                                <th>Shift</th>
                                <th>Operator</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $materialOuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $out): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong class="text-warning"><?php echo e($out->nomor_out); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo e($out->jam_out); ?></small>
                                </td>
                                <td><?php echo e($out->tanggal->format('d/m/Y')); ?></td>
                                <td>
                                    <strong><?php echo e($out->nama_produk); ?></strong>
                                    <?php if($out->nama_mesin): ?>
                                    <br><small class="text-muted"><i class="bi bi-gear"></i> <?php echo e($out->nama_mesin); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e($out->material->nama_material ?? '-'); ?>

                                    <?php if($out->warna): ?>
                                    <br><span class="badge bg-light text-dark"><?php echo e($out->warna); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="text-danger">-<?php echo e(number_format($out->qty_out, 2)); ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">S<?php echo e($out->shift); ?></span>
                                </td>
                                <td>
                                    <small><?php echo e($out->nama_operator); ?></small>
                                </td>
                                <td>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                    <form action="<?php echo e(route('material-out.destroy', $out)); ?>" method="POST" 
                                          onsubmit="return confirm('Hapus data ini? Stok akan dikembalikan.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">Belum ada data material out</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($materialOuts->hasPages()): ?>
                <div class="mt-3"><?php echo e($materialOuts->withQueryString()->links()); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill jenis material & info stok saat pilih material
        document.getElementById('materialSelect').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const jenisInput = document.getElementById('jenisMaterial');
            const stokInfo = document.getElementById('stokInfo');
            const qtyInput = document.getElementById('qtyOut');
            
            if (option.value) {
                const jenis = option.dataset.jenis;
                const stok = option.dataset.stok;
                const satuan = option.dataset.satuan;
                
                // Auto fill jenis (uppercase)
                jenisInput.value = jenis.toUpperCase();
                
                // Tampilkan info stok
                stokInfo.innerHTML = `<i class="bi bi-info-circle"></i> Stok tersedia: <strong>${parseFloat(stok).toFixed(2)} ${satuan}</strong>`;
                stokInfo.className = 'text-success';
                
                // Set max qty
                qtyInput.max = stok;
            } else {
                jenisInput.value = '';
                stokInfo.textContent = 'Pilih material untuk lihat stok';
                stokInfo.className = 'text-muted';
                qtyInput.max = '';
            }
        });

        // Trigger change event jika ada old value
        if (document.getElementById('materialSelect').value) {
            document.getElementById('materialSelect').dispatchEvent(new Event('change'));
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/material-out/index.blade.php ENDPATH**/ ?>