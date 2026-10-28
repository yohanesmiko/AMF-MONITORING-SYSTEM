@extends('layouts.app')

@section('title', 'Warning System')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
        <h4 class="mb-1">🚨 Warning System - Stok Material</h4>
        <p class="mb-0">Monitoring real-time estimasi hari habis material</p>
    </div>

    <!-- Stats Level -->
    <div class="row g-4 mb-4 mt-3">
        <div class="col-md">
            <div class="stat-card" style="border-left: 4px solid #000;">
                <h3 style="color: #000;">{{ $stats['habis'] }}</h3>
                <p>⚫ Habis</p>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card" style="border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626;">{{ $stats['kritis'] }}</h3>
                <p>🔴 Kritis (≤3 hari)</p>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card" style="border-left: 4px solid #f97316;">
                <h3 style="color: #f97316;">{{ $stats['urgent'] }}</h3>
                <p>🟠 Urgent (≤7 hari)</p>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card" style="border-left: 4px solid #eab308;">
                <h3 style="color: #eab308;">{{ $stats['warning'] }}</h3>
                <p>🟡 Warning (≤14 hari)</p>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card" style="border-left: 4px solid #10b981;">
                <h3 style="color: #10b981;">{{ $stats['aman'] }}</h3>
                <p>🟢 Aman (>14 hari)</p>
            </div>
        </div>
    </div>

    <!-- Form Catat Penggunaan -->
    <div class="card-custom mb-4">
        <h5><i class="bi bi-pencil-square me-2"></i> Catat Penggunaan Material Hari Ini</h5>
        <form action="{{ route('warning.catat') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="material_id" class="form-select" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $item)
                        <option value="{{ $item['material']->id }}">{{ $item['material']->nama_material }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="qty_penggunaan" class="form-control" placeholder="Qty digunakan" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Catat</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Warning -->
    <div class="card-custom">
        <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>📊 Daftar Material Berdasarkan Urgensi</h5>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Stok</th>
                        <th>Avg Harian</th>
                        <th>Estimasi Habis</th>
                        <th>Level</th>
                        <th>Rekomendasi PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item['material']->nama_material }}</strong><br>
                            <small class="text-muted">{{ $item['material']->kode_material }} • {{ strtoupper($item['material']->jenis) }}</small>
                        </td>
                        <td>{{ number_format($item['material']->stok, 2) }} {{ $item['material']->satuan }}</td>
                        <td>{{ number_format($item['avg_harian'], 2) }} {{ $item['material']->satuan }}/hari</td>
                        <td>
                            @if($item['hari_habis'] === null)
                                <span class="text-muted">Tidak ada data</span>
                            @elseif($item['hari_habis'] <= 3)
                                <strong class="text-danger">{{ $item['hari_habis'] }} hari lagi! 🚨</strong>
                            @elseif($item['hari_habis'] <= 7)
                                <strong class="text-warning">{{ $item['hari_habis'] }} hari</strong>
                            @else
                                {{ $item['hari_habis'] }} hari
                            @endif
                        </td>
                        <td>
                            @if($item['level'] == 'habis')
                                <span class="badge bg-dark">HABIS</span>
                            @elseif($item['level'] == 'kritis')
                                <span class="badge bg-danger">KRITIS</span>
                            @elseif($item['level'] == 'urgent')
                                <span class="badge bg-warning text-dark">URGENT</span>
                            @elseif($item['level'] == 'warning')
                                <span class="badge bg-info text-dark">WARNING</span>
                            @else
                                <span class="badge bg-success">AMAN</span>
                            @endif
                        </td>
                        <td>
                            @if($item['rekomendasi_qty'] > 0)
                                <strong>{{ number_format($item['rekomendasi_qty'], 2) }} {{ $item['material']->satuan }}</strong>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('po.create') }}?material_id={{ $item['material']->id }}" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-cart-plus"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection