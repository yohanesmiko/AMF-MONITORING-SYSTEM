@extends('layouts.app')

@section('title', 'Produksi Shift')

@section('content')
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">🏭 Produksi Shift - Monitoring</h4>
                <p class="mb-0">Pencatatan produksi 3 shift dengan cycle time</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('produksi-shift.setup') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-gear me-2"></i>Setup Produk
                </a>
                <a href="{{ route('produksi-shift.laporan', ['product_id' => $defaultProduct->id ?? 1, 'tanggal' => $today]) }}" class="btn btn-primary-custom">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Harian
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Pilih Produk -->
    @if($products->count() > 1)
    <div class="card-custom mb-4">
        <h5><i class="bi bi-box-seam me-2"></i>Pilih Produk</h5>
        <div class="row g-3">
            @foreach($products as $product)
            <div class="col-md-4">
                <a href="{{ route('produksi-shift.index', ['product_id' => $product->id]) }}" 
                   class="card-custom text-decoration-none {{ ($defaultProduct->id ?? 0) == $product->id ? 'border-primary' : '' }}"
                   style="display: block;">
                    <h6 class="mb-1">{{ $product->nama_produk }}</h6>
                    <small class="text-muted">{{ $product->kode_produk }}</small>
                    <div class="mt-2 small">
                        <span class="badge bg-info">CT: {{ $product->cycle_time_standar }}s</span>
                        <span class="badge bg-primary">Target: {{ number_format($product->target_per_shift) }}/shift</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($defaultProduct)
    <!-- Info Produk -->
    <div class="card-custom mb-4">
        <div class="row">
            <div class="col-md-8">
                <h5 class="mb-2">{{ $defaultProduct->nama_produk }}</h5>
                <p class="text-muted mb-0">{{ $defaultProduct->deskripsi ?? 'Produk produksi reguler' }}</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="small text-muted">Cycle Time Standar</div>
                <h3 class="text-primary mb-0">{{ $defaultProduct->cycle_time_standar }} <small class="text-muted">detik/unit</small></h3>
                <div class="small text-muted mt-2">Target per Shift: <strong>{{ number_format($defaultProduct->target_per_shift) }} unit</strong></div>
            </div>
        </div>
        <hr>
        <h6 class="mb-2">📦 Material per Unit:</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($defaultProduct->materials as $pm)
            <span class="badge bg-light text-dark p-2">
                <i class="bi bi-circle-fill text-{{ $loop->index == 0 ? 'primary' : 'success' }} me-1"></i>
                {{ $pm->material->nama_material }}: <strong>{{ number_format($pm->qty_per_unit, 2) }} {{ $pm->satuan }}</strong>
            </span>
            @endforeach
        </div>
    </div>

    <!-- Status 3 Shift Hari Ini -->
    <h5 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Status Shift Hari Ini ({{ \Carbon\Carbon::parse($today)->format('d/m/Y') }})</h5>
    <div class="row g-4 mb-4">
        @foreach(['1' => '🌅 Shift 1 (06:00-14:00)', '2' => '☀️ Shift 2 (14:00-22:00)', '3' => '🌙 Shift 3 (22:00-06:00)'] as $shiftNum => $shiftName)
        <div class="col-md-4">
            <div class="card-custom" style="border-top: 4px solid {{ $shiftsToday[$shiftNum] ? '#10b981' : '#cbd5e1' }};">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="mb-0">{{ $shiftName }}</h6>
                    @if($shiftsToday[$shiftNum])
                        <span class="badge bg-success">✅ Terinput</span>
                    @else
                        <span class="badge bg-secondary">⏳ Belum</span>
                    @endif
                </div>

                @if($shiftsToday[$shiftNum])
                    @php $run = $shiftsToday[$shiftNum]; @endphp
                    <div class="mb-2">
                        <small class="text-muted">Output Aktual</small>
                        <h4 class="mb-0">{{ number_format($run->output_aktual, 0) }} <small class="text-muted">unit</small></h4>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Efisiensi</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $run->efisiensi >= 90 ? 'success' : ($run->efisiensi >= 70 ? 'warning' : 'danger') }}" 
                                 style="width: {{ min(100, $run->efisiensi) }}%"></div>
                        </div>
                        <small><strong>{{ number_format($run->efisiensi, 1) }}%</strong> (Target: {{ number_format($run->target_output, 0) }})</small>
                    </div>
                    <div class="small text-muted">
                        <div>⏱️ CT Aktual: {{ number_format($run->cycle_time_aktual, 1) }}s</div>
                        <div>🔧 Downtime: {{ $run->downtime_menit }} menit</div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history" style="font-size: 2rem; color: #cbd5e1;"></i>
                        <p class="text-muted small mt-2">Belum ada input</p>
                    </div>
                @endif

                <a href="{{ route('produksi-shift.create', ['product_id' => $defaultProduct->id, 'shift' => $shiftNum, 'tanggal' => $today]) }}" 
                   class="btn btn-{{ $shiftsToday[$shiftNum] ? 'outline-primary' : 'primary' }} w-100 mt-3">
                    <i class="bi bi-{{ $shiftsToday[$shiftNum] ? 'pencil' : 'plus-circle' }} me-2"></i>
                    {{ $shiftsToday[$shiftNum] ? 'Edit Input' : 'Input Produksi' }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card-custom text-center py-5">
        <i class="bi bi-box-seam" style="font-size: 3rem; color: #cbd5e1;"></i>
        <p class="text-muted mt-3">Belum ada produk produksi. Silakan setup terlebih dahulu.</p>
        <a href="{{ route('produksi-shift.setup') }}" class="btn btn-primary-custom">Setup Produk</a>
    </div>
    @endif
@endsection