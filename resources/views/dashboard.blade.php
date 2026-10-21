@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Dashboard Inventory</h4>
            <p class="text-muted mb-0">Selamat datang di sistem inventory ATMI MIKAEL FORTUNA</p>
        </div>
        <div>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-circle me-2"></i>Tambah Barang
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3>{{ number_format($totalBarang) }}</h3>
                <p>Total Jenis Barang</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon success">
                    <i class="bi bi-stack"></i>
                </div>
                <h3>{{ number_format($totalStok) }}</h3>
                <p>Total Stok Barang</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3>Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
                <p>Total Nilai Inventory</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3>{{ $stokMenipis }}</h3>
                <p>Stok Menipis</p>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-4 mb-4">
        <!-- Pie Chart: Stok per Kategori -->
        <div class="col-lg-6">
            <div class="card-custom">
                <h5><i class="bi bi-pie-chart me-2"></i>Distribusi Stok per Kategori</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart: Nilai per Kategori -->
        <div class="col-lg-6">
            <div class="card-custom">
                <h5><i class="bi bi-bar-chart me-2"></i>Nilai Inventory per Kategori</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="nilaiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-4 mb-4">
        <!-- Doughnut Chart: Status Barang -->
        <div class="col-lg-4">
            <div class="card-custom">
                <h5><i class="bi bi-check-circle me-2"></i>Status Barang</h5>
                <div style="position: relative; height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart Horizontal: Top 10 Stok -->
        <div class="col-lg-8">
            <div class="card-custom">
                <h5><i class="bi bi-trophy me-2"></i>Top 10 Barang dengan Stok Terbanyak</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="topStokChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="row g-4">
        <!-- Barang Terbaru -->
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Barang Terbaru Ditambahkan</h5>
                    <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangTerbaru as $barang)
                            <tr>
                                <td><strong>{{ $barang->kode_barang }}</strong></td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td><span class="badge bg-light text-dark">{{ $barang->kategori }}</span></td>
                                <td>{{ $barang->stok }} {{ $barang->satuan }}</td>
                                <td>
                                    <span class="badge-status badge-{{ $barang->status }}">
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="col-lg-4">
            <div class="card-custom">
                <h5>
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Stok Menipis
                </h5>
                <div class="list-group list-group-flush">
                    @forelse($stokMenipisList as $barang)
                    <div class="list-group-item border-0 px-0 py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $barang->nama_barang }}</h6>
                                <small class="text-muted">{{ $barang->kode_barang }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">
                                {{ $barang->stok }} {{ $barang->satuan }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Semua stok aman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Chart Configuration -->
    <script>
        // Data dari Laravel
        const kategoriLabels = @json($kategoriData->pluck('kategori'));
        const kategoriData = @json($kategoriData->pluck('total_stok'));
        
        const nilaiLabels = @json($nilaiKategori->pluck('kategori'));
        const nilaiData = @json($nilaiKategori->pluck('total_nilai'));
        
        const statusLabels = ['Tersedia', 'Habis', 'Rusak'];
        const statusData = [@json($statusData['tersedia']), @json($statusData['habis']), @json($statusData['rusak'])];
        
        const topStokLabels = @json($topStok->pluck('nama_barang'));
        const topStokData = @json($topStok->pluck('stok'));

        // Warna untuk chart
        const colors = [
            '#2563eb', '#10b981', '#f59e0b', '#ef4444', 
            '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'
        ];

        // Chart 1: Pie Chart - Stok per Kategori
        if (kategoriLabels.length > 0) {
            new Chart(document.getElementById('kategoriChart'), {
                type: 'pie',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        data: kategoriData,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, font: { size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + ' unit';
                                }
                            }
                        }
                    }
                }
            });
        } else {
            document.getElementById('kategoriChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }

        // Chart 2: Bar Chart - Nilai per Kategori
        if (nilaiLabels.length > 0) {
            new Chart(document.getElementById('nilaiChart'), {
                type: 'bar',
                data: {
                    labels: nilaiLabels,
                    datasets: [{
                        label: 'Nilai Inventory (Rp)',
                        data: nilaiData,
                        backgroundColor: '#2563eb',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    }
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                }
                            }
                        }
                    }
                }
            });
        } else {
            document.getElementById('nilaiChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }

        // Chart 3: Doughnut Chart - Status Barang
        const totalStatus = statusData.reduce((a, b) => a + b, 0);
        if (totalStatus > 0) {
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15, font: { size: 12 } }
                        }
                    }
                }
            });
        } else {
            document.getElementById('statusChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }

        // Chart 4: Horizontal Bar - Top 10 Stok
        if (topStokLabels.length > 0) {
            new Chart(document.getElementById('topStokChart'), {
                type: 'bar',
                data: {
                    labels: topStokLabels,
                    datasets: [{
                        label: 'Stok',
                        data: topStokData,
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' unit';
                                }
                            }
                        }
                    }
                }
            });
        } else {
            document.getElementById('topStokChart').parentElement.innerHTML = '<div class="text-center text-muted py-5">Belum ada data</div>';
        }
    </script>
@endsection