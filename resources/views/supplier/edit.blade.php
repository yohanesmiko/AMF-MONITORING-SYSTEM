@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
    <!-- Topbar dengan Style Berbeda -->
    <div class="topbar" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-people me-2"></i>Manajemen Supplier</h4>
                <p class="mb-0" style="opacity: 0.9;">Kelola daftar supplier dan vendor perusahaan</p>
            </div>
            <a href="{{ route('supplier.create') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Tambah Supplier
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Search -->
    <div class="card-custom mt-4">
        <form action="{{ route('supplier.index') }}" method="GET">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama supplier, kode, atau kota..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>

    <!-- Grid Card Layout -->
    <div class="row g-4 mt-2">
        @forelse($suppliers as $supplier)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom" style="transition: transform 0.3s;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-building text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <span class="badge {{ $supplier->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($supplier->status) }}
                    </span>
                </div>
                <h6 class="mb-1">{{ $supplier->nama_supplier }}</h6>
                <p class="text-muted small mb-2">{{ $supplier->kode_supplier }}</p>
                
                <div class="mt-3" style="font-size: 0.85rem;">
                    <p class="mb-1"><i class="bi bi-person me-2 text-primary"></i>{{ $supplier->kontak }}</p>
                    @if($supplier->telepon)
                    <p class="mb-1"><i class="bi bi-telephone me-2 text-primary"></i>{{ $supplier->telepon }}</p>
                    @endif
                    @if($supplier->email)
                    <p class="mb-1"><i class="bi bi-envelope me-2 text-primary"></i>{{ $supplier->email }}</p>
                    @endif
                    @if($supplier->kota)
                    <p class="mb-0"><i class="bi bi-geo-alt me-2 text-primary"></i>{{ $supplier->kota }}</p>
                    @endif
                </div>

                <div class="d-flex gap-2 mt-3 pt-3" style="border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('supplier.destroy', $supplier) }}" method="POST" class="flex-fill"
                          onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-people" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">Belum ada data supplier</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($suppliers->hasPages())
    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
    @endif
@endsection