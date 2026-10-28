@extends('layouts.app')

@section('title', 'Kalkulasi Kebutuhan')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div>
            <h4 class="mb-1"><i class="bi bi-calculator me-2"></i>Kalkulasi Kebutuhan Material</h4>
            <p class="mb-0" style="opacity: 0.9;">Hitung estimasi hari kebutuhan & warning stok menipis</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistik -->
    <div class="row g-4 mt-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-layers"></i></div>
                <h3>{{ $totalMaterial }}</h3>
                <p>Total Material</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon danger"><i class="bi bi-exclamation-triangle"></i></div>
                <h3>{{ $butuhRestock }}</h3>
                <p>Butuh Restock (≤7 hari)</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-shield-check"></i></div>
                <h3>{{ $totalMaterial - $butuhRestock }}</h3>
                <p>Stok Aman</p>
            </div>
        </div>
    </div>

    <!-- Form Catat Penggunaan Harian -->
    <div class="card-custom mt-4">
        <h5 class="mb-3"><i class="bi bi-pencil-square me-2"></i>Catat Penggunaan Harian</h5>
        <form action="{{ route('kalkulasi.catat') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Material</label>
                    <select name="material_id" class="form-select" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($kalkulasiData as $data)
                        <option value="{{ $data['material']->id }}">{{ $data['material']->nama_material }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Qty Penggunaan</label>
                    <input type="number" step="0.01" name="qty_penggunaan" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus"></i> Catat
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Kalkulasi -->
    <div class="card-custom mt-4">
        <h5 class="mb-3"><i class="bi bi-graph-up me-2"></i>Analisis Kebutuhan Material</h5>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Jenis</th>
                        <th>Stok Saat Ini</th>
                        <th>Rata-rata 7 Hari</th>
                        <th>Rata-rata 30 Hari</th>
                        <th>Estimasi Habis (7h)</th>
                        <th>Estimasi Habis (30h)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kalkulasiData as $data)
                    <tr class="{{ $data['butuh_restock'] ? 'table-warning' : '' }}">
                        <td><strong>{{ $data['material']->nama_material }}</strong><br><small class="text-muted">{{ $data['material']->kode_material }}</small></td>
                        <td><span class="badge bg-{{ $data['material']->jenis == 'ct' ? 'primary' : ($data['material']->jenis == 'masterbatch' ? 'info' : 'secondary') }}">{{ strtoupper($data['material']->jenis) }}</span></td>
                        <td>{{ number_format($data['material']->stok, 2) }} {{ $data['material']->satuan }}</td>
                        <td>{{ number_format($data['avg_7_hari'], 2) }} {{ $data['material']->satuan }}/hari</td>
                        <td>{{ number_format($data['avg_30_hari'], 2) }} {{ $data['material']->satuan }}/hari</td>
                        <td>
                            @if($data['estimasi_7_hari'])
                                <strong>{{ $data['estimasi_7_hari'] }} hari</strong>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($data['estimasi_30_hari'])
                                {{ $data['estimasi_30_hari'] }} hari
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($data['butuh_restock'])
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Butuh Restock</span>
                            @elseif($data['estimasi_7_hari'] && $data['estimasi_7_hari'] <= 14)
                                <span class="badge bg-warning">Perhatian</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            Belum ada data material
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection