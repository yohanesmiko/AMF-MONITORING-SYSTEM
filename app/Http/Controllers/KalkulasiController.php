<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DailyUsage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KalkulasiController extends Controller
{
    public function index()
    {
        $materials = Material::with(['dailyUsages' => function($q) {
            $q->where('tanggal', '>=', now()->subDays(30));
        }])->get();

        $kalkulasiData = [];
        foreach ($materials as $material) {
            $avg7Hari = $material->dailyUsages()
                ->where('tanggal', '>=', now()->subDays(7))
                ->avg('qty_penggunaan') ?? 0;
            
            $avg30Hari = $material->dailyUsages()
                ->where('tanggal', '>=', now()->subDays(30))
                ->avg('qty_penggunaan') ?? 0;

            $estimasiHabis7 = $avg7Hari > 0 ? round($material->stok / $avg7Hari) : null;
            $estimasiHabis30 = $avg30Hari > 0 ? round($material->stok / $avg30Hari) : null;

            $kalkulasiData[] = [
                'material' => $material,
                'avg_7_hari' => $avg7Hari,
                'avg_30_hari' => $avg30Hari,
                'estimasi_7_hari' => $estimasiHabis7,
                'estimasi_30_hari' => $estimasiHabis30,
                'butuh_restock' => $estimasiHabis7 !== null && $estimasiHabis7 <= 7,
            ];
        }

        $totalMaterial = Material::count();
        $butuhRestock = collect($kalkulasiData)->where('butuh_restock', true)->count();

        return view('kalkulasi.index', compact('kalkulasiData', 'totalMaterial', 'butuhRestock'));
    }

    public function hitung(Request $request)
    {
        $validated = $request->validate([
            'stok_sekarang' => 'required|numeric|min:0',
            'penggunaan_harian' => 'required|numeric|min:0.01',
            'hari_kebutuhan' => 'required|integer|min:1',
        ]);

        $kebutuhanTotal = $validated['penggunaan_harian'] * $validated['hari_kebutuhan'];
        $kekurangan = max(0, $kebutuhanTotal - $validated['stok_sekarang']);
        $hariHabis = $validated['penggunaan_harian'] > 0 
            ? round($validated['stok_sekarang'] / $validated['penggunaan_harian']) 
            : 0;

        return view('kalkulasi.hasil', compact('validated', 'kebutuhanTotal', 'kekurangan', 'hariHabis'));
    }

    public function catatPenggunaan(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty_penggunaan' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        DailyUsage::create([
            'material_id' => $validated['material_id'],
            'qty_penggunaan' => $validated['qty_penggunaan'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Penggunaan harian berhasil dicatat!');
    }
}