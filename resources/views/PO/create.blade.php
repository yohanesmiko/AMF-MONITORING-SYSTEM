@extends('layouts.app')

@section('title', 'Buat Purchase Order')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Buat Purchase Order Baru</h4>
        <p class="mb-0" style="opacity: 0.9;">Form pembuatan PO berdasarkan quantity kebutuhan</p>
    </div>

    <div class="card-custom mt-4">
        <form action="{{ route('po.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Material <span class="text-danger">*</span></label>
                    <select name="material_id" class="form-select @error('material_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $m)
                        <option value="{{ $m->id }}" {{ old('material_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->kode_material }} - {{ $m->nama_material }} (Stok: {{ $m->stok }} {{ $m->satuan }})
                        </option>
                        @endforeach
                    </select>
                    @error('material_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" 
                           value="{{ old('supplier') }}" required>
                    @error('supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="qty" class="form-control @error('qty') is-invalid @enderror" 
                           value="{{ old('qty') }}" min="0.01" required>
                    @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Satuan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan" class="form-control @error('harga_satuan') is-invalid @enderror" 
                               value="{{ old('harga_satuan') }}" min="0" required>
                    </div>
                    @error('harga_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal PO <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_po" class="form-control @error('tanggal_po') is-invalid @enderror" 
                           value="{{ old('tanggal_po', date('Y-m-d')) }}" required>
                    @error('tanggal_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ old('tanggal_jatuh_tempo') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary" style="background: #8b5cf6; border: none;">
                    <i class="bi bi-save me-2"></i>Buat Purchase Order
                </button>
                <a href="{{ route('po.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection