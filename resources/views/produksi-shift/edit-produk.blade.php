@extends('layouts.app')

@section('title', 'Edit Produk Produksi')

@section('content')
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">✏️ Edit Produk Produksi</h4>
                <p class="mb-0">Update informasi produk: <strong>{{ $product->nama_produk }}</strong></p>
            </div>
            <div>
                <a href="{{ route('produksi-shift.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger mt-3">
        <i class="bi bi-exclamation-circle me-2"></i>Ada kesalahan pada form:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card-custom mt-4">
        <form action="{{ route('produksi-shift.update-produk', $product->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Informasi Dasar -->
            <h5 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Produk</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                    <input type="text" name="kode_produk" class="form-control" 
                           value="{{ old('kode_produk', $product->kode_produk) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control" 
                           value="{{ old('nama_produk', $product->nama_produk) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori</label>
                    <input type="text" name="kategori" class="form-control" 
                           value="{{ old('kategori', $product->kategori) }}" placeholder="Contoh: Packaging">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" 
                           value="{{ old('deskripsi', $product->deskripsi) }}">
                </div>
            </div>

            <!-- Cycle Time & Shift -->
            <h5 class="mb-3"><i class="bi bi-clock me-2 text-warning"></i>Cycle Time & Shift</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cycle Time Standar (detik/unit) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="cycle_time_standar" class="form-control" 
                               value="{{ old('cycle_time_standar', $product->cycle_time_standar) }}" min="0.01" required>
                        <span class="input-group-text">detik</span>
                    </div>
                    <small class="text-muted">Waktu standar untuk memproduksi 1 unit</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Waktu Shift (menit) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="waktu_shift_menit" class="form-control" 
                               value="{{ old('waktu_shift_menit', $product->waktu_shift_menit) }}" min="1" required>
                        <span class="input-group-text">menit</span>
                    </div>
                    <small class="text-muted">Durasi 1 shift kerja (default: 480 menit = 8 jam)</small>
                </div>

                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-calculator me-2"></i>
                        <strong>Target per Shift:</strong> 
                        <span id="targetPreview">{{ number_format(floor(($product->waktu_shift_menit * 60) / $product->cycle_time_standar)) }}</span> unit
                    </div>
                </div>
            </div>

            <!-- Material Recipe -->
            <h5 class="mb-3"><i class="bi bi-box-seam me-2 text-success"></i>Material per Unit</h5>
            <div id="materialContainer" class="mb-4">
                @foreach($product->materials as $index => $pm)
                <div class="row g-3 mb-2 material-row">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Material</label>
                        <select name="materials[{{ $index }}][material_id]" class="form-select" required>
                            <option value="">-- Pilih Material --</option>
                            @foreach($materials as $mat)
                            <option value="{{ $mat->id }}" {{ old("materials.$index.material_id", $pm->material_id) == $mat->id ? 'selected' : '' }}>
                                {{ $mat->nama_material }} ({{ $mat->kode_material }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Qty per Unit (gram)</label>
                        <input type="number" step="0.01" name="materials[{{ $index }}][qty_per_unit]" class="form-control" 
                               value="{{ old("materials.$index.qty_per_unit", $pm->qty_per_unit) }}" min="0.01" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="removeMaterialRow(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-success mb-4" onclick="addMaterialRow()">
                <i class="bi bi-plus-circle me-2"></i>Tambah Material
            </button>

            <!-- Tombol Aksi -->
            <div class="d-flex gap-2 pt-3" style="border-top: 1px solid #e2e8f0;">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('produksi-shift.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        let materialIndex = {{ $product->materials->count() }};

        function addMaterialRow() {
            const container = document.getElementById('materialContainer');
            const row = document.createElement('div');
            row.className = 'row g-3 mb-2 material-row';
            row.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Material</label>
                    <select name="materials[${materialIndex}][material_id]" class="form-select" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $mat)
                        <option value="{{ $mat->id }}">{{ $mat->nama_material }} ({{ $mat->kode_material }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Qty per Unit (gram)</label>
                    <input type="number" step="0.01" name="materials[${materialIndex}][qty_per_unit]" class="form-control" min="0.01" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeMaterialRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            materialIndex++;
        }

        function removeMaterialRow(btn) {
            btn.closest('.material-row').remove();
        }

        // Preview target calculation
        const ctInput = document.querySelector('[name="cycle_time_standar"]');
        const shiftInput = document.querySelector('[name="waktu_shift_menit"]');
        const targetPreview = document.getElementById('targetPreview');

        function updateTargetPreview() {
            const ct = parseFloat(ctInput.value) || 0;
            const shift = parseFloat(shiftInput.value) || 0;
            if (ct > 0 && shift > 0) {
                const target = Math.floor((shift * 60) / ct);
                targetPreview.textContent = target.toLocaleString();
            }
        }

        ctInput.addEventListener('input', updateTargetPreview);
        shiftInput.addEventListener('input', updateTargetPreview);
    </script>
@endsection