<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class POController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['material', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nomor_po', 'like', "%{$request->search}%");
        }

        $purchaseOrders = $query->latest()->paginate(10);

        $stats = [
            'total_po' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', 'draft')->count(),
            'terkirim' => PurchaseOrder::where('status', 'terkirim')->count(),
            'selesai' => PurchaseOrder::where('status', 'selesai')->count(),
            'total_nilai' => PurchaseOrder::where('status', '!=', 'dibatalkan')->sum('total_harga'),
        ];

        return view('po.index', compact('purchaseOrders', 'stats'));
    }

    public function create()
    {
        $materials = Material::where('status', '!=', 'habis')->get();
        return view('po.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'tanggal_po' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after:tanggal_po',
            'keterangan' => 'nullable|string',
        ]);

        $material = Material::find($validated['material_id']);
        $totalHarga = $validated['qty'] * $validated['harga_satuan'];

        PurchaseOrder::create([
            'nomor_po' => 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'material_id' => $validated['material_id'],
            'qty' => $validated['qty'],
            'harga_satuan' => $validated['harga_satuan'],
            'total_harga' => $totalHarga,
            'supplier' => $validated['supplier'],
            'tanggal_po' => $validated['tanggal_po'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'] ?? null,
            'status' => 'draft',
            'keterangan' => $validated['keterangan'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('po.index')
            ->with('success', 'Purchase Order berhasil dibuat!');
    }

    public function show(PurchaseOrder $po)
    {
        return view('po.show', compact('po'));
    }

    public function edit(PurchaseOrder $po)
    {
        $materials = Material::where('status', '!=', 'habis')->get();
        return view('po.edit', compact('po', 'materials'));
    }

    public function update(Request $request, PurchaseOrder $po)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
            'tanggal_po' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $totalHarga = $validated['qty'] * $validated['harga_satuan'];

        $po->update([
            'material_id' => $validated['material_id'],
            'qty' => $validated['qty'],
            'harga_satuan' => $validated['harga_satuan'],
            'total_harga' => $totalHarga,
            'supplier' => $validated['supplier'],
            'tanggal_po' => $validated['tanggal_po'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'] ?? null,
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('po.index')
            ->with('success', 'Purchase Order berhasil diupdate!');
    }

    public function destroy(PurchaseOrder $po)
    {
        $po->update(['status' => 'dibatalkan']);
        return redirect()->route('po.index')
            ->with('success', 'Purchase Order dibatalkan!');
    }

    public function updateStatus(Request $request, PurchaseOrder $po)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,terkirim,diproses,selesai,dibatalkan',
        ]);

        $po->update(['status' => $validated['status']]);

        // Jika PO selesai, tambah stok material
        if ($validated['status'] === 'selesai') {
            $material = $po->material;
            $material->stok += $po->qty;
            
            if ($material->stok > $material->stok_minimum) {
                $material->status = 'tersedia';
            }
            $material->save();
        }

        return back()->with('success', 'Status PO berhasil diupdate!');
    }
}