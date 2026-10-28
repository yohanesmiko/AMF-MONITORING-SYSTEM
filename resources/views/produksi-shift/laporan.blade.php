@extends('layouts.app')

@section('title', 'Laporan Produksi Harian')

@section('content')
    <div class="topbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"> Laporan Produksi Harian</h4>
                <p class="mb-0">{{ $product->nama_produk }} • {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer me-2"></i>Cetak
                </button>
                <a href="{{ route('produksi-shift.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Header Laporan -->
    <div class="card-custom mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-1">{{ $product->nama_produk }}</h3>
                <p class="mb-0 opacity-75">{{ $product->kode_produk }} • {{ $product->kategori ?? 'Produk Reguler' }}</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="small opacity-75">Cycle Time Standar</div>
                <h2 class="mb-0">{{ $product->cycle_time_standar }}s</h2>
                <div class="small opacity-75 mt-1">Target/Shift: {{ number_format($product->target_per_shift) }} unit</div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary"><i class="bi bi-box-seam-fill"></i></div>
                <h3>{{ number_format($totalOutput, 0) }}</h3>
                <p>Total Output (3 Shift)</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success"><i class="bi bi-graph-up-arrow"></i></div>
                <h3>{{ number_format($efisiensiTotal, 1) }}%</h3>
                <p>Efisiensi Total</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning"><i class="bi bi-clock"></i></div>
                <h3>{{ number_format($avgCycleTime, 1) }}s</h3>
                <p>Rata-rata Cycle Time</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon {{ $totalVariance > 0 ? 'danger' : 'info' }}"><i class="bi bi-boxes"></i></div>
                <h3 style="font-size: 1.3rem;">{{ number_format($totalVariance/1000, 2) }} kg</h3>
                <p>Variance Material</p>
            </div>
        </div>
    </div>

    <!-- TABEL LAPORAN 3 SHIFT (INTI) -->
    <div class="card-custom mb-4">
        <h5><i class="bi bi-table me-2"></i>📋 Rekap Produksi 3 Shift</h5>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th rowspan="2">Metric</th>
                        <th colspan="3" class="text-center bg-primary text-white">Per Shift</th>
                        <th rowspan="2" class="bg-success text-white">TOTAL</th>
                    </tr>
                    <tr>
                        <th class="text-center">Shift 1 🌅</th>
                        <th class="text-center">Shift 2 ☀️</th>
                        <th class="text-center">Shift 3 🌙</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>📦 Output (unit)</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="text-center">
                            <strong>{{ $shifts[$s]->output_aktual ?? 0 }}</strong>
                        </td>
                        @endforeach
                        <td class="text-center bg-light"><strong>{{ number_format($totalOutput, 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>🎯 Target (unit)</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="text-center">{{ $shifts[$s]->target_output ?? 0 }}</td>
                        @endforeach
                        <td class="text-center bg-light"><strong>{{ number_format($targetTotal, 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>📈 Efisiensi</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="text-center">
                            @php $eff = $shifts[$s]->efisiensi ?? 0; @endphp
                            <span class="badge bg-{{ $eff >= 90 ? 'success' : ($eff >= 70 ? 'warning' : 'danger') }}">
                                {{ number_format($eff, 1) }}%
                            </span>
                        </td>
                        @endforeach
                        <td class="text-center bg-light">
                            <strong class="{{ $efisiensiTotal >= 90 ? 'text-success' : ($efisiensiTotal >= 70 ? 'text-warning' : 'text-danger') }}">
                                {{ number_format($efisiensiTotal, 1) }}%
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>⏱️ Cycle Time Aktual</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="text-center">{{ $shifts[$s]->cycle_time_aktual ? number_format($shifts[$s]->cycle_time_aktual, 1) . 's' : '-' }}</td>
                        @endforeach
                        <td class="text-center bg-light"><strong>{{ number_format($avgCycleTime, 1) }}s</strong></td>
                    </tr>
                    <tr>
                        <td><strong>🔧 Downtime</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="text-center">{{ $shifts[$s]->downtime_menit ?? 0 }} menit</td>
                        @endforeach
                        <td class="text-center bg-light"><strong>{{ $totalDowntime }} menit</strong></td>
                    </tr>
                    <tr class="table-info">
                        <td colspan="5"><strong>📦 DETAIL MATERIAL</strong></td>
                    </tr>
                    @foreach($product->materials as $pm)
                    @php
                        $totalStandar = 0; $totalAktual = 0;
                    @endphp
                    <tr>
                        <td colspan="5"><strong>{{ $pm->material->nama_material }}</strong> ({{ number_format($pm->qty_per_unit, 2) }} {{ $pm->satuan }}/unit)</td>
                    </tr>
                    <tr>
                        <td class="ps-4">Standar (gr)</td>
                        @foreach(['1','2','3'] as $s)
                        @php
                            $std = $shifts[$s] ? $shifts[$s]->output_aktual * $pm->qty_per_unit : 0;
                            $totalStandar += $std;
                        @endphp
                        <td class="text-center">{{ number_format($std, 2) }}</td>
                        @endforeach
                        <td class="text-center bg-light"><strong>{{ number_format($totalStandar, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="ps-4">Aktual (gr)</td>
                        @foreach(['1','2','3'] as $s)
                        @php
                            $akt = $shifts[$s] ? ($shifts[$s]->runMaterials->where('material_id', $pm->material_id)->first()->qty_aktual ?? 0) : 0;
                            $totalAktual += $akt;
                        @endphp
                        <td class="text-center">{{ number_format($akt, 2) }}</td>
                        @endforeach
                        <td class="text-center bg-light"><strong>{{ number_format($totalAktual, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="ps-4">Variance (gr)</td>
                        @foreach(['1','2','3'] as $s)
                        @php
                            $std = $shifts[$s] ? $shifts[$s]->output_aktual * $pm->qty_per_unit : 0;
                            $akt = $shifts[$s] ? ($shifts[$s]->runMaterials->where('material_id', $pm->material_id)->first()->qty_aktual ?? 0) : 0;
                        @endphp
                        <td class="text-center">
                            <span class="{{ ($akt-$std) > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($akt - $std, 2) }}
                            </span>
                        </td>
                        @endforeach
                        @php $var = $totalAktual - $totalStandar; @endphp
                        <td class="text-center bg-light">
                            <strong class="{{ $var > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($var, 2) }}
                            </strong>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <td><strong>👷 Operator</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="text-center">{{ $shifts[$s]->operator->name ?? '-' }}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td><strong> Catatan</strong></td>
                        @foreach(['1','2','3'] as $s)
                        <td class="small">{{ $shifts[$s]->catatan ?? '-' }}</td>
                        @endforeach
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Perbandingan Shift -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card-custom">
                <h5><i class="bi bi-bar-chart-fill me-2"></i>Output per Shift</h5>
                <canvas id="outputChart" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-custom">
                <h5><i class="bi bi-pie-chart-fill me-2"></i>Efisiensi per Shift</h5>
                <canvas id="efficiencyChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Analisis -->
    <div class="card-custom">
        <h5><i class="bi bi-lightbulb me-2"></i> Analisis & Rekomendasi</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded" style="background: {{ $efisiensiTotal >= 90 ? '#d1fae5' : ($efisiensiTotal >= 70 ? '#fef3c7' : '#fee2e2') }};">
                    <h6 class="mb-1">Performa Hari Ini</h6>
                    <p class="mb-0 small">
                        @if($efisiensiTotal >= 90)
                            ✅ <strong>Excellent!</strong> Efisiensi {{ number_format($efisiensiTotal, 1) }}% melampaui target.
                        @elseif($efisiensiTotal >= 70)
                            ⚠️ <strong>Cukup Baik.</strong> Efisiensi {{ number_format($efisiensiTotal, 1) }}%, masih bisa ditingkatkan.
                        @else
                            🚨 <strong>Perlu Perhatian!</strong> Efisiensi hanya {{ number_format($efisiensiTotal, 1) }}%.
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded" style="background: {{ $totalVariance <= 0 ? '#d1fae5' : '#fee2e2' }};">
                    <h6 class="mb-1">Penggunaan Material</h6>
                    <p class="mb-0 small">
                        @if($totalVariance <= 0)
                            ✅ Material efisien, hemat {{ number_format(abs($totalVariance)/1000, 2) }} kg dari standar.
                        @else
                            ⚠️ Ada pemborosan {{ number_format($totalVariance/1000, 2) }} kg material.
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded bg-info bg-opacity-10">
                    <h6 class="mb-1">Best Shift</h6>
                    @php
                        $bestShift = collect($shifts)->filter()->sortByDesc('efisiensi')->first();
                    @endphp
                    <p class="mb-0 small">
                        🏆 Shift {{ $bestShift->shift ?? '-' }} dengan efisiensi {{ number_format($bestShift->efisiensi ?? 0, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const shiftLabels = @json($shiftLabels);
        const shiftOutputs = @json($shiftOutputs);
        const shiftEfisiensi = @json($shiftEfisiensi);

        new Chart(document.getElementById('outputChart'), {
            type: 'bar',
            data: {
                labels: shiftLabels,
                datasets: [{
                    label: 'Output (unit)',
                    data: shiftOutputs,
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        new Chart(document.getElementById('efficiencyChart'), {
            type: 'doughnut',
            data: {
                labels: shiftLabels,
                datasets: [{
                    data: shiftEfisiensi,
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection