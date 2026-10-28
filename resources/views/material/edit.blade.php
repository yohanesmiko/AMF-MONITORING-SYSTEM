@extends('layouts.app')

@section('title', 'Edit Material')

@section('content')
    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">️ Edit Material</h4>
            <p class="mb-0">Update informasi material: <strong>{{ $material->nama_material }}</strong></p>
        </div>
        <div>
            <a href="{{ route('material.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle me-2"></i>Ada kesalahan pada form:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-4 mt-2">
        <!-- Form Edit (Kiri) -->
        <div class="col-lg-7">
            <div class="card-custom">
                <h5 class="mb-4"><i class="bi bi-pencil-square me-2 text-primary"></i>Form Edit Material</h5>
                
                <form action="{{ route('material.update', $material) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar -->
                    <h6 class="text-muted mb-3"> Informasi Dasar</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Material <span class="text-danger">*</span></label>
                            <input type="text" name="kode_material" class="form-control @error('kode_material') is-invalid @enderror" 
                                   value="{{ old('kode_material', $material->kode_material) }}" required>
                            @error('kode_material')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Material <span class="text-danger">*</span></label>
                            <input type="text" name="nama_material" class="form-control @error('nama_material') is-invalid @enderror" 
                                   value="{{ old('nama_material', $material->nama_material) }}" required>
                            @error('nama_material')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="ct" {{ old('jenis', $material->jenis) == 'ct' ? 'selected' : '' }}>CT (Carton)</option>
                                <option value="masterbatch" {{ old('jenis', $material->jenis) == 'masterbatch' ? 'selected' : '' }}>Masterbatch</option>
                                <option value="lainnya" {{ old('jenis', $material->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" 
                                   value="{{ old('kategori', $material->kategori) }}" placeholder="Contoh: Packaging, Raw Material">
                        </div>
                    </div>

                    <!-- Stok & Harga -->
                    <h6 class="text-muted mb-3">📦 Stok & Harga</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                       value="{{ old('stok', $material->stok) }}" min="0" required>
                                <span class="input-group-text">{{ $material->satuan }}</span>
                                @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror" 
                                       value="{{ old('stok_minimum', $material->stok_minimum) }}" min="0" required>
                                <span class="input-group-text">{{ $material->satuan }}</span>
                                @error('stok_minimum')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Alert muncul jika stok di bawah nilai ini</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                <option value="KG" {{ old('satuan', $material->satuan) == 'KG' ? 'selected' : '' }}>KG</option>
                                <option value="TON" {{ old('satuan', $material->satuan) == 'TON' ? 'selected' : '' }}>TON</option>
                                <option value="PCS" {{ old('satuan', $material->satuan) == 'PCS' ? 'selected' : '' }}>PCS</option>
                                <option value="LITER" {{ old('satuan', $material->satuan) == 'LITER' ? 'selected' : '' }}>LITER</option>
                                <option value="METER" {{ old('satuan', $material->satuan) == 'METER' ? 'selected' : '' }}>METER</option>
                            </select>
                            @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" name="harga_satuan" class="form-control" 
                                       value="{{ old('harga_satuan', $material->harga_satuan) }}" min="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="tersedia" {{ old('status', $material->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="menipis" {{ old('status', $material->status) == 'menipis' ? 'selected' : '' }}>Menipis</option>
                                <option value="habis" {{ old('status', $material->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                            </select>
                            <small class="text-muted">Otomatis diupdate berdasarkan stok</small>
                        </div>
                    </div>

                    <!-- Lokasi & Supplier -->
                    <h6 class="text-muted mb-3">📍 Lokasi & Supplier</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" 
                                   value="{{ old('lokasi', $material->lokasi) }}" placeholder="Contoh: RAK 1, Gudang A">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <input type="text" name="supplier" class="form-control" 
                                   value="{{ old('supplier', $material->supplier) }}" placeholder="Nama supplier">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $material->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex gap-2 pt-3" style="border-top: 1px solid #e2e8f0;">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('material.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card (Kanan) - Seperti di gambar -->
        <div class="col-lg-5">
            <div class="card-custom">
                <h5 class="mb-4"><i class="bi bi-eye me-2 text-info"></i>Preview Card</h5>
                
                <!-- Card Preview (Sama seperti di gambar) -->
                <!-- Card Preview -->
<div id="previewCard" class="p-4 rounded" style="background: white; border-left: 4px solid {{ 
    $material->status == 'tersedia' ? '#10b981' : 
    ($material->status == 'menipis' ? '#f59e0b' : '#ef4444') 
}}; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <span class="badge {{ $material->jenis == 'ct' ? 'bg-primary' : ($material->jenis == 'masterbatch' ? 'bg-purple' : 'bg-secondary') }}" 
                  style="{{ $material->jenis == 'masterbatch' ? 'background: #8b5cf6;' : '' }}; font-size: 0.75rem;">
                {{ strtoupper($material->jenis) }}
            </span>
            <h5 class="mt-2 mb-1" id="previewNama">{{ $material->nama_material }}</h5>
            <small class="text-muted" id="previewKode">{{ $material->kode_material }}</small>
        </div>
        <span class="badge {{ 
            $material->status == 'tersedia' ? 'bg-success' : 
            ($material->status == 'menipis' ? 'bg-warning text-dark' : 'bg-danger') 
        }}" id="previewStatus">
            {{ ucfirst($material->status) }}
        </span>
    </div>

    <div class="mt-3">
        <div class="d-flex justify-content-between mb-2">
            <small class="text-muted">Stok Saat Ini</small>
            <strong id="previewStok" class="{{ $material->status == 'habis' ? 'text-danger' : ($material->status == 'menipis' ? 'text-warning' : '') }}">
                {{ number_format($material->stok, 2) }} {{ $material->satuan }}
            </strong>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <small class="text-muted">Stok Minimum</small>
            <span id="previewMin">{{ number_format($material->stok_minimum, 2) }} {{ $material->satuan }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <small class="text-muted">Harga Satuan</small>
            <span id="previewHarga">Rp {{ number_format($material->harga_satuan ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($material->lokasi)
    <div class="mt-3 pt-3" style="border-top: 1px solid #e2e8f0;">
        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><span id="previewLokasi">{{ $material->lokasi }}</span></small>
    </div>
    @endif
</div>

                <!-- Info Perubahan -->
                <div class="alert alert-info mt-4 mb-0">
                    <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                    <ul class="mb-0 small">
                        <li>Perubahan akan otomatis tersimpan</li>
                        <li>Status akan diupdate otomatis berdasarkan stok</li>
                        <li>Preview di kanan menunjukkan hasil akhir</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Live Preview -->
    <script>
      function updatePreview() {
    // Update nama & kode
    document.getElementById('previewNama').textContent = inputs.nama.value || '-';
    document.getElementById('previewKode').textContent = inputs.kode.value || '-';
    
    // Update stok dengan satuan
    const stok = parseFloat(inputs.stok.value) || 0;
    const stokMin = parseFloat(inputs.stokMin.value) || 0;
    const satuan = inputs.satuan.value;
    
    // Hitung status otomatis
    let statusOtomatis = 'tersedia';
    if (stok == 0) {
        statusOtomatis = 'habis';
    } else if (stok <= stokMin) {
        statusOtomatis = 'menipis';
    }
    
    // Update preview stok (dengan warna sesuai status)
    const previewStok = document.getElementById('previewStok');
    previewStok.textContent = stok.toFixed(2) + ' ' + satuan;
    previewStok.className = statusOtomatis === 'habis' ? 'text-danger fw-bold' : 
                           (statusOtomatis === 'menipis' ? 'text-warning fw-bold' : 'fw-bold');
    
    document.getElementById('previewMin').textContent = stokMin.toFixed(2) + ' ' + satuan;
    
    // Update harga
    const harga = parseFloat(inputs.harga.value) || 0;
    document.getElementById('previewHarga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
    
    // Update lokasi
    document.getElementById('previewLokasi').textContent = inputs.lokasi.value || '-';
    
    // Update status badge (OTOMATIS)
    const statusBadge = document.getElementById('previewStatus');
    statusBadge.textContent = statusOtomatis.charAt(0).toUpperCase() + statusOtomatis.slice(1);
    statusBadge.className = 'badge ' + (
        statusOtomatis === 'tersedia' ? 'bg-success' : 
        (statusOtomatis === 'menipis' ? 'bg-warning text-dark' : 'bg-danger')
    );
    
    // Update border color card
    const previewCard = document.getElementById('previewCard');
    const borderColor = statusOtomatis === 'tersedia' ? '#10b981' : 
                       (statusOtomatis === 'menipis' ? '#f59e0b' : '#ef4444');
    previewCard.style.borderLeft = '4px solid ' + borderColor;
    
    // Update jenis badge
    const jenis = inputs.jenis.value;
    const jenisBadge = previewCard.querySelector('.badge:first-child');
    jenisBadge.textContent = jenis.toUpperCase();
    jenisBadge.style.background = jenis === 'masterbatch' ? '#8b5cf6' : (jenis === 'ct' ? '#4f46e5' : '#64748b');
}

            function updatePreview() {
                // Update nama & kode
                document.getElementById('previewNama').textContent = inputs.nama.value || '-';
                document.getElementById('previewKode').textContent = inputs.kode.value || '-';
                
                // Update stok dengan satuan
                const stok = parseFloat(inputs.stok.value) || 0;
                const satuan = inputs.satuan.value;
                document.getElementById('previewStok').textContent = stok.toFixed(2) + ' ' + satuan;
                document.getElementById('previewMin').textContent = (parseFloat(inputs.stokMin.value) || 0).toFixed(2) + ' ' + satuan;
                
                // Update harga
                const harga = parseFloat(inputs.harga.value) || 0;
                document.getElementById('previewHarga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
                
                // Update lokasi
                document.getElementById('previewLokasi').textContent = inputs.lokasi.value || '-';
                
                // Update status badge
                const status = inputs.status.value;
                const statusBadge = document.getElementById('previewStatus');
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.className = 'badge ' + (status === 'tersedia' ? 'bg-success' : (status === 'menipis' ? 'bg-warning' : 'bg-danger'));
                
                // Update border color card
                const previewCard = document.getElementById('previewCard');
                const borderColor = status === 'tersedia' ? '#10b981' : (status === 'menipis' ? '#f59e0b' : '#ef4444');
                previewCard.style.borderLeft = '4px solid ' + borderColor;
                
                // Update jenis badge
                const jenis = inputs.jenis.value;
                const jenisBadge = previewCard.querySelector('.badge:first-child');
                jenisBadge.textContent = jenis.toUpperCase();
                jenisBadge.style.background = jenis === 'masterbatch' ? '#8b5cf6' : (jenis === 'ct' ? '#4f46e5' : '#64748b');
            }

            // Attach event listeners
            Object.values(inputs).forEach(input => {
                if (input) {
                    input.addEventListener('input', updatePreview);
                    input.addEventListener('change', updatePreview);
                }
            });
        });
    </script>
@endsection