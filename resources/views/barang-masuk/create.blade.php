@extends('layouts.app')

@section('title', 'Catat Barang Masuk')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Catat Barang Masuk</h4>
        <p class="mb-0" style="opacity: 0.9;">Form pencatatan barang yang masuk ke gudang</p>
    </div>

    <div class="card-custom mt-4">
        <form action="{{ route('barang-masuk.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Barang <span class="text-danger">*</span></label>
                    <select name="inventory_id" class="form-select @error('inventory_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($inventories as $item)
                        <option value="{{ $item->id }}" {{ old('inventory_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                        </option>
                        @endforeach
                    </select>
                    @error('inventory_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                           value="{{ old('jumlah') }}" min="1" required>
                    @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                               value="{{ old('harga') }}" min="0" required>
                        @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">-- Pilih Supplier (Opsional) --</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama_supplier }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembelian dari supplier PT XYZ">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-2"></i>Simpan Transaksi
                </button>
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
@endsection