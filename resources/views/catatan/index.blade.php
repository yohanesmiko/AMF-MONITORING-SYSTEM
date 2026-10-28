@extends('layouts.app')

@section('title', 'Catatan')

@section('content')
    <div class="topbar" style="background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: white; border-radius: 12px; padding: 25px 30px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-journal-text me-2"></i>Catatan</h4>
                <p class="mb-0" style="opacity: 0.9;">Catatan penting untuk inventory</p>
            </div>
            <a href="{{ route('catatan.create') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                <i class="bi bi-plus-circle me-2"></i>Tambah Catatan
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Grid Catatan -->
    <div class="row g-4 mt-3">
        @forelse($notes as $note)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom" style="border-top: 4px solid {{ $note->warna == 'primary' ? '#2563eb' : ($note->warna == 'success' ? '#10b981' : ($note->warna == 'warning' ? '#f59e0b' : ($note->warna == 'danger' ? '#ef4444' : '#06b6d4'))) }}; {{ $note->is_pinned ? 'background: #fef3c7;' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        @if($note->is_pinned)
                        <i class="bi bi-pin-fill text-warning me-1"></i>
                        @endif
                        <span class="badge bg-{{ $note->warna }}">{{ ucfirst($note->kategori) }}</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('catatan.edit', $note) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                            <li>
                                <form action="{{ route('catatan.destroy', $note) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <h6 class="mb-2">{{ $note->judul }}</h6>
                <p class="text-muted small mb-2" style="white-space: pre-wrap; max-height: 120px; overflow: hidden;">{{ $note->isi }}</p>
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>{{ $note->created_at->diffForHumans() }}
                    @if($note->user) · {{ $note->user->name }}@endif
                </small>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-journal-text" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">Belum ada catatan</p>
                <a href="{{ route('catatan.create') }}" class="btn btn-warning mt-2">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Catatan Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if($notes->hasPages())
    <div class="mt-4">{{ $notes->links() }}</div>
    @endif
@endsection