<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;

// Route public
Route::get('/', function () {
    return redirect()->route('login');
});

// Route yang butuh login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Inventory routes (akan dibuat di Bagian 2)
    Route::resource('inventory', InventoryController::class);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//HAPUS BARIS INI:require __DIR__.'/auth.php';

// Authentication routes (manual)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::get('/inventory/export/excel', [InventoryController::class, 'exportExcel'])->name('inventory.export.excel');
    Route::get('/inventory/export/pdf', [InventoryController::class, 'exportPdf'])->name('inventory.export.pdf');
    
    // Barang Masuk
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barang-masuk.create');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
    
    // Barang Keluar
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
    Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
    Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
    
    // Supplier
    Route::resource('supplier', SupplierController::class);
    
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    
    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::patch('/pengaturan/profile', [PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::patch('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Barang Masuk
Route::get('/barang-masuk', [\App\Http\Controllers\BarangMasukController::class, 'index'])->name('barang-masuk.index');
Route::get('/barang-masuk/create', [\App\Http\Controllers\BarangMasukController::class, 'create'])->name('barang-masuk.create');
Route::post('/barang-masuk', [\App\Http\Controllers\BarangMasukController::class, 'store'])->name('barang-masuk.store');

// Barang Keluar
Route::get('/barang-keluar', [\App\Http\Controllers\BarangKeluarController::class, 'index'])->name('barang-keluar.index');
Route::get('/barang-keluar/create', [\App\Http\Controllers\BarangKeluarController::class, 'create'])->name('barang-keluar.create');
Route::post('/barang-keluar', [\App\Http\Controllers\BarangKeluarController::class, 'store'])->name('barang-keluar.store');

// Stock Material
Route::resource('material', \App\Http\Controllers\MaterialController::class);

// Purchase Order
Route::resource('po', \App\Http\Controllers\POController::class);
Route::post('/po/{po}/update-status', [\App\Http\Controllers\POController::class, 'updateStatus'])->name('po.update-status');

// WIP (Work In Progress)
Route::resource('wip', \App\Http\Controllers\WipController::class);
Route::post('/wip/{wip}/update-status', [\App\Http\Controllers\WipController::class, 'updateStatus'])->name('wip.update-status');

// Catatan
Route::resource('catatan', \App\Http\Controllers\CatatanController::class);

// Kalkulasi Kebutuhan
Route::get('/kalkulasi', [\App\Http\Controllers\KalkulasiController::class, 'index'])->name('kalkulasi.index');
Route::post('/kalkulasi/hitung', [\App\Http\Controllers\KalkulasiController::class, 'hitung'])->name('kalkulasi.hitung');
Route::post('/kalkulasi/catat-penggunaan', [\App\Http\Controllers\KalkulasiController::class, 'catatPenggunaan'])->name('kalkulasi.catat');

// Produksi & Monitoring
Route::get('/produksi', [\App\Http\Controllers\ProduksiController::class, 'index'])->name('produksi.index');
Route::post('/produksi/jalankan', [\App\Http\Controllers\ProduksiController::class, 'jalankanProduksi'])->name('produksi.jalankan');
Route::post('/produksi/kalkulasi', [\App\Http\Controllers\ProduksiController::class, 'kalkulasiDetail'])->name('produksi.kalkulasi');

// Warning System
Route::get('/warning', [\App\Http\Controllers\WarningController::class, 'index'])->name('warning.index');
Route::post('/warning/catat', [\App\Http\Controllers\WarningController::class, 'catatPenggunaan'])->name('warning.catat');

// Produksi Shift
Route::get('/produksi-shift', [\App\Http\Controllers\ShiftProductionController::class, 'index'])->name('produksi-shift.index');
Route::get('/produksi-shift/create', [\App\Http\Controllers\ShiftProductionController::class, 'create'])->name('produksi-shift.create');
Route::post('/produksi-shift', [\App\Http\Controllers\ShiftProductionController::class, 'store'])->name('produksi-shift.store');
Route::get('/produksi-shift/laporan', [\App\Http\Controllers\ShiftProductionController::class, 'laporan'])->name('produksi-shift.laporan');
Route::get('/produksi-shift/setup', [\App\Http\Controllers\ShiftProductionController::class, 'setupProduk'])->name('produksi-shift.setup');
Route::post('/produksi-shift/setup', [\App\Http\Controllers\ShiftProductionController::class, 'storeProduk'])->name('produksi-shift.store-produk');

// Ganti route dashboard menjadi inventory (root)
Route::get('/', function () {
    return redirect()->route('inventory');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard/Inventory (nama route: inventory)
    Route::get('/inventory', [\App\Http\Controllers\DashboardController::class, 'index'])->name('inventory');
    
    // ... route lainnya tetap sama
});
});