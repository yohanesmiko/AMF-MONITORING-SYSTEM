<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DailyUsage;
use Carbon\Carbon;

class WarningController extends Controller
{
    /**
     * Dashboard Warning - Semua material dengan status kritis
     */
    public function index()
    {
        $materials = Material::all()->map(function($m) {
            $avgHarian = $m->dailyUsages()
                ->where('tanggal', '>=', now()->subDays(7))
                ->avg('qty_penggunaan') ?? 0;

            $hariHabis = $avgHarian > 0 ? round($m->stok / $avgHarian) : null;
            
            $level = 'aman';
            if ($m->stok == 0) {
                $level = 'habis';
            } elseif ($hariHabis !== null && $hariHabis <= 3) {
                $level = 'kritis';
            } elseif ($hariHabis !== null && $hariHabis <= 7) {
                $level = 'urgent';
            } elseif ($hariHabis !== null && $hariHabis <= 14) {
                $level = 'warning';
            }

            return [
                'material' => $m,
                'avg_harian' => $avgHarian,
                'hari_habis' => $hariHabis,
                'level' => $level,
                'rekomendasi_qty' => $avgHarian > 0 ? round($avgHarian * 14 - $m->stok, 2) : 0,
            ];
        })->sortBy('hari_habis');

        $stats = [
            'habis' => $materials->where('level', 'habis')->count(),
            'kritis' => $materials->where('level', 'kritis')->count(),
            'urgent' => $materials->where('level', 'urgent')->count(),
            'warning' => $materials->where('level', 'warning')->count(),
            'aman' => $materials->where('level', 'aman')->count(),
        ];

        return view('warning.index', compact('materials', 'stats'));
    }

    /**
     * Catat penggunaan harian (WAJIB untuk akurasi)
     */
    public function catatPenggunaan(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty_penggunaan' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Cek duplikasi (1 material hanya 1x per hari)
        $existing = DailyUsage::where('material_id', $validated['material_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if ($existing) {
            $existing->qty_penggunaan += $validated['qty_penggunaan'];
            $existing->save();
        } else {
            DailyUsage::create([
                'material_id' => $validated['material_id'],
                'qty_penggunaan' => $validated['qty_penggunaan'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'],
                'user_id' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Penggunaan harian tercatat!');
    }
}