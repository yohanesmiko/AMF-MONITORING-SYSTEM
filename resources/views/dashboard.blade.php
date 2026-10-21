@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Dashboard Inventory</h4>
            <p class="text-muted mb-0">Selamat datang di sistem inventory ATMI MIKAEL FORTUNA</p>
        </div>
        <div>
            <button class="btn btn-primary-custom">
                <i class="bi bi-plus-circle me-2"></i>Tambah Barang
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3>{{ number_format($totalBarang) }}</h3>
                <p>Total Jenis Barang</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success">
                    <i class="bi bi-stack"></i>
                </div>
                <h3>{{ number_format($totalStok) }}</h3>
                <p>Total Stok Barang</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3>Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
                <p>Total Nilai Inventory</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3>{{ $stokMenipis }}</h3>
                <p>Stok Menipis</p>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="row g-4">
        <!-- Barang Terbaru -->
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Barang Terbaru Ditambahkan</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangTerbaru as $barang)
                            <tr>
                                <td><strong>{{ $barang->kode_barang }}</strong></td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td><span class="badge bg-light text-dark">{{ $barang->kategori }}</span></td>
                                <td>{{ $barang->stok }} {{ $barang->satuan }}</td>
                                <td>
                                    <span class="badge-status badge-{{ $barang->status }}">
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="col-lg-4">
            <div class="card-custom">
                <h5>
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Stok Menipis
                </h5>
                <div class="list-group list-group-flush">
                    @forelse($stokMenipisList as $barang)
                    <div class="list-group-item border-0 px-0 py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $barang->nama_barang }}</h6>
                                <small class="text-muted">{{ $barang->kode_barang }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">
                                {{ $barang->stok }} {{ $barang->satuan }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Semua stok aman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori & Status -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card-custom">
                <h5>Kategori Barang</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($kategori as $kat)
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        {{ $kat->kategori }} <span class="ms-1">({{ $kat->total }})</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-custom">
                <h5>Status Inventory</h5>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <h4 class="text-success mb-1">{{ $totalBarang - $barangHabis - $barangRusak }}</h4>
                            <small>Tersedia</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <h4 class="text-danger mb-1">{{ $barangHabis }}</h4>
                            <small>Habis</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <h4 class="text-warning mb-1">{{ $barangRusak }}</h4>
                            <small>Rusak</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection