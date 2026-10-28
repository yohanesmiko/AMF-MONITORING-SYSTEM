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
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="6" 
                              placeholder="Deskripsi detail tentang barang (opsional)">{{ old('deskripsi') }}</textarea>
                    <small class="text-muted">Informasi tambahan tentang barang</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Gambar Barang</label>
                    
                    <!-- Area Upload dengan Drag & Drop -->
                    <div class="upload-area" id="uploadArea" 
                         style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 30px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.3s; min-height: 200px; display: flex; align-items: center; justify-content: center;">
                        
                        <input type="file" name="gambar" id="gambarInput" accept="image/*" style="display: none;">
                        
                        <div id="uploadPlaceholder">
                            <i class="bi bi-cloud-arrow-up" style="font-size: 3rem; color: #94a3b8;"></i>
                            <h6 class="mt-2 mb-1">Seret & Lepas Gambar di Sini</h6>
                            <p class="text-muted mb-2 small">atau</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="browseBtn">
                                <i class="bi bi-folder2-open me-1"></i>Pilih File
                            </button>
                            <p class="text-muted mt-2 mb-0 small">
                                <i class="bi bi-info-circle"></i> 
                                Max 2MB. Format: JPG, PNG, GIF, WEBP
                            </p>
                        </div>

                        <!-- Preview Gambar -->
                        <div id="gambarPreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" 
                                 style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">
                            <div class="mt-2">
                                <p class="mb-1 small text-success" id="fileName"></p>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="removeBtn">
                                    <i class="bi bi-x-circle me-1"></i>Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    @error('gambar')
                    <div class="text-danger small mt-2">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const gambarInput = document.getElementById('gambarInput');
    const browseBtn = document.getElementById('browseBtn');
    const gambarPreview = document.getElementById('gambarPreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const previewImg = document.getElementById('previewImg');
    const fileName = document.getElementById('fileName');
    const removeBtn = document.getElementById('removeBtn');

    // Klik area upload untuk browse file
    uploadArea.addEventListener('click', function(e) {
        if (e.target !== browseBtn && e.target !== removeBtn && !e.target.closest('#gambarPreview')) {
            gambarInput.click();
        }
    });

    browseBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        gambarInput.click();
    });

    // Drag & Drop Events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // File Input Change
    gambarInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // Handle File
    function handleFile(file) {
        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan JPG, PNG, GIF, atau WEBP.');
            return;
        }

        // Validasi ukuran (max 2MB)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            return;
        }

        // Tampilkan preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            uploadPlaceholder.style.display = 'none';
            gambarPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Remove Gambar
    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        gambarInput.value = '';
        previewImg.src = '';
        fileName.textContent = '';
        uploadPlaceholder.style.display = 'block';
        gambarPreview.style.display = 'none';
    });
});
</script>
@endpush