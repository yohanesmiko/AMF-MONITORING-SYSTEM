@extends('layouts.app')

@section('title', 'Tambah Material')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Tambah Material Baru</h4>
        <p class="mb-0" style="opacity: 0.9;">Input data material (CT atau Masterbatch)</p>
    </div>

    <div class="card-custom mt-4">
        <form action="{{ route('material.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Material <span class="text-danger">*</span></label>
                    <input type="text" name="kode_material" class="form-control @error('kode_material') is-invalid @enderror" 
                           value="{{ old('kode_material') }}" placeholder="Contoh: CT-001" required>
                    @error('kode_material')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Material <span class="text-danger">*</span></label>
                    <input type="text" name="nama_material" class="form-control @error('nama_material') is-invalid @enderror" 
                           value="{{ old('nama_material') }}" placeholder="Contoh: CT Box 500gr" required>
                    @error('nama_material')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="ct" {{ old('jenis') == 'ct' ? 'selected' : '' }}>CT (Carton)</option>
                        <option value="masterbatch" {{ old('jenis') == 'masterbatch' ? 'selected' : '' }}>Masterbatch</option>
                        <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}" placeholder="Contoh: Packaging">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                           value="{{ old('satuan', 'kg') }}" required>
                    @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                           value="{{ old('stok', 0) }}" min="0" required>
                    @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror" 
                           value="{{ old('stok_minimum', 0) }}" min="0" required>
                    <small class="text-muted">Alert akan muncul jika stok di bawah nilai ini</small>
                    @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Satuan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan" class="form-control" value="{{ old('harga_satuan', 0) }}" min="0">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}" placeholder="Nama supplier">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Contoh: Gudang B - Rak 2">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-info">
                    <i class="bi bi-save me-2"></i>Simpan Material
                </button>
                <a href="{{ route('material.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection