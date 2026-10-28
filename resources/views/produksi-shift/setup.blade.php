@extends('layouts.app')

@section('title', 'Setup Produk Produksi')

@section('content')
    <div class="topbar">
        <h4 class="mb-1">⚙️ Setup Produk Produksi</h4>
        <p class="mb-0">Definisikan produk dengan recipe material & cycle time</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="row g-4 mt-3">
        <!-- Form Tambah Produk -->
        <div class="col-lg-5">
            <div class="card-custom">
                <h5><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</h5>
                <form action="{{ route('produksi-shift.store-produk') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode Produk</label>
                        <input type="text" name="kode_produk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cycle Time (detik/unit)</label>
                            <input type="number" step="0.01" name="cycle_time_standar" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu Shift (menit)</label>
                            <input type="number" name="waktu_shift_menit" class="form-control" value="480" required>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-2">Material per Unit:</h6>
                    <div id="materialInputs">
                        <div class="row g-2 mb-2 material-row">
                            <div class="col-md-7">
                                <select name="materials[0][material_id]" class="form-select" required>
                                    <option value="">-- Pilih Material --</option>
                                    @foreach($materials as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_material }} ({{ $m->kode_material }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="number" step="0.01" name="materials[0][qty_per_unit]" class="form-control" placeholder="Qty (gram)" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addMaterial()">
                        <i class="bi bi-plus"></i> Tambah Material
                    </button>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-save me-2"></i>Simpan Produk
                    </button>
                </form>
            </div>
        </div>

        <!-- List Produk -->
        <div class="col-lg-7">
            <div class="card-custom">
                <h5><i class="bi bi-list-ul me-2"></i>Produk yang Sudah Ada</h5>
                @forelse($products as $product)
                <div class="p-3 mb-3 rounded" style="background: #f8fafc; border-left: 4px solid #4f46e5;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $product->nama_produk }}</h6>
                            <small class="text-muted">{{ $product->kode_produk }}</small>
                        </div>
                        <span class="badge bg-primary">CT: {{ $product->cycle_time_standar }}s</span>
                    </div>
                    <div class="mt-2 small">
                        <strong>Material:</strong>
                        @foreach($product->materials as $pm)
                        <span class="badge bg-light text-dark ms-1">
                            {{ $pm->material->nama_material }}: {{ $pm->qty_per_unit }}gr
                        </span>
                        @endforeach
                    </div>
                    <div class="mt-2 small text-muted">
                        Target/Shift: {{ number_format($product->target_per_shift) }} unit
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-4">Belum ada produk</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        let materialCount = 1;
        function addMaterial() {
            const container = document.getElementById('materialInputs');
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 material-row';
            row.innerHTML = `
                <div class="col-md-7">
                    <select name="materials[${materialCount}][material_id]" class="form-select" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_material }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" name="materials[${materialCount}][qty_per_unit]" class="form-control" placeholder="Qty (gram)" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.material-row').remove()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            materialCount++;
        }
    </script>
@endsection