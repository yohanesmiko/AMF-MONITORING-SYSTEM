@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Manajemen Inventory</h4>
            <p class="text-muted mb-0">Kelola semua barang inventory ATMI MIKAEL FORTUNA</p>
        </div>
        <div>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle me-2"></i>Tambah Barang
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter & Search -->
    <div class="card-custom mb-4">
        <form action="{{ route('inventory.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Barang</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama, kode, atau kategori..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                        <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stok</label>
                    <select name="stok_menipis" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" {{ request('stok_menipis') ? 'selected' : '' }}>Menipis (≤10)</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card-custom">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga Jual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $item)
                    <tr>
                        <td>
                            @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="{{ $item->nama_barang }}" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                            <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td><strong>{{ $item->kode_barang }}</strong></td>
                        <td>{{ $item->nama_barang }}</td>
                        <td><span class="badge bg-light text-dark">{{ $item->kategori }}</span></td>
                        <td>
                            <span class="{{ $item->stok <= 10 ? 'text-danger fw-bold' : '' }}">
                                {{ $item->stok }} {{ $item->satuan }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge-status badge-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">Tidak ada data barang</p>
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary-custom mt-2">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Barang Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($inventories->hasPages())
        <div class="mt-3">
            {{ $inventories->withQueryString()->links() }}
        </div>
        @endif
    </div>
@endsection