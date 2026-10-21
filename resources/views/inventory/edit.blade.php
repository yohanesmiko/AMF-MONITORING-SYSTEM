@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="topbar">
        <h4 class="mb-1">Edit Barang</h4>
        <p class="text-muted mb-0">Update informasi barang: {{ $inventory->nama_barang }}</p>
    </div>

    <div class="card-custom">
        <form action="{{ route('inventory.update', $inventory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" 
                               value="{{ old('kode_barang', $inventory->kode_barang) }}" required>
                        @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                               value="{{ old('nama_barang', $inventory->nama_barang) }}" required>
                        @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror" 
                               value="{{ old('kategori', $inventory->kategori) }}" required>
                        @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                   value="{{ old('stok', $inventory->stok) }}" min="0" required>
                            @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                                   value="{{ old('satuan', $inventory->satuan) }}" required>
                            @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi" class="form-control" 
                               value="{{ old('lokasi', $inventory->lokasi) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" 
                                   value="{{ old('harga_beli', $inventory->harga_beli) }}" min="0" step="0.01" required>
                            @error('harga_beli')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" 
                                   value="{{ old('harga_jual', $inventory->harga_jual) }}" min="0" step="0.01" required>
                            @error('harga_jual')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="tersedia" {{ old('status', $inventory->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="habis" {{ old('status', $inventory->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                            <option value="rusak" {{ old('status', $inventory->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $inventory->deskripsi) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Barang</label>
                        @if($inventory->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $inventory->gambar) }}" alt="Current image" 
                                 style="max-width: 200px; border-radius: 8px;">
                        </div>
                        @endif
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-save me-2"></i>Update Barang
                </button>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
@endsection