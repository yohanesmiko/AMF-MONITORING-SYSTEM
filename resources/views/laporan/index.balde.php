@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <!-- Topbar dengan Style Berbeda -->
    <div class="topbar" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px;">
        <div>
            <h4 class="mb-1"><i class="bi bi-file-earmark-text me-2"></i>Laporan Inventory</h4>
            <p class="mb-0" style="opacity: 0.9;">Ringkasan dan analisis transaksi inventory</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="card-custom mt-4">
        <form action="{{ route('laporan.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ $tanggalDari }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ $tanggalSampai }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-funnel me-2"></i>Tampilkan Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mt-2">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-receipt"></i></div>
                <h3>{{ $stats['total_transaksi'] }}</h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-arrow-down-circle"></i></div>
                <h3>{{ $stats['barang_masuk'] }}</h3>
                <p>Transaksi Masuk</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger"><i class="bi bi-arrow-up-circle"></i></div>
                <h3>{{ $stats['barang_keluar'] }}</h3>
                <p>Transaksi Keluar</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-cash-stack"></i></div>
                <h3>Rp {{ number_format($stats['nilai_masuk'] - $stats['nilai_keluar'], 0, ',', '.') }}</h3>
                <p>Net Nilai</p>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card-custom mt-4">
        <h5 class="mb-4"><i class="bi bi-table me-2"></i>Detail Transaksi</h5>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td><strong>{{ $t->nomor_transaksi }}</strong></td>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $t->jenis == 'masuk' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($t->jenis) }}
                            </span>
                        </td>
                        <td>{{ $t->inventory->nama_barang }}</td>
                        <td>{{ $t->jumlah }}</td>
                        <td>Rp {{ number_format($t->jumlah * ($t->harga ?? 0), 0, ',', '.') }}</td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection