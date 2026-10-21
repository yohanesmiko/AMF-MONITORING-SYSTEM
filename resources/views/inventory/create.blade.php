@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Tambah Barang Baru</h4>
            <p class="text-muted mb-0">Isi form di bawah untuk menambah barang ke inventory ATMI MIKAEL FORTUNA</p>
        </div>
        <div>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card-custom mt-4">
        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informasi Dasar Barang -->
            <h5 class="mb-3">
                <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Dasar
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" 
                           value="{{ old('kode_barang') }}" placeholder="Contoh: BRG001" required>
                    <small class="text-muted">Kode unik untuk identifikasi barang</small>
                    @error('kode_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                           value="{{ old('nama_barang') }}" placeholder="Contoh: Laptop ASUS ROG" required>
                    @error('nama_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror" 
                           value="{{ old('kategori') }}" placeholder="Contoh: Elektronik, Aksesoris, ATK" required>
                    <small class="text-muted">Kategori untuk pengelompokan barang</small>
                    @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                    <input type="text" name="lokasi" class="form-control" 
                           value="{{ old('lokasi') }}" placeholder="Contoh: Gudang A - Rak 1">
                    <small class="text-muted">Lokasi barang disimpan di gudang</small>
                </div>
            </div>

            <!-- Stok dan Satuan -->
            <h5 class="mb-3">
                <i class="bi bi-box-seam me-2 text-success"></i>Stok & Satuan
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                           value="{{ old('stok', 0) }}" min="0" required>
                    <small class="text-muted">Jumlah stok awal barang</small>
                    @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                           value="{{ old('satuan') }}" placeholder="Contoh: pcs, unit, kg, box" required>
                    <small class="text-muted">Satuan pengukuran barang</small>
                    @error('satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                        <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    <small class="text-muted">Status ketersediaan barang</small>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Harga -->
            <h5 class="mb-3">
                <i class="bi bi-cash-stack me-2 text-warning"></i>Harga
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" 
                               value="{{ old('harga_beli', 0) }}" min="0" step="0.01" required>
                        @error('harga_beli')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Harga pembelian dari supplier</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" 
                               value="{{ old('harga_jual', 0) }}" min="0" step="0.01" required>
                        @error('harga_jual')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Harga penjualan ke customer</small>
                </div>
            </div>

            <!-- Deskripsi dan Gambar -->
            <h5 class="mb-3">
                <i class="bi bi-image me-2 text-info"></i>Deskripsi & Gambar
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" 
                              placeholder="Deskripsi detail tentang barang (opsional)">{{ old('deskripsi') }}</textarea>
                    <small class="text-muted">Informasi tambahan tentang barang</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gambar Barang</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" id="gambarInput">
                    <small class="text-muted">Max 2MB. Format: JPG, PNG, GIF</small>
                    
                    <!-- Preview Gambar -->
                    <div id="gambarPreview" class="mt-3" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; border-radius: 8px; border: 2px solid #e2e8f0;">
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex gap-2 pt-3" style="border-top: 1px solid #e2e8f0;">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-save me-2"></i>Simpan Barang
                </button>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Form
                </button>
            </div>
        </form>
    </div>

    <!-- Script untuk Preview Gambar -->
    <script>
        document.getElementById('gambarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('gambarPreview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection