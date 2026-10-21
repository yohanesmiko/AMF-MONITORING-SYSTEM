@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Detail Barang</h4>
            <p class="text-muted mb-0">{{ $inventory->nama_barang }}</p>
        </div>
        <div>
            <a href="{{ route('inventory.edit', $inventory) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Gambar -->
        <div class="col-md-4">
            <div class="card-custom text-center">
                @if($inventory->gambar)
                <img src="{{ asset('storage/' . $inventory->gambar) }}" alt="{{ $inventory->nama_barang }}" 
                     class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
                @else
                <div style="height: 300px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                </div>
                @endif
                <h5 class="mt-3">{{ $inventory->nama_barang }}</h5>
                <span class="badge-status badge-{{ $inventory->status }}">
                    {{ ucfirst($inventory->status) }}
                </span>
            </div>
        </div>

        <!-- Informasi -->
        <div class="col-md-8">
            <div class="card-custom">
                <h5 class="mb-4">Informasi Barang</h5>
                
                <table class="table">
                    <tr>
                        <th width="30%">Kode Barang</th>
                        <td>: <strong>{{ $inventory->kode_barang }}</strong></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: <span class="badge bg-light text-dark">{{ $inventory->kategori }}</span></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>: <strong class="{{ $inventory->stok <= 10 ? 'text-danger' : '' }}">{{ $inventory->stok }} {{ $inventory->satuan }}</strong></td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td>: Rp {{ number_format($inventory->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>: Rp {{ number_format($inventory->harga_jual, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Margin</th>
                        <td>: Rp {{ number_format($inventory->harga_jual - $inventory->harga_beli, 0, ',', '.') }} 
                            ({{ number_format((($inventory->harga_jual - $inventory->harga_beli) / $inventory->harga_beli) * 100, 1) }}%)</td>
                    </tr>
                    <tr>
                        <th>Total Nilai</th>
                        <td>: Rp {{ number_format($inventory->stok * $inventory->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>: {{ $inventory->lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>: {{ $inventory->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ditambahkan</th>
                        <td>: {{ $inventory->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>: {{ $inventory->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection