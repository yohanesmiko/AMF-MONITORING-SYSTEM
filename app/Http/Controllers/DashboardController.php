<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung statistik
        $totalBarang = Inventory::count();
        $totalStok = Inventory::sum('stok');
        $totalNilai = Inventory::selectRaw('SUM(stok * harga_beli) as total')->value('total') ?? 0;
        $barangHabis = Inventory::where('status', 'habis')->count();
        $barangRusak = Inventory::where('status', 'rusak')->count();
        $stokMenipis = Inventory::where('stok', '<=', 10)->count();

        // Ambil data terbaru
        $barangTerbaru = Inventory::latest()->take(5)->get();
        
        // Stok menipis
        $stokMenipisList = Inventory::where('stok', '<=', 10)
            ->orderBy('stok')
            ->take(5)
            ->get();

        // Kategori terbanyak
        $kategori = Inventory::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalStok',
            'totalNilai',
            'barangHabis',
            'barangRusak',
            'stokMenipis',
            'barangTerbaru',
            'stokMenipisList',
            'kategori'
        ));
    }
}