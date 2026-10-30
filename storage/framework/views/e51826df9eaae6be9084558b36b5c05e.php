

<?php $__env->startSection('title', 'Inventory'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Inventory Material</h4>
            <p class="mb-0">Selamat datang kembali, <?php echo e(auth()->user()->name ?? 'Admin'); ?>! </p>
        </div>
        <div>
            <a href="<?php echo e(route('material.create')); ?>" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg me-2"></i>Tambah Material
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon primary">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <h3><?php echo e(number_format($totalMaterial)); ?></h3>
                <p>Total Jenis Material</p>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon success">
                    <i class="bi bi-stack"></i>
                </div>
                <h3><?php echo e(number_format($totalStok, 2)); ?></h3>
                <p>Total Stok Material</p>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3 style="font-size: 1.4rem;">Rp <?php echo e(number_format($totalNilai, 0, ',', '.')); ?></h3>
                <p>Total Nilai Material</p>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h3><?php echo e($materialMenipis); ?></h3>
                <p>Material Menipis</p>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card-custom">
                <h5><i class="bi bi-pie-chart-fill me-2" style="color: var(--primary);"></i>Distribusi Stok per Kategori</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-custom">
                <h5><i class="bi bi-bar-chart-fill me-2" style="color: var(--success);"></i>Nilai Material per Kategori</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="nilaiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card-custom">
                <h5><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i>Status Material</h5>
                <div style="position: relative; height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-custom">
                <h5><i class="bi bi-trophy-fill me-2" style="color: var(--warning);"></i>Top 10 Material dengan Stok Terbanyak</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="topStokChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2" style="color: var(--primary);"></i>Material Terbaru Ditambahkan</h5>
                    <a href="<?php echo e(route('material.index')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Material</th>
                                <th>Jenis</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $materialTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($material->kode_material); ?></strong></td>
                                <td><?php echo e($material->nama_material); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($material->jenis == 'ct' ? 'primary' : ($material->jenis == 'masterbatch' ? 'info' : 'secondary')); ?>">
                                        <?php echo e(strtoupper($material->jenis)); ?>

                                    </span>
                                </td>
                                <td><?php echo e(number_format($material->stok, 2)); ?> <?php echo e($material->satuan); ?></td>
                                <td>
                                    <span class="badge-status badge-<?php echo e($material->status); ?>">
                                        <?php echo e(ucfirst($material->status)); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">Belum ada data material</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom">
                <h5>
                    <i class="bi bi-exclamation-triangle-fill me-2" style="color: var(--warning);"></i>
                    Material Stok Menipis
                </h5>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $stokMenipisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-group-item border-0 px-0 py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?php echo e($material->nama_material); ?></h6>
                                <small class="text-muted"><?php echo e($material->kode_material); ?></small>
                            </div>
                            <span class="badge bg-danger rounded-pill">
                                <?php echo e(number_format($material->stok, 2)); ?> <?php echo e($material->satuan); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: var(--success);"></i>
                        <p class="mt-2 mb-0">Semua stok material aman</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const kategoriLabels = <?php echo json_encode($kategoriData->pluck('kategori'), 15, 512) ?>;
        const kategoriData = <?php echo json_encode($kategoriData->pluck('total_stok'), 15, 512) ?>;
        const nilaiLabels = <?php echo json_encode($nilaiKategori->pluck('kategori'), 15, 512) ?>;
        const nilaiData = <?php echo json_encode($nilaiKategori->pluck('total_nilai'), 15, 512) ?>;
        const statusLabels = ['Tersedia', 'Menipis', 'Habis'];
        const statusData = [
            <?php echo json_encode($statusData['tersedia'], 15, 512) ?>, 
            <?php echo json_encode($statusData['menipis'], 15, 512) ?>, 
            <?php echo json_encode($statusData['habis'], 15, 512) ?>
        ];
        const topStokLabels = <?php echo json_encode($topStok->pluck('nama_material'), 15, 512) ?>;
        const topStokData = <?php echo json_encode($topStok->pluck('stok'), 15, 512) ?>;

        const colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#8b5cf6', '#f97316'];

        // Chart 1: Doughnut - Stok per Kategori
        if (kategoriLabels.length > 0) {
            new Chart(document.getElementById('kategoriChart'), {
                type: 'doughnut',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        data: kategoriData,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } }
                    }
                }
            });
        } else {
            document.getElementById('kategoriChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }

        // Chart 2: Bar - Nilai per Kategori
        if (nilaiLabels.length > 0) {
            new Chart(document.getElementById('nilaiChart'), {
                type: 'bar',
                data: {
                    labels: nilaiLabels,
                    datasets: [{
                        label: 'Nilai (Rp)',
                        data: nilaiData,
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        } else {
            document.getElementById('nilaiChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }

        // Chart 3: Doughnut - Status Material
        if (statusData.some(v => v > 0)) {
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { padding: 15 } } }
                }
            });
        } else {
            document.getElementById('statusChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }

        // Chart 4: Horizontal Bar - Top 10 Stok
        if (topStokLabels.length > 0) {
            new Chart(document.getElementById('topStokChart'), {
                type: 'bar',
                data: {
                    labels: topStokLabels,
                    datasets: [{
                        label: 'Stok',
                        data: topStokData,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        } else {
            document.getElementById('topStokChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/dashboard.blade.php ENDPATH**/ ?>