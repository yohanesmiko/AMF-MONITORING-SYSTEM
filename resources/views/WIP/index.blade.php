@extends('layouts.app')

@section('title', 'Barang WIP')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-gear-wide-connected me-2"></i>Barang WIP (Work In Progress)</h4>
                <p class="mb-0" style="opacity: 0.9;">Pantau proses produksi material</p>
            </div>
            <a href="{{ route('wip.create') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Tambah WIP
            </a>
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
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-gear"></i></div>
                <h3>{{ $stats['total_wip'] }}</h3>
                <p>Total WIP</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-hourglass"></i></div>
                <h3>{{ $stats['antrian'] }}</h3>
                <p>Antrian</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon info"><i class="bi bi-play-circle"></i></div>
                <h3>{{ $stats['diproses'] }}</h3>
                <p>Sedang Diproses</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-check-circle"></i></div>
                <h3>{{ $stats['selesai'] }}</h3>
                <p>Selesai</p>
            </div>
        </div>
    </div>

    <!-- Tabel WIP -->
    <div class="card-custom mt-4">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. WIP</th>
                        <th>Material</th>
                        <th>Input</th>
                        <th>Proses</th>
                        <th>Output</th>
                        <th>Mesin</th>
                        <th>Mulai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wipItems as $wip)
                    <tr>
                        <td><strong>{{ $wip->nomor_wip }}</strong></td>
                        <td>{{ $wip->material->nama_material }}</td>
                        <td>{{ number_format($wip->qty_input, 2) }}</td>
                        <td>{{ number_format($wip->qty_proses, 2) }}</td>
                        <td>{{ number_format($wip->qty_output, 2) }}</td>
                        <td>{{ $wip->mesin ?? '-' }}</td>
                        <td>{{ $wip->tanggal_mulai->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $wip->status == 'selesai' ? 'success' : ($wip->status == 'diproses' ? 'info' : ($wip->status == 'antrian' ? 'warning' : 'danger')) }}">
                                {{ ucfirst($wip->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('wip.update-status', $wip) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                    <option value="antrian" {{ $wip->status == 'antrian' ? 'selected' : '' }}>Antrian</option>
                                    <option value="diproses" {{ $wip->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ $wip->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ $wip->status == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-gear-wide-connected" style="font-size: 3rem;"></i>
                            <p class="mt-3">Belum ada data WIP</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($wipItems->hasPages())
        <div class="mt-3">{{ $wipItems->links() }}</div>
        @endif
    </div>
@endsection