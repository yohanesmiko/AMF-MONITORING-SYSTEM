@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('content')
    <!-- Topbar dengan Style Merah -->
    <div class="topbar" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-cart-dash me-2"></i>Barang Keluar</h4>
                <p class="mb-0" style="opacity: 0.9;">Catat dan pantau semua barang yang keluar dari gudang</p>
            </div>
            <a href="{{ route('barang-keluar.create') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Catat Barang Keluar
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger"><i class="bi bi-receipt"></i></div>
                <h3>{{ number_format($totalTransaksi) }}</h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-box-seam"></i></div>
                <h3>{{ number_format($totalBarang) }}</h3>
                <p>Total Barang Keluar</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-cash-stack"></i></div>
                <h3>Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
                <p>Total Nilai</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-calendar-check"></i></div>
                <h3>{{ $transaksiHariIni }}</h3>
                <p>Transaksi Hari Ini</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-custom mt-4">
        <form action="{{ route('barang-keluar.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cari Nomor Transaksi</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="BK-20261021-XXXXXX">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
            </div>
        </form>
    </div>

    <!-- Timeline View (Sama seperti Barang Masuk) -->
    <div class="card-custom mt-4">
        <h5 class="mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Barang Keluar</h5>
        
        @forelse($transactions as $index => $transaction)
        <div class="timeline-item" style="display: flex; gap: 20px; padding: 20px 0; {{ $index < $transactions->count() - 1 ? 'border-bottom: 1px solid #e2e8f0;' : '' }}">
            <div class="timeline-icon" style="width: 50px; height: 50px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-arrow-up-circle text-danger" style="font-size: 1.5rem;"></i>
            </div>
            <div class="timeline-content" style="flex: 1;">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div style="flex: 1;">
                        <h6 class="mb-1">
                            <strong>{{ $transaction->inventory->nama_barang ?? 'Barang tidak ditemukan' }}</strong>
                            <span class="badge bg-danger ms-2">{{ $transaction->nomor_transaksi }}</span>
                        </h6>
                        <p class="text-muted mb-1 small">
                            <i class="bi bi-calendar me-1"></i>{{ $transaction->tanggal->format('d M Y') }}
                            @if($transaction->tujuan)
                            | <i class="bi bi-geo-alt me-1"></i>{{ $transaction->tujuan }}
                            @endif
                            @if($transaction->user)
                            | <i class="bi bi-person me-1"></i>{{ $transaction->user->name }}
                            @endif
                        </p>
                        @if($transaction->keterangan)
                        <p class="mb-0 small"><i class="bi bi-chat-left-text me-1"></i>{{ $transaction->keterangan }}</p>
                        @endif
                    </div>
                    <div class="text-end mt-2 mt-md-0">
                        <h5 class="text-danger mb-1">-{{ $transaction->jumlah }} {{ $transaction->inventory->satuan ?? '' }}</h5>
                        <small class="text-muted">Rp {{ number_format(($transaction->harga ?? 0) * $transaction->jumlah, 0, ',', '.') }}</small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
            <p class="text-muted mt-3">Belum ada transaksi barang keluar</p>
            <a href="{{ route('barang-keluar.create') }}" class="btn btn-danger mt-2">
                <i class="bi bi-plus-circle me-2"></i>Catat Transaksi Pertama
            </a>
        </div>
        @endforelse

        @if($transactions->hasPages())
        <div class="mt-3">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
    </div>
@endsection