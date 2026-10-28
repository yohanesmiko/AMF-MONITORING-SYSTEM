@extends('layouts.app')

@section('title', 'Input Produksi Shift ' . $shift)

@section('content')
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">📝 Input Produksi - Shift {{ $shift }}</h4>
                <p class="mb-0">{{ $product->nama_produk }} • {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</p>
            </div>
            <a href="{{ route('produksi-shift.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="card-custom mt-4">
        <form action="{{ route('produksi-shift.store') }}" method="POST">
            @csrf
            <input type="hidden" name="production_product_id" value="{{ $product->id }}">
            <input type="hidden" name="shift" value="{{ $shift }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <!-- Info Recipe -->
            <div class="alert alert-info">
                <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Recipe Produk:</h6>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Cycle Time:</strong> {{ $product->cycle_time_standar }} detik/unit
                    </div>
                    <div class="col-md-4">
                        <strong>Target/Shift:</strong> {{ number_format($product->target_per_shift) }} unit
                    </div>
                    <div class="col-md-4">
                        <strong>Waktu Shift:</strong> {{ $product->waktu_shift_menit }} menit
                    </div>
                </div>
                <hr>
                <strong>Material per Unit:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($product->materials as $pm)
                    <li>{{ $pm->material->nama_material }}: <strong>{{ number_format($pm->qty_per_unit, 2) }} {{ $pm->satuan }}</strong></li>
                    @endforeach
                </ul>
            </div>

            <!-- Data Produksi -->
            <h5 class="mb-3"><i class="bi bi-speedometer2 me-2 text-primary"></i>Data Produksi</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Output Aktual (unit) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="output_aktual" class="form-control" 
                           value="{{ $existingRun->output_aktual ?? old('output_aktual') }}" required>
                    <small class="text-muted">Jumlah unit yang berhasil diproduksi</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Downtime (menit) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="downtime_menit" class="form-control" 
                           value="{{ $existingRun->downtime_menit ?? old('downtime_menit', 0) }}" required>
                    <small class="text-muted">Waktu mesin tidak beroperasi</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Operator</label>
                    <select name="operator_id" class="form-select">
                        <option value="">-- Pilih Operator --</option>
                        @foreach($operators as $op)
                        <option value="{{ $op->id }}" {{ ($existingRun->operator_id ?? '') == $op->id ? 'selected' : '' }}>
                            {{ $op->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Material yang Dipakai -->
            <h5 class="mb-3"><i class="bi bi-box-seam me-2 text-success"></i>Material yang Dipakai (gram)</h5>
            <div class="row g-3 mb-4">
                @foreach($product->materials as $pm)
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        {{ $pm->material->nama_material }} 
                        <small class="text-muted">(Standar: {{ number_format(($existingRun->output_aktual ?? 0) * $pm->qty_per_unit, 2) }} gr)</small>
                    </label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="material_aktual[{{ $pm->material_id }}]" 
                               class="form-control" 
                               value="{{ old('material_aktual.' . $pm->material_id, ($existingRun->output_aktual ?? 0) * $pm->qty_per_unit) }}" 
                               required>
                        <span class="input-group-text">{{ $pm->satuan }}</span>
                    </div>
                    <small class="text-muted">Qty per unit: {{ $pm->qty_per_unit }} {{ $pm->satuan }}</small>
                </div>
                @endforeach
            </div>

            <!-- Catatan -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan Shift</label>
                <textarea name="catatan" class="form-control" rows="3">{{ $existingRun->catatan ?? old('catatan') }}</textarea>
            </div>

            <!-- Preview Perhitungan -->
            <div class="alert alert-warning" id="previewPerhitungan">
                <h6 class="mb-2"><i class="bi bi-calculator me-2"></i>Preview Perhitungan:</h6>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Target Output:</strong> <span id="prevTarget">-</span> unit
                    </div>
                    <div class="col-md-4">
                        <strong>Efisiensi:</strong> <span id="prevEfisiensi">-</span>%
                    </div>
                    <div class="col-md-4">
                        <strong>CT Aktual:</strong> <span id="prevCT">-</span> detik
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-save me-2"></i>Simpan Produksi Shift {{ $shift }}
                </button>
                <a href="{{ route('produksi-shift.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        // Preview perhitungan real-time
        const outputInput = document.querySelector('[name="output_aktual"]');
        const downtimeInput = document.querySelector('[name="downtime_menit"]');
        const cycleTimeStandar = {{ $product->cycle_time_standar }};
        const waktuShiftMenit = {{ $product->waktu_shift_menit }};

        function updatePreview() {
            const output = parseFloat(outputInput.value) || 0;
            const downtime = parseFloat(downtimeInput.value) || 0;
            
            const waktuEfektifDetik = (waktuShiftMenit - downtime) * 60;
            const target = Math.floor(waktuEfektifDetik / cycleTimeStandar);
            const efisiensi = target > 0 ? (output / target) * 100 : 0;
            const ctAktual = output > 0 ? waktuEfektifDetik / output : 0;

            document.getElementById('prevTarget').textContent = target.toLocaleString();
            document.getElementById('prevEfisiensi').textContent = efisiensi.toFixed(2);
            document.getElementById('prevCT').textContent = ctAktual.toFixed(2);
        }

        outputInput.addEventListener('input', updatePreview);
        downtimeInput.addEventListener('input', updatePreview);
        updatePreview();
    </script>
@endsection