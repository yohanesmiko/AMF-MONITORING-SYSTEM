<?php

namespace App\Http\Controllers;

use App\Models\ProductionProduct;
use App\Models\ProductionRun;
use App\Models\ProductionRunMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShiftProductionController extends Controller
{
    /**
     * Dashboard Produksi - Pilih produk & lihat status shift hari ini
     */
    public function index()
    {
        $products = ProductionProduct::with('materials.material')->get();
        $today = now()->toDateString();
        
        // Status 3 shift hari ini untuk produk pertama (default)
        $defaultProduct = $products->first();
        $shiftsToday = [];
        
        if ($defaultProduct) {
            foreach (['1', '2', '3'] as $shift) {
                $shiftsToday[$shift] = ProductionRun::where('production_product_id', $defaultProduct->id)
                    ->where('shift', $shift)
                    ->whereDate('tanggal', $today)
                    ->with(['runMaterials.material', 'operator'])
                    ->first();
            }
        }

        return view('produksi-shift.index', compact('products', 'shiftsToday', 'defaultProduct', 'today'));
    }

    /**
     * Form input produksi per shift
     */
    public function create(Request $request)
    {
        $productId = $request->product_id;
        $shift = $request->shift;
        $tanggal = $request->tanggal ?? now()->toDateString();
        
        $product = ProductionProduct::with('materials.material')->findOrFail($productId);
        
        // Cek apakah sudah ada run untuk shift ini
        $existingRun = ProductionRun::where('production_product_id', $productId)
            ->where('shift', $shift)
            ->whereDate('tanggal', $tanggal)
            ->first();

        $operators = \App\Models\User::where('role', 'staff')->get();

        return view('produksi-shift.create', compact(
            'product', 'shift', 'tanggal', 'existingRun', 'operators'
        ));
    }

    /**
     * Simpan production run (AKURAT dengan transaction)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_product_id' => 'required|exists:production_products,id',
            'shift' => 'required|in:1,2,3',
            'tanggal' => 'required|date',
            'output_aktual' => 'required|numeric|min:0',
            'downtime_menit' => 'required|numeric|min:0',
            'material_aktual' => 'required|array',
            'material_aktual.*' => 'required|numeric|min:0',
            'operator_id' => 'nullable|exists:users,id',
            'catatan' => 'nullable|string',
        ]);

        $product = ProductionProduct::with('materials')->findOrFail($validated['production_product_id']);
        $outputAktual = $validated['output_aktual'];
        $downtime = $validated['downtime_menit'];

        DB::beginTransaction();
        try {
            // 1. Buat Production Run
            $run = ProductionRun::create([
                'nomor_run' => 'RUN-' . date('Ymd') . '-S' . $validated['shift'] . '-' . strtoupper(Str::random(4)),
                'production_product_id' => $validated['production_product_id'],
                'shift' => $validated['shift'],
                'tanggal' => $validated['tanggal'],
                'output_aktual' => $outputAktual,
                'downtime_menit' => $downtime,
                'operator_id' => $validated['operator_id'] ?? auth()->id(),
                'catatan' => $validated['catatan'],
            ]);

            // 2. Hitung ulang metrik
            $run->recalculate();

            // 3. Simpan detail material & kurangi stok
            $totalMaterialAktual = 0;
            $totalMaterialStandar = 0;

            foreach ($product->materials as $productMaterial) {
                $material = $productMaterial->material;
                $qtyStandar = $outputAktual * $productMaterial->qty_per_unit;
                $qtyAktual = $validated['material_aktual'][$productMaterial->material_id] ?? $qtyStandar;
                $variance = $qtyAktual - $qtyStandar;

                ProductionRunMaterial::create([
                    'production_run_id' => $run->id,
                    'material_id' => $material->id,
                    'qty_standar' => $qtyStandar,
                    'qty_aktual' => $qtyAktual,
                    'variance' => $variance,
                ]);

                $totalMaterialStandar += $qtyStandar;
                $totalMaterialAktual += $qtyAktual;

                // 4. KURANGI STOK MATERIAL (AKURAT!)
                $qtyAktualKg = $qtyAktual / 1000; // gram ke kg
                $material->stok = max(0, $material->stok - $qtyAktualKg);
                
                if ($material->stok <= 0) {
                    $material->status = 'habis';
                } elseif ($material->stok <= $material->stok_minimum) {
                    $material->status = 'menipis';
                }
                $material->save();
            }

            // 5. Update total di production run
            $run->update([
                'material_standar_total' => $totalMaterialStandar,
                'material_aktual_total' => $totalMaterialAktual,
                'variance_material' => $totalMaterialAktual - $totalMaterialStandar,
            ]);

            DB::commit();

            return redirect()->route('produksi-shift.laporan', [
                'product_id' => $product->id,
                'tanggal' => $validated['tanggal']
            ])->with('success', "Shift {$validated['shift']} berhasil dicatat! Output: {$outputAktual} unit");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * LAPORAN HARIAN - Rekap 3 Shift (INTI SISTEM)
     */
    public function laporan(Request $request)
    {
        $productId = $request->product_id;
        $tanggal = $request->tanggal ?? now()->toDateString();
        
        $product = ProductionProduct::with('materials.material')->findOrFail($productId);
        
        // Ambil data 3 shift
        $shifts = [];
        foreach (['1', '2', '3'] as $shift) {
            $shifts[$shift] = ProductionRun::where('production_product_id', $productId)
                ->where('shift', $shift)
                ->whereDate('tanggal', $tanggal)
                ->with(['runMaterials.material', 'operator'])
                ->first();
        }

        // Hitung total & rata-rata
        $totalOutput = collect($shifts)->sum('output_aktual');
        $totalDowntime = collect($shifts)->sum('downtime_menit');
        $totalMaterialStandar = collect($shifts)->sum('material_standar_total');
        $totalMaterialAktual = collect($shifts)->sum('material_aktual_total');
        $totalVariance = $totalMaterialAktual - $totalMaterialStandar;
        
        $targetTotal = $product->hitungTargetOutput(0) * 3; // 3 shift
        $efisiensiTotal = $targetTotal > 0 ? ($totalOutput / $targetTotal) * 100 : 0;
        
        $avgCycleTime = $totalOutput > 0 
            ? (($product->waktu_shift_menit * 3 * 60) - ($totalDowntime * 60)) / $totalOutput 
            : 0;

        // Data untuk chart
        $shiftLabels = ['Shift 1', 'Shift 2', 'Shift 3'];
        $shiftOutputs = [
            $shifts['1']->output_aktual ?? 0,
            $shifts['2']->output_aktual ?? 0,
            $shifts['3']->output_aktual ?? 0,
        ];
        $shiftEfisiensi = [
            $shifts['1']->efisiensi ?? 0,
            $shifts['2']->efisiensi ?? 0,
            $shifts['3']->efisiensi ?? 0,
        ];

        return view('produksi-shift.laporan', compact(
            'product', 'tanggal', 'shifts', 'totalOutput', 'totalDowntime',
            'totalMaterialStandar', 'totalMaterialAktual', 'totalVariance',
            'targetTotal', 'efisiensiTotal', 'avgCycleTime',
            'shiftLabels', 'shiftOutputs', 'shiftEfisiensi'
        ));
    }

    /**
     * Setup produk (untuk admin)
     */
    public function setupProduk()
    {
        $products = ProductionProduct::with('materials.material')->get();
        $materials = Material::all();
        return view('produksi-shift.setup', compact('products', 'materials'));
    }

    public function storeProduk(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|unique:production_products,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'cycle_time_standar' => 'required|numeric|min:0.01',
            'waktu_shift_menit' => 'required|numeric|min:1',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.qty_per_unit' => 'required|numeric|min:0.01',
        ]);

        $product = ProductionProduct::create([
            'kode_produk' => $validated['kode_produk'],
            'nama_produk' => $validated['nama_produk'],
            'cycle_time_standar' => $validated['cycle_time_standar'],
            'waktu_shift_menit' => $validated['waktu_shift_menit'],
            'target_per_shift' => floor(($validated['waktu_shift_menit'] * 60) / $validated['cycle_time_standar']),
        ]);

        foreach ($validated['materials'] as $mat) {
            $product->materials()->create([
                'material_id' => $mat['material_id'],
                'qty_per_unit' => $mat['qty_per_unit'],
                'satuan' => 'gram',
            ]);
        }

        return back()->with('success', 'Produk berhasil dibuat!');
    }
}