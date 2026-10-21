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
        $stokMenipis = Inventory::where('stok', '<=', 10)->where('status', 'tersedia')->count();

        // Ambil data terbaru
        $barangTerbaru = Inventory::latest()->take(5)->get();
        
        // Stok menipis
        $stokMenipisList = Inventory::where('stok', '<=', 10)
            ->where('status', 'tersedia')
            ->orderBy('stok')
            ->take(5)
            ->get();

        // Data untuk Chart 1: Stok per Kategori (Pie Chart)
        $kategoriData = Inventory::selectRaw('kategori, SUM(stok) as total_stok')
            ->groupBy('kategori')
            ->orderByDesc('total_stok')
            ->take(7)
            ->get();

        // Data untuk Chart 2: Nilai Inventory per Kategori (Bar Chart)
        $nilaiKategori = Inventory::selectRaw('kategori, SUM(stok * harga_beli) as total_nilai')
            ->groupBy('kategori')
            ->orderByDesc('total_nilai')
            ->take(7)
            ->get();

        // Data untuk Chart 3: Status Barang (Doughnut Chart)
        $statusData = [
            'tersedia' => Inventory::where('status', 'tersedia')->count(),
            'habis' => $barangHabis,
            'rusak' => $barangRusak,
        ];

        // Data untuk Chart 4: Top 10 Barang dengan Stok Terbanyak
        $topStok = Inventory::orderByDesc('stok')->take(10)->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalStok',
            'totalNilai',
            'barangHabis',
            'barangRusak',
            'stokMenipis',
            'barangTerbaru',
            'stokMenipisList',
            'kategoriData',
            'nilaiKategori',
            'statusData',
            'topStok'
        ));
    }
}