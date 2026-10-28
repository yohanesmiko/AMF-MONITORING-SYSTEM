<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter stok menipis
        if ($request->filled('stok_menipis')) {
            $query->where('stok', '<=', 10);
        }

        $inventories = $query->latest()->paginate(10);
        $kategoris = Inventory::distinct()->pluck('kategori');

        return view('inventory.index', compact('inventories', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:inventories,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:tersedia,habis,rusak',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::slug($validated['nama_barang']) . '.' . $file->getClientOriginalExtension();
            $validated['gambar'] = $file->storeAs('inventories', $filename, 'public');
        }

        Inventory::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:inventories,kode_barang,' . $inventory->id,
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:tersedia,habis,rusak',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($inventory->gambar) {
                Storage::disk('public')->delete($inventory->gambar);
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . Str::slug($validated['nama_barang']) . '.' . $file->getClientOriginalExtension();
            $validated['gambar'] = $file->storeAs('inventories', $filename, 'public');
        }

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Barang berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        // Hapus gambar jika ada
        if ($inventory->gambar) {
            Storage::disk('public')->delete($inventory->gambar);
        }

        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        return back()->with('info', 'Fitur export Excel belum tersedia. Install package terlebih dahulu.');
    }

    /**
     * Export to PDF
     */
    public function exportPdf()
    {
        return back()->with('info', 'Fitur export PDF belum tersedia. Install package terlebih dahulu.');
    }
}