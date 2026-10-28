<?php

namespace App\Http\Controllers;

use App\Models\WipItem;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WipItem::with(['material', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $wipItems = $query->latest()->paginate(10);

        $stats = [
            'total_wip' => WipItem::count(),
            'antrian' => WipItem::where('status', 'antrian')->count(),
            'diproses' => WipItem::where('status', 'diproses')->count(),
            'selesai' => WipItem::where('status', 'selesai')->count(),
        ];

        return view('wip.index', compact('wipItems', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::where('stok', '>', 0)->get();
        return view('wip.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty_input' => 'required|numeric|min:0.01',
            'mesin' => 'nullable|string|max:100',
            'operator' => 'nullable|string|max:100',
            'tanggal_mulai' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $material = Material::find($validated['material_id']);

        if ($validated['qty_input'] > $material->stok) {
            return back()->with('error', 'Stok material tidak mencukupi!')->withInput();
        }

        WipItem::create([
            'nomor_wip' => 'WIP-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'material_id' => $validated['material_id'],
            'qty_input' => $validated['qty_input'],
            'qty_output' => 0,
            'qty_proses' => 0,
            'mesin' => $validated['mesin'],
            'operator' => $validated['operator'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'status' => 'antrian',
            'keterangan' => $validated['keterangan'],
            'user_id' => auth()->id(),
        ]);

        // Kurangi stok material
        $material->stok -= $validated['qty_input'];

        // Auto update status material
        if ($material->stok == 0) {
            $material->status = 'habis';
        } elseif ($material->stok <= $material->stok_minimum) {
            $material->status = 'menipis';
        } else {
            $material->status = 'tersedia';
        }
        $material->save();

        return redirect()->route('wip.index')
            ->with('success', 'WIP berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WipItem $wip)
    {
        return view('wip.show', compact('wip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WipItem $wip)
    {
        $materials = Material::where('stok', '>', 0)->get();
        return view('wip.edit', compact('wip', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WipItem $wip)
    {
        $validated = $request->validate([
            'qty_output' => 'nullable|numeric|min:0',
            'qty_proses' => 'nullable|numeric|min:0',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $wip->update($validated);

        return redirect()->route('wip.index')
            ->with('success', 'WIP berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WipItem $wip)
    {
        // Kembalikan stok ke material
        $material = $wip->material;
        $material->stok += $wip->qty_input - $wip->qty_output;

        // Auto update status material
        if ($material->stok == 0) {
            $material->status = 'habis';
        } elseif ($material->stok <= $material->stok_minimum) {
            $material->status = 'menipis';
        } else {
            $material->status = 'tersedia';
        }
        $material->save();

        $wip->delete();

        return redirect()->route('wip.index')
            ->with('success', 'WIP berhasil dihapus!');
    }

    /**
     * Update status WIP
     */
    public function updateStatus(Request $request, WipItem $wip)
    {
        $validated = $request->validate([
            'status' => 'required|in:antrian,diproses,selesai,dibatalkan',
        ]);

        $wip->update(['status' => $validated['status']]);

        // Jika selesai, update tanggal selesai
        if ($validated['status'] === 'selesai') {
            $wip->update(['tanggal_selesai' => now()]);
        }

        return back()->with('success', 'Status WIP berhasil diupdate!');
    }
}