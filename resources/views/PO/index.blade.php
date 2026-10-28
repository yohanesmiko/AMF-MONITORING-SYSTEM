@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-file-earmark-text me-2"></i>Purchase Order</h4>
                <p class="mb-0" style="opacity: 0.9;">Kelola PO berdasarkan quantity kebutuhan</p>
            </div>
            <a href="{{ route('po.create') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Buat PO Baru
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
                <div class="icon primary"><i class="bi bi-file-earmark-text"></i></div>
                <h3>{{ $stats['total_po'] }}</h3>
                <p>Total PO</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-pencil"></i></div>
                <h3>{{ $stats['draft'] }}</h3>
                <p>Draft</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon info"><i class="bi bi-send"></i></div>
                <h3>{{ $stats['terkirim'] }}</h3>
                <p>Terkirim</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-cash-stack"></i></div>
                <h3>Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</h3>
                <p>Total Nilai PO</p>
            </div>
        </div>
    </div>

    <!-- Tabel PO -->
    <div class="card-custom mt-4">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Material</th>
                        <th>Qty</th>
                        <th>Total Harga</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td><strong>{{ $po->nomor_po }}</strong></td>
                        <td>{{ $po->material->nama_material }}</td>
                        <td>{{ number_format($po->qty, 2) }} {{ $po->material->satuan }}</td>
                        <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $po->supplier }}</td>
                        <td>{{ $po->tanggal_po->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $po->status == 'selesai' ? 'success' : ($po->status == 'draft' ? 'secondary' : 'primary') }}">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('po.show', $po) }}"><i class="bi bi-eye me-2"></i>Detail</a></li>
                                    <li><a class="dropdown-item" href="{{ route('po.edit', $po) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Update Status</h6></li>
                                    <li>
                                        <form action="{{ route('po.update-status', $po) }}" method="POST">
                                            @csrf
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="draft" {{ $po->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="terkirim" {{ $po->status == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                                <option value="diproses" {{ $po->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="selesai" {{ $po->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="dibatalkan" {{ $po->status == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-file-earmark-text" style="font-size: 3rem;"></i>
                            <p class="mt-3">Belum ada Purchase Order</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseOrders->hasPages())
        <div class="mt-3">{{ $purchaseOrders->links() }}</div>
        @endif
    </div>
@endsection