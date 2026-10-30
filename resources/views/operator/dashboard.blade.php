@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('content')
<div class="topbar" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 25px 30px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1"> Selamat Datang, {{ auth()->user()->name }}!</h4>
            <p class="mb-0">
                <span class="badge bg-white text-primary me-2">
                    <i class="bi bi-person-badge me-1"></i>Operator Shift {{ auth()->user()->shift ?? '1' }}
                </span>
            </p>
        </div>
        <a href="{{ route('material-out.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
            <i class="bi bi-box-arrow-up me-2"></i>Input Material Out
        </a>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon primary"><i class="bi bi-box-arrow-up"></i></div>
            <h3>{{ number_format($stats['material_out_hari_ini'], 2) }}</h3>
            <p>Out Shift Ini (kg)</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon success"><i class="bi bi-receipt"></i></div>
            <h3>{{ $stats['total_transaksi'] }}</h3>
            <p>Total Transaksi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon warning"><i class="bi bi-exclamation-triangle"></i></div>
            <h3>{{ $stats['material_menipis'] }}</h3>
            <p>Material Menipis</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon danger"><i class="bi bi-x-circle"></i></div>
            <h3>{{ $stats['material_habis'] }}</h3>
            <p>Material Habis</p>
        </div>
    </div>
</div>

<div class="card-custom mt-4">
    <h5><i class="bi bi-lightning-fill me-2 text-warning"></i>Aksi Cepat</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="{{ route('material-out.index') }}" class="card-custom text-decoration-none d-block text-center p-4" style="border: 2px dashed #e2e8f0;">
                <i class="bi bi-box-arrow-up-fill" style="font-size: 2.5rem; color: #4f46e5;"></i>
                <h6 class="mt-2 mb-0">Input Material Out</h6>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('material.index') }}" class="card-custom text-decoration-none d-block text-center p-4" style="border: 2px dashed #e2e8f0;">
                <i class="bi bi-eye-fill" style="font-size: 2.5rem; color: #10b981;"></i>
                <h6 class="mt-2 mb-0">Lihat Stok Material</h6>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card-custom text-center p-4" style="border: 2px dashed #e2e8f0;">
                <i class="bi bi-info-circle-fill" style="font-size: 2.5rem; color: #f59e0b;"></i>
                <h6 class="mt-2 mb-0">Role: {{ strtoupper(auth()->user()->role) }}</h6>
                <p class="text-muted small mb-0">Shift {{ auth()->user()->shift ?? '1' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection