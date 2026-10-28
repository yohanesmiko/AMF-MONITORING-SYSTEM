<?php

namespace App\Http\Controllers;

use App\Models\ProductRecipe;
use App\Models\RecipeDetail;
use App\Models\Material;
use App\Models\FinishedProduct;
use App\Models\StockMovement;
use App\Models\WipItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProduksiController extends Controller
{
    /**
     * Dashboard Produksi - Monitoring lengkap
     */
    public function index()
    {
        // 1. Material yang akan habis (warning)
        $materialWarning = Material::where('stok', '>', 0)
            ->get()
            ->map(function($m) {
                $avgHarian = $m->dailyUsages()
                    ->where('tanggal', '>=', now()->subDays(7))
                    ->avg('qty_penggunaan') ?? 0;
                
                $hariHabis = $avgHarian > 0 ? round($m->stok / $avgHarian) : null;
                
                return [
                    'material' => $m,
                    'avg_harian' => $avgHarian,
                    'hari_habis' => $hariHabis,
                    'level' => $hariHabis === null ? 'aman' : 
                             ($hariHabis <= 7 ? 'danger' : ($hariHabis <= 14 ? 'warning' : 'safe'))
                ];
            })
            ->sortBy('hari_habis');

        // 2. Produk yang bisa diproduksi dari stok saat ini
        $recipes = ProductRecipe::with(['details.material'])->get()->map(function($r) {
            return [
                'recipe' => $r,
                'maks_produksi' => $r->hitungMaksimumProduksi(),
                'material_pembatas' => $this->cariMaterialPembatas($r),
            ];
        });

        // 3. Statistik
        $stats = [
            'total_material' => Material::count(),
            'material_kritis' => $materialWarning->where('level', 'danger')->count(),
            'total_produk' => FinishedProduct::count(),
            'total_stok_produk' => FinishedProduct::sum('stok'),
            'wip_aktif' => WipItem::where('status', 'diproses')->count(),
        ];

        return view('produksi.index', compact(
            'materialWarning', 'recipes', 'stats'
        ));
    }

    /**
     * Cari material yang jadi pembatas produksi
     */
    private function cariMaterialPembatas(ProductRecipe $recipe)
    {
        $minRatio = PHP_INT_MAX;
        $pembatas = null;

        foreach ($recipe->details as $detail) {
            $material = $detail->material;
            $stokGram = $material->stok * 1000;
            $ratio = $stokGram / $detail->qty_dibutuhkan;
            
            if ($ratio < $minRatio) {
                $minRatio = $ratio;
                $pembatas = $material->nama_material;
            }
        }

        return $pembatas;
    }

    /**
     * Jalankan produksi - konversi material ke produk jadi
     * INI YANG MEMASTIKAN DATA AKURAT!
     */
    public function jalankanProduksi(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => 'required|exists:product_recipes,id',
            'qty_produksi' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $recipe = ProductRecipe::with('details.material')->findOrFail($validated['recipe_id']);
        $qtyProduksi = $validated['qty_produksi'];

        // VALIDASI 1: Cek apakah material mencukupi
        foreach ($recipe->details as $detail) {
            $material = $detail->material;
            $butuhGram = $detail->qty_dibutuhkan * $qtyProduksi;
            $stokGram = $material->stok * 1000; // kg ke gram

            if ($stokGram < $butuhGram) {
                return back()->with('error', 
                    "Material {$material->nama_material} tidak mencukupi! " .
                    "Butuh: " . number_format($butuhGram, 0) . "gr, " .
                    "Tersedia: " . number_format($stokGram, 0) . "gr"
                )->withInput();
            }
        }

        // TRANSAKSI DATABASE (Atomic - semua atau tidak sama sekali)
        DB::beginTransaction();
        try {
            $nomorTransaksi = 'PRD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // 1. Kurangi stok material
            foreach ($recipe->details as $detail) {
                $material = $detail->material;
                $butuhKg = ($detail->qty_dibutuhkan * $qtyProduksi) / 1000;

                $material->stok -= $butuhKg;
                
                // Update status material
                if ($material->stok <= 0) {
                    $material->status = 'habis';
                    $material->stok = 0;
                } elseif ($material->stok <= $material->stok_minimum) {
                    $material->status = 'menipis';
                }
                $material->save();

                // Catat di stock_movements
                StockMovement::create([
                    'nomor_transaksi' => $nomorTransaksi,
                    'tipe' => 'material_keluar',
                    'referensi_id' => $recipe->id,
                    'material_id' => $material->id,
                    'qty' => $butuhKg,
                    'satuan' => 'kg',
                    'keterangan' => "Produksi {$recipe->nama_produk} x{$qtyProduksi}",
                    'user_id' => auth()->id(),
                ]);
            }

            // 2. Tambah stok produk jadi (dengan yield rate)
            $yieldRate = $recipe->yield_rate / 100;
            $qtyProdukJadi = floor($qtyProduksi * $yieldRate);
            $produkRusak = $qtyProduksi - $qtyProdukJadi;

            $produk = FinishedProduct::firstOrCreate(
                ['nama_produk' => $recipe->nama_produk],
                [
                    'kode_produk' => 'FP-' . strtoupper(Str::random(8)),
                    'satuan' => 'unit',
                    'status' => 'tersedia',
                ]
            );

            $produk->stok += $qtyProdukJadi;
            $produk->save();

            // Catat produk jadi di stock_movements
            StockMovement::create([
                'nomor_transaksi' => $nomorTransaksi,
                'tipe' => 'produk_jadi',
                'referensi_id' => $recipe->id,
                'product_id' => $produk->id,
                'qty' => $qtyProdukJadi,
                'satuan' => 'unit',
                'keterangan' => "Hasil produksi (Yield: {$recipe->yield_rate}%)",
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('produksi.index')
                ->with('success', 
                    "Produksi berhasil! " .
                    "Input: {$qtyProduksi} unit, " .
                    "Output: {$qtyProdukJadi} unit jadi, " .
                    "Rusak: {$produkRusak} unit"
                );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal produksi: ' . $e->getMessage());
        }
    }

    /**
     * Kalkulasi detail per material
     */
    public function kalkulasiDetail(Request $request)
    {
        $materialId = $request->material_id;
        $material = Material::with(['dailyUsages'])->findOrFail($materialId);

        // Hitung rata-rata 7, 14, 30 hari
        $avg7 = $material->dailyUsages()
            ->where('tanggal', '>=', now()->subDays(7))
            ->avg('qty_penggunaan') ?? 0;
        
        $avg14 = $material->dailyUsages()
            ->where('tanggal', '>=', now()->subDays(14))
            ->avg('qty_penggunaan') ?? 0;
        
        $avg30 = $material->dailyUsages()
            ->where('tanggal', '>=', now()->subDays(30))
            ->avg('qty_penggunaan') ?? 0;

        // Estimasi hari habis
        $estimasi = [
            '7_hari' => $avg7 > 0 ? round($material->stok / $avg7) : null,
            '14_hari' => $avg14 > 0 ? round($material->stok / $avg14) : null,
            '30_hari' => $avg30 > 0 ? round($material->stok / $avg30) : null,
        ];

        // Rekomendasi restock
        $rekomendasi = null;
        if ($estimasi['7_hari'] && $estimasi['7_hari'] <= 7) {
            $kebutuhan7Hari = $avg7 * 7;
            $rekomendasi = max(0, $kebutuhan7Hari - $material->stok);
        }

        return response()->json([
            'material' => $material,
            'avg' => ['7' => $avg7, '14' => $avg14, '30' => $avg30],
            'estimasi_hari' => $estimasi,
            'rekomendasi_restock' => $rekomendasi,
        ]);
    }
}