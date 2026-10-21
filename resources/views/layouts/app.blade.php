<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ATMI MIKAEL FORTUNA</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f5f9;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            padding: 20px 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar .brand {
            padding: 0 20px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar .brand h3 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
        }

        .sidebar .brand small {
            color: #94a3b8;
            font-size: 0.75rem;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background: rgba(37, 99, 235, 0.1);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .stat-card .icon.primary { background: rgba(37, 99, 235, 0.1); color: var(--primary-color); }
        .stat-card .icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .stat-card .icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .stat-card .icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0 5px;
            color: var(--dark-color);
        }

        .stat-card p {
            color: var(--secondary-color);
            margin: 0;
            font-size: 0.9rem;
        }

        .card-custom {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: none;
        }

        .card-custom h5 {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
        }

        .table-custom {
            margin: 0;
        }

        .table-custom thead th {
            background: #f8fafc;
            border: none;
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-custom tbody td {
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-tersedia { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .badge-habis { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }
        .badge-rusak { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }

        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
       .card-custom canvas {
    max-height: 100%;
}

.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
 }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h3>ATMI MIKAEL</h3>
            <small>FORTUNA INVENTORY</small>
        </div>
        
       <nav class="nav flex-column">
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i>
        <span>Inventory</span>
    </a>
    <a href="{{ route('barang-masuk.index') }}" class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
        <i class="bi bi-cart-plus"></i>
        <span>Barang Masuk</span>
    </a>
    <a href="{{ route('barang-keluar.index') }}" class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
        <i class="bi bi-cart-dash"></i>
        <span>Barang Keluar</span>
    </a>
    <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Supplier</span>
    </a>
    <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <span>Laporan</span>
    </a>
    <a href="{{ route('pengaturan.index') }}" class="nav-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
        <i class="bi bi-gear"></i>
        <span>Pengaturan</span>
    </a>
</nav>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>