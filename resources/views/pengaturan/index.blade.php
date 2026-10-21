@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <!-- Topbar -->
    <div class="topbar" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); color: white; border-radius: 12px;">
        <div>
            <h4 class="mb-1"><i class="bi bi-gear me-2"></i>Pengaturan</h4>
            <p class="mb-0" style="opacity: 0.9;">Kelola profil dan preferensi akun Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" style="border-radius: 12px; border: none;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="card-custom mt-4">
        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button">
                    <i class="bi bi-person me-2"></i>Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button">
                    <i class="bi bi-shield-lock me-2"></i>Password
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="app-tab" data-bs-toggle="tab" data-bs-target="#app" type="button">
                    <i class="bi bi-sliders me-2"></i>Aplikasi
                </button>
            </li>
        </ul>

        <div class="tab-content mt-4" id="settingsTabContent">
            <!-- Tab Profil -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <form action="{{ route('pengaturan.profile') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->role) }}" disabled>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Tab Password -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form action="{{ route('pengaturan.password') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-shield-check me-2"></i>Update Password
                    </button>
                </form>
            </div>

            <!-- Tab Aplikasi -->
            <div class="tab-pane fade" id="app" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3" style="background: #f8fafc; border-radius: 12px;">
                            <h6><i class="bi bi-info-circle me-2"></i>Informasi Aplikasi</h6>
                            <p class="mb-1"><strong>Nama:</strong> ATMI MIKAEL FORTUNA Inventory</p>
                            <p class="mb-1"><strong>Versi:</strong> 1.0.0</p>
                            <p class="mb-0"><strong>Framework:</strong> Laravel 11</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background: #f8fafc; border-radius: 12px;">
                            <h6><i class="bi bi-database me-2"></i>Database</h6>
                            <p class="mb-1"><strong>Driver:</strong> MySQL</p>
                            <p class="mb-1"><strong>Host:</strong> localhost</p>
                            <p class="mb-0"><strong>Database:</strong> atmi_inventory</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection