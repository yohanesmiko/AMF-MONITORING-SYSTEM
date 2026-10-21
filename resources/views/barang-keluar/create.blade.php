@extends('layouts.app')

@section('title', 'Catat Barang Keluar')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <h4 class="mb-1"><i class="bi bi-arrow-up-circle me-2"></i>Catat Barang Keluar</h4>
        <p class="mb-0" style="opacity: 0.9;">Form pencatatan barang yang keluar dari gudang</p>
    </div>

    <div class="card-custom mt-4">
        <form action="{{ route('barang-keluar.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Barang <span class="text-danger">*</span></label>
                    <select name="inventory_id" class="form-select @error('inventory_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($inventories as $item)
                        <option value="{{ $item->id }}" {{ old('inventory_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }} {{ $item->satuan }})
                        </option>
                        @endforeach
                    </select>
                    @error('inventory_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                           value="{{ old('jumlah') }}" min="1" required>
                    <small class="text-muted">Pastikan jumlah tidak melebihi stok tersedia</small>
                    @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" 
                           value="{{ old('tujuan') }}" placeholder="Contoh: Toko Cabang A, Customer XYZ" required>
                    @error('tujuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pengiriman ke cabang Bandung">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-save me-2"></i>Simpan Transaksi
                </button>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection