<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik dari tabel materials
        $totalMaterial = Material::count();
        $totalStok = Material::sum('stok');
        $totalNilai = Material::selectRaw('SUM(stok * harga_satuan) as total')->value('total') ?? 0;
        $materialMenipis = Material::where('status', 'menipis')->count();
        $materialHabis = Material::where('status', 'habis')->count();

        // Data untuk Chart 1: Stok per Kategori Material
        $kategoriData = Material::selectRaw('kategori, SUM(stok) as total_stok')
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->orderByDesc('total_stok')
            ->take(7)
            ->get();

        // Data untuk Chart 2: Nilai Material per Kategori
        $nilaiKategori = Material::selectRaw('kategori, SUM(stok * COALESCE(harga_satuan, 0)) as total_nilai')
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->orderByDesc('total_nilai')
            ->take(7)
            ->get();

        // Data untuk Chart 3: Status Material (Doughnut)
        $statusData = [
            'tersedia' => Material::where('status', 'tersedia')->count(),
            'menipis' => $materialMenipis,
            'habis' => $materialHabis,
        ];

        // Data untuk Chart 4: Top 10 Material dengan Stok Terbanyak
        $topStok = Material::orderByDesc('stok')->take(10)->get();

        // Material terbaru ditambahkan
        $materialTerbaru = Material::latest()->take(5)->get();

        // Material stok menipis
        $stokMenipisList = Material::where('status', 'menipis')
            ->orderBy('stok')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalMaterial',
            'totalStok',
            'totalNilai',
            'materialMenipis',
            'materialHabis',
            'kategoriData',
            'nilaiKategori',
            'statusData',
            'topStok',
            'materialTerbaru',
            'stokMenipisList'
        ));
    }
}