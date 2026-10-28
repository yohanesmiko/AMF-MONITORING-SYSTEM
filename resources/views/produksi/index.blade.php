@extends('layouts.app')

@section('title', 'Produksi & Monitoring')

@section('content')
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">🏭 Produksi & Monitoring Material</h4>
                <p class="mb-0">Monitoring stok material & konversi ke produk jadi</p>
            </div>
            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalProduksi">
                <i class="bi bi-play-fill me-2"></i>Jalankan Produksi
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-layers-fill"></i></div>
                <h3>{{ $stats['total_material'] }}</h3>
                <p>Total Material</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger"><i class="bi bi-exclamation-octagon-fill"></i></div>
                <h3>{{ $stats['material_kritis'] }}</h3>
                <p>Material Kritis</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-box-seam-fill"></i></div>
                <h3>{{ number_format($stats['total_stok_produk'], 0) }}</h3>
                <p>Stok Produk Jadi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-gear-wide-connected"></i></div>
                <h3>{{ $stats['wip_aktif'] }}</h3>
                <p>WIP Aktif</p>
            </div>
        </div>
    </div>

    <!-- Warning Material (Paling Penting!) -->
    <div class="card-custom mb-4">
        <h5><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>⚠️ Warning Stok Material - Estimasi Hari Habis</h5>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Jenis</th>
                        <th>Stok Saat Ini</th>
                        <th>Rata-rata Harian</th>
                        <th>Estimasi Habis</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materialWarning as $item)
                    @if($item['hari_habis'] !== null)
                    <tr class="{{ $item['level'] == 'danger' ? 'table-danger' : ($item['level'] == 'warning' ? 'table-warning' : '') }}">
                        <td>
                            <strong>{{ $item['material']->nama_material }}</strong><br>
                            <small class="text-muted">{{ $item['material']->kode_material }}</small>
                        </td>
                        <td><span class="badge bg-info">{{ strtoupper($item['material']->jenis) }}</span></td>
                        <td>{{ number_format($item['material']->stok, 2) }} {{ $item['material']->satuan }}</td>
                        <td>{{ number_format($item['avg_harian'], 2) }} {{ $item['material']->satuan }}/hari</td>
                        <td>
                            @if($item['hari_habis'] <= 3)
                                <strong class="text-danger">{{ $item['hari_habis'] }} hari lagi!</strong>
                            @elseif($item['hari_habis'] <= 7)
                                <strong class="text-warning">{{ $item['hari_habis'] }} hari</strong>
                            @else
                                {{ $item['hari_habis'] }} hari
                            @endif
                        </td>
                        <td>
                            @if($item['level'] == 'danger')
                                <span class="badge bg-danger">🔴 KRITIS</span>
                            @elseif($item['level'] == 'warning')
                                <span class="badge bg-warning text-dark">🟡 URGENT</span>
                            @else
                                <span class="badge bg-success">🟢 Aman</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('po.create') }}?material_id={{ $item['material']->id }}" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-cart-plus"></i> PO
                            </a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kapasitas Produksi per Produk -->
    <div class="card-custom">
        <h5><i class="bi bi-calculator-fill me-2 text-primary"></i>📊 Kapasitas Produksi Berdasarkan Stok Material</h5>
        <div class="row g-4">
            @foreach($recipes as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card-custom" style="border-left: 4px solid {{ $item['maks_produksi'] <= 10 ? '#ef4444' : ($item['maks_produksi'] <= 50 ? '#f59e0b' : '#10b981') }};">
                    <h6 class="mb-2">{{ $item['recipe']->nama_produk }}</h6>
                    <small class="text-muted">{{ $item['recipe']->kode_produk }}</small>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Maks Produksi:</span>
                            <strong style="font-size: 1.3rem;">{{ number_format($item['maks_produksi']) }} unit</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Material Pembatas:</span>
                            <span>{{ $item['material_pembatas'] ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Yield Rate:</span>
                            <span>{{ $item['recipe']->yield_rate }}%</span>
                        </div>
                    </div>

                    <div class="mt-3 pt-3" style="border-top: 1px solid #e2e8f0;">
                        <small class="text-muted d-block mb-2">Material Dibutuhkan:</small>
                        @foreach($item['recipe']->details as $detail)
                        <div class="d-flex justify-content-between small">
                            <span>{{ $detail->material->nama_material }}</span>
                            <span>{{ $detail->qty_dibutuhkan }}gr/unit</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Produksi -->
    <div class="modal fade" id="modalProduksi" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🏭 Jalankan Produksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('produksi.jalankan') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Produk</label>
                            <select name="recipe_id" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($recipes as $item)
                                <option value="{{ $item['recipe']->id }}">
                                    {{ $item['recipe']->nama_produk }} 
                                    (Maks: {{ number_format($item['maks_produksi']) }} unit)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Produksi</label>
                            <input type="number" name="qty_produksi" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-play-fill me-2"></i>Proses Produksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection