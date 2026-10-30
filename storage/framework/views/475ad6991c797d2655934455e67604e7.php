<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - ATMI MIKAEL FORTUNA</title>
    
    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5; --primary-dark: #4338ca; --primary-light: #e0e7ff;
            --success: #10b981; --success-light: #d1fae5;
            --danger: #ef4444; --danger-light: #fee2e2;
            --warning: #f59e0b; --warning-light: #fef3c7;
            --info: #06b6d4; --info-light: #cffafe;
            --dark: #0f172a; --gray-50: #f8fafc; --gray-100: #f1f5f9;
            --gray-200: #e2e8f0; --gray-300: #cbd5e1; --gray-400: #94a3b8;
            --gray-500: #64748b; --gray-600: #475569; --gray-700: #334155;
            --gray-800: #1e293b; --gray-900: #0f172a;
            --sidebar-width: 280px; --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --radius: 10px; --radius-lg: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--gray-100); color: var(--gray-800); font-size: 14px; line-height: 1.6; overflow-x: hidden; }
        
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width); background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); z-index: 1000; overflow-y: auto; box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1); }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .sidebar .brand { padding: 28px 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); margin-bottom: 8px; background: rgba(0, 0, 0, 0.2); }
        .sidebar .brand h3 { color: white; font-weight: 800; margin: 0; font-size: 1.4rem; letter-spacing: 0.5px; }
        .sidebar .brand small { color: #94a3b8; font-size: 0.7rem; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; display: block; margin-top: 4px; }
        .sidebar .brand .user-info { margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); }
        .sidebar .brand .user-info small { color: #a5b4fc; font-size: 0.8rem; letter-spacing: 0; text-transform: none; }
        .sidebar .nav-section { padding: 16px 24px 8px; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; }
        .sidebar .nav-link { color: #cbd5e1; padding: 13px 24px; display: flex; align-items: center; gap: 14px; transition: all 0.25s ease; border-left: 3px solid transparent; text-decoration: none; font-weight: 500; font-size: 0.92rem; }
        .sidebar .nav-link:hover { background: rgba(255, 255, 255, 0.05); color: white; border-left-color: var(--primary); padding-left: 28px; }
        .sidebar .nav-link.active { background: linear-gradient(90deg, rgba(79, 70, 229, 0.15) 0%, transparent 100%); color: white; border-left-color: var(--primary); }
        .sidebar .nav-link i { font-size: 1.15rem; width: 22px; text-align: center; opacity: 0.85; }
        .sidebar .nav-link.active i { opacity: 1; color: #a5b4fc; }
        
        .main-content { margin-left: var(--sidebar-width); padding: 32px; min-height: 100vh; width: calc(100% - var(--sidebar-width)); background: var(--gray-100); }
        .topbar { background: white; padding: 28px 32px; border-radius: var(--radius-lg); box-shadow: var(--shadow); width: 100%; border: 1px solid var(--gray-200); margin-bottom: 24px; }
        .topbar h4 { font-weight: 700; color: var(--gray-900); font-size: 1.5rem; margin: 0; }
        .topbar p { color: var(--gray-500); margin: 4px 0 0; font-size: 0.9rem; }
        
        .stat-card { background: white; border-radius: var(--radius-lg); padding: 26px; box-shadow: var(--shadow); transition: all 0.3s; border: 1px solid var(--gray-200); width: 100%; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--primary) 0%, #818cf8 100%); opacity: 0; transition: opacity 0.3s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .stat-card:hover::before { opacity: 1; }
        .stat-card .icon { width: 54px; height: 54px; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 16px; }
        .stat-card .icon.primary { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: var(--primary); }
        .stat-card .icon.success { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: var(--success); }
        .stat-card .icon.warning { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: var(--warning); }
        .stat-card .icon.danger { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: var(--danger); }
        .stat-card .icon.info { background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%); color: var(--info); }
        .stat-card h3 { font-size: 2rem; font-weight: 800; margin: 8px 0 4px; color: var(--gray-900); letter-spacing: -0.5px; }
        .stat-card p { color: var(--gray-500); margin: 0; font-size: 0.85rem; font-weight: 500; }
        
        .card-custom { background: white; border-radius: var(--radius-lg); padding: 28px; box-shadow: var(--shadow); border: 1px solid var(--gray-200); width: 100%; margin-bottom: 24px; }
        .card-custom h5 { font-weight: 700; color: var(--gray-900); margin-bottom: 20px; font-size: 1.1rem; }
        .table-custom { margin: 0; width: 100%; font-size: 0.9rem; }
        .table-custom thead th { background: var(--gray-50); border: none; font-weight: 700; color: var(--gray-600); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.8px; padding: 14px 16px; border-bottom: 2px solid var(--gray-200); }
        .table-custom tbody td { vertical-align: middle; border-color: var(--gray-100); padding: 14px 16px; color: var(--gray-700); }
        .table-custom tbody tr:hover { background: var(--gray-50); }
        
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .badge-tersedia { background: var(--success-light); color: #065f46; }
        .badge-habis { background: var(--danger-light); color: #991b1b; }
        .badge-rusak, .badge-menipis { background: var(--warning-light); color: #92400e; }
        
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border: none; padding: 11px 24px; border-radius: var(--radius); font-weight: 600; color: white; transition: all 0.25s; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); text-decoration: none; display: inline-flex; align-items: center; }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35); color: white; }
        
        .form-label { font-weight: 600; color: var(--gray-700); margin-bottom: 6px; font-size: 0.85rem; }
        .form-control, .form-select { border-radius: var(--radius); border: 1.5px solid var(--gray-200); padding: 11px 14px; transition: all 0.2s; font-size: 0.9rem; background: white; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        
        .alert { border-radius: var(--radius); border: none; padding: 16px 20px; font-weight: 500; margin-bottom: 20px; }
        .alert-success { background: var(--success-light); color: #065f46; }
        .alert-danger { background: var(--danger-light); color: #991b1b; }
        .alert-warning { background: var(--warning-light); color: #92400e; }
        
        .bg-purple { background: #8b5cf6 !important; color: white !important; }
        
        @media (max-width: 992px) { .sidebar { width: 100%; height: auto; position: relative; } .main-content { margin-left: 0; width: 100%; padding: 20px; } }
        @media (max-width: 576px) { .main-content { padding: 16px; } .topbar { padding: 20px; } .stat-card h3 { font-size: 1.6rem; } }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .card-custom, .stat-card, .topbar { animation: fadeIn 0.4s ease-out; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--gray-100); }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h3>ATMI MIKAEL FORTUNA</h3>
            <small>Inventory System</small>
            <div class="user-info">
                <small><i class="bi bi-person-circle me-1"></i><?php echo e(auth()->user()->name); ?></small><br>
                <span class="badge <?php echo e(auth()->user()->role === 'admin' ? 'bg-danger' : 'bg-primary'); ?>" style="font-size: 0.65rem; margin-top: 4px;">
                    <?php echo e(strtoupper(auth()->user()->role)); ?>

                </span>
            </div>
        </div>

        <?php if(auth()->user()->role === 'admin'): ?>
            <!-- ===== MENU KHUSUS ADMIN ===== -->
            <div class="nav-section">Menu Utama</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
                </a>
                <a href="<?php echo e(route('material.index')); ?>" class="nav-link <?php echo e(request()->routeIs('material.*') ? 'active' : ''); ?>">
                    <i class="bi bi-layers-fill"></i><span>Stock Material</span>
                </a>
                <a href="<?php echo e(route('po.index')); ?>" class="nav-link <?php echo e(request()->routeIs('po.*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-earmark-text-fill"></i><span>Purchase Order</span>
                </a>
            </nav>

            <div class="nav-section">Transaksi</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('barang-masuk.index')); ?>" class="nav-link <?php echo e(request()->routeIs('barang-masuk.*') ? 'active' : ''); ?>">
                    <i class="bi bi-arrow-down-circle-fill"></i><span>Barang Masuk</span>
                </a>
                <a href="<?php echo e(route('barang-keluar.index')); ?>" class="nav-link <?php echo e(request()->routeIs('barang-keluar.*') ? 'active' : ''); ?>">
                    <i class="bi bi-arrow-up-circle-fill"></i><span>Barang Keluar</span>
                </a>
                <a href="<?php echo e(route('wip.index')); ?>" class="nav-link <?php echo e(request()->routeIs('wip.*') ? 'active' : ''); ?>">
                    <i class="bi bi-gear-wide-connected"></i><span>Barang WIP</span>
                </a>
            </nav>

            <div class="nav-section">Analisis & Laporan</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('laporan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('laporan.*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Laporan</span>
                </a>
                <a href="<?php echo e(route('kalkulasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('kalkulasi.*') ? 'active' : ''); ?>">
                    <i class="bi bi-calculator-fill"></i><span>Kalkulasi</span>
                </a>
            </nav>

            <div class="nav-section">Pengaturan</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('supplier.index')); ?>" class="nav-link <?php echo e(request()->routeIs('supplier.*') ? 'active' : ''); ?>">
                    <i class="bi bi-people-fill"></i><span>Supplier</span>
                </a>
                <a href="<?php echo e(route('pengaturan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pengaturan.*') ? 'active' : ''); ?>">
                    <i class="bi bi-gear-fill"></i><span>Pengaturan</span>
                </a>
            </nav>

        <?php else: ?>
            <!-- ===== MENU KHUSUS OPERATOR ===== -->
            <div class="nav-section">Menu Utama</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('operator.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('operator.dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-house-fill"></i><span>Dashboard</span>
                </a>
            </nav>

            <div class="nav-section">Produksi Harian</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('material-out.index')); ?>" class="nav-link <?php echo e(request()->routeIs('material-out.*') ? 'active' : ''); ?>">
                    <i class="bi bi-box-arrow-up-fill"></i><span>Input Material Out</span>
                </a>
                <a href="<?php echo e(route('produksi-shift.index')); ?>" class="nav-link <?php echo e(request()->routeIs('produksi-shift.*') ? 'active' : ''); ?>">
                    <i class="bi bi-play-circle-fill"></i><span>Input Produksi Shift</span>
                </a>
            </nav>

            <div class="nav-section">Monitoring</div>
            <nav class="nav flex-column">
                <a href="<?php echo e(route('material.index')); ?>" class="nav-link <?php echo e(request()->routeIs('material.index') ? 'active' : ''); ?>">
                    <i class="bi bi-eye-fill"></i><span>Lihat Stok Material</span>
                </a>
                <a href="<?php echo e(route('barang-masuk.index')); ?>" class="nav-link <?php echo e(request()->routeIs('barang-masuk.*') ? 'active' : ''); ?>">
                    <i class="bi bi-arrow-down-circle-fill"></i><span>Barang Masuk</span>
                </a>
                <a href="<?php echo e(route('barang-keluar.index')); ?>" class="nav-link <?php echo e(request()->routeIs('barang-keluar.*') ? 'active' : ''); ?>">
                    <i class="bi bi-arrow-up-circle-fill"></i><span>Barang Keluar</span>
                </a>
            </nav>
        <?php endif; ?>

        <!-- Logout (Untuk Semua Role) -->
        <div style="padding: 20px 24px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto;">
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link" style="width: 100%; border: none; background: none; cursor: pointer; color: #fca5a5;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/layouts/app.blade.php ENDPATH**/ ?>