@extends('layouts.app')

@section('title', 'Tambah WIP')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Tambah Work In Progress</h4>
        <p class="mb-0" style="opacity: 0.9;">Mulai proses produksi material</p>
    </div>

    @if(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="card-custom mt-4">
        <form action="{{ route('wip.store') }}" method="POST">
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
                    <label class="form-label fw-semibold">Qty Input <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="qty_input" class="form-control @error('qty_input') is-invalid @enderror" 
                           value="{{ old('qty_input') }}" min="0.01" required>
                    @error('qty_input')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mesin</label>
                    <input type="text" name="mesin" class="form-control" value="{{ old('mesin') }}" placeholder="Nama/ID mesin">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Operator</label>
                    <input type="text" name="operator" class="form-control" value="{{ old('operator') }}" placeholder="Nama operator">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                           value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-2"></i>Mulai Proses WIP
                </button>
                <a href="{{ route('wip.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection