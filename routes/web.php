<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OperatorDashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialOutController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\POController;
use App\Http\Controllers\WipController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\KalkulasiController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\ShiftProductionController;

// ==========================================
// 1. PUBLIC ROUTES (Tidak perlu login)
// ==========================================

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('operator.dashboard');
    }
    return view('auth.login');
})->name('home');

Route::get('/login', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('operator.dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan.']);
        }

        // Redirect berdasarkan role
        return $user->role === 'admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('operator.dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('success', 'Anda telah logout.');
})->name('logout');


// ==========================================
// 2. AUTHENTICATED ROUTES (Wajib Login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/operator/dashboard', [OperatorDashboardController::class, 'index'])->name('operator.dashboard');

    // --- MATERIAL OUT (Bisa diakses Admin & Operator) ---
    Route::get('/material-out', [MaterialOutController::class, 'index'])->name('material-out.index');
    Route::post('/material-out', [MaterialOutController::class, 'store'])->name('material-out.store');
    Route::delete('/material-out/{materialOut}', [MaterialOutController::class, 'destroy'])->name('material-out.destroy');
    Route::get('/material-out/info/{id}', [MaterialOutController::class, 'getMaterialInfo'])->name('material-out.info');

    // --- STOCK MATERIAL (Read-only untuk semua, CRUD diatur di controller) ---
    Route::get('/material', [MaterialController::class, 'index'])->name('material.index');
    Route::get('/material/{material}', [MaterialController::class, 'show'])->name('material.show');
    Route::resource('material', MaterialController::class)->except(['index', 'show']);

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- INVENTORY ---
    Route::resource('inventory', InventoryController::class);
    Route::get('/inventory/export/excel', [InventoryController::class, 'exportExcel'])->name('inventory.export.excel');
    Route::get('/inventory/export/pdf', [InventoryController::class, 'exportPdf'])->name('inventory.export.pdf');
    
    // --- TRANSAKSI ---
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barang-masuk.create');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
    
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
    Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
    Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
    
    // --- PRODUKSI & WIP ---
    Route::resource('wip', WipController::class);
    Route::post('/wip/{wip}/update-status', [WipController::class, 'updateStatus'])->name('wip.update-status');
    
    Route::get('/produksi-shift', [ShiftProductionController::class, 'index'])->name('produksi-shift.index');
    Route::get('/produksi-shift/create', [ShiftProductionController::class, 'create'])->name('produksi-shift.create');
    Route::post('/produksi-shift', [ShiftProductionController::class, 'store'])->name('produksi-shift.store');
    Route::get('/produksi-shift/laporan', [ShiftProductionController::class, 'laporan'])->name('produksi-shift.laporan');
    Route::get('/produksi-shift/setup', [ShiftProductionController::class, 'setupProduk'])->name('produksi-shift.setup');
    Route::post('/produksi-shift/setup', [ShiftProductionController::class, 'storeProduk'])->name('produksi-shift.store-produk');

    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
    Route::post('/produksi/jalankan', [ProduksiController::class, 'jalankanProduksi'])->name('produksi.jalankan');
    Route::post('/produksi/kalkulasi', [ProduksiController::class, 'kalkulasiDetail'])->name('produksi.kalkulasi');
    
    // --- PURCHASE ORDER & SUPPLIER ---
    Route::resource('po', POController::class);
    Route::post('/po/{po}/update-status', [POController::class, 'updateStatus'])->name('po.update-status');
    Route::resource('supplier', SupplierController::class);
    
    // --- LAPORAN & KALKULASI ---
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/kalkulasi', [KalkulasiController::class, 'index'])->name('kalkulasi.index');
    Route::post('/kalkulasi/hitung', [KalkulasiController::class, 'hitung'])->name('kalkulasi.hitung');
    Route::post('/kalkulasi/catat-penggunaan', [KalkulasiController::class, 'catatPenggunaan'])->name('kalkulasi.catat');
    
    // --- WARNING & CATATAN ---
    Route::get('/warning', [WarningController::class, 'index'])->name('warning.index');
    Route::post('/warning/catat', [WarningController::class, 'catatPenggunaan'])->name('warning.catat');
    Route::resource('catatan', CatatanController::class);
    
    // --- PENGATURAN ---
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::patch('/pengaturan/profile', [PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::patch('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    
    // Edit & Update Produk Produksi
Route::get('/produksi-shift/produk/{product}/edit', [\App\Http\Controllers\ShiftProductionController::class, 'editProduk'])
    ->name('produksi-shift.edit-produk');
Route::patch('/produksi-shift/produk/{product}', [\App\Http\Controllers\ShiftProductionController::class, 'updateProduk'])
    ->name('produksi-shift.update-produk');
Route::delete('/produksi-shift/produk/{product}', [\App\Http\Controllers\ShiftProductionController::class, 'destroyProduk'])
    ->name('produksi-shift.destroy-produk');
Route::patch('/produksi-shift/update-cycle-time/{product}', [\App\Http\Controllers\ShiftProductionController::class, 'updateCycleTime'])
        ->name('produksi-shift.update-cycle-time');    

    
}); 