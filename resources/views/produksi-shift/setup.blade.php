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
                <!-- Produk yang Sudah Ada -->
<div class="col-md-6">
    <div class="card-custom">
        <h5 class="mb-3"><i class="bi bi-list-ul me-2"></i>Produk yang Sudah Ada</h5>
        
        @if($products->count() > 0)
        <div class="list-group list-group-flush">
            @foreach($products as $product)
            <div class="list-group-item p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $product->nama_produk }}</h6>
                        <small class="text-muted d-block mb-2">{{ $product->kode_produk }}</small>
                        
                        <div class="mb-2">
                            <span class="badge bg-info me-1">CT: {{ $product->cycle_time_standar }}s</span>
                            <span class="badge bg-primary">Target: {{ number_format($product->target_per_shift) }}/shift</span>
                        </div>
                        
                        <div class="small text-muted">
                            <strong>Material:</strong>
                            @foreach($product->materials as $index => $pm)
                                {{ $pm->material->nama_material }}: {{ number_format($pm->qty_per_unit) }}gr
                                @if(!$loop->last), @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="dropdown ms-2">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('produksi-shift.edit-produk', $product->id) }}">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('produksi-shift.destroy-produk', $product->id) }}" method="POST" 
                                      onsubmit="return confirm('⚠️ Yakin ingin menghapus produk \'{{ $product->nama_produk }}\'?\n\nPeringatan: Data produksi yang terkait dengan produk ini mungkin akan terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i>Hapus
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-muted py-4">
            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
            <p class="mb-0 mt-2">Belum ada produk</p>
        </div>
        @endif
    </div>
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