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
});