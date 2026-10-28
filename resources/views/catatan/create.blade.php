@extends('layouts.app')

@section('title', 'Tambah Catatan')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Tambah Catatan Baru</h4>
    </div>

    <div class="card-custom mt-4">
        <form action="{{ route('catatan.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                           value="{{ old('judul') }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="umum" {{ old('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="penting" {{ old('kategori') == 'penting' ? 'selected' : '' }}>Penting</option>
                        <option value="reminder" {{ old('kategori') == 'reminder' ? 'selected' : '' }}>Reminder</option>
                        <option value="laporan" {{ old('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Isi Catatan <span class="text-danger">*</span></label>
                    <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="6" required>{{ old('isi') }}</textarea>
                    @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Warna Label</label>
                    <select name="warna" class="form-select">
                        <option value="primary" {{ old('warna') == 'primary' ? 'selected' : '' }}>Biru</option>
                        <option value="success" {{ old('warna') == 'success' ? 'selected' : '' }}>Hijau</option>
                        <option value="warning" {{ old('warna') == 'warning' ? 'selected' : '' }}>Kuning</option>
                        <option value="danger" {{ old('warna') == 'danger' ? 'selected' : '' }}>Merah</option>
                        <option value="info" {{ old('warna') == 'info' ? 'selected' : '' }}>Cyan</option>
                    </select>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_pinned" id="isPinned" {{ old('is_pinned') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isPinned">
                            <i class="bi bi-pin me-1"></i>Pin catatan ini
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-2"></i>Simpan Catatan
                </button>
                <a href="{{ route('catatan.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection