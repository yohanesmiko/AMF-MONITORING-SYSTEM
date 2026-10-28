<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Material::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_material', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $materials = $query->latest()->paginate(12);

        // Statistik
        $totalMaterial = Material::count();
        $totalStok = Material::sum('stok');
        $materialMenipis = Material::where('status', 'menipis')->count();
        $materialHabis = Material::where('status', 'habis')->count();

        return view('material.index', compact(
            'materials', 'totalMaterial', 'totalStok',
            'materialMenipis', 'materialHabis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('material.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_material' => 'required|unique:materials,kode_material',
            'nama_material' => 'required|string|max:255',
            'jenis' => 'required|in:ct,masterbatch,lainnya',
            'kategori' => 'nullable|string|max:100',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Auto set status berdasarkan stok
        if ($validated['stok'] == 0) {
            $validated['status'] = 'habis';
        } elseif ($validated['stok'] <= $validated['stok_minimum']) {
            $validated['status'] = 'menipis';
        } else {
            $validated['status'] = 'tersedia';
        }

        Material::create($validated);

        return redirect()->route('material.index')
            ->with('success', 'Material berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return view('material.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return view('material.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'kode_material' => 'required|unique:materials,kode_material,' . $material->id,
            'nama_material' => 'required|string|max:255',
            'jenis' => 'required|in:ct,masterbatch,lainnya',
            'kategori' => 'nullable|string|max:100',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Auto set status berdasarkan stok
        if ($validated['stok'] == 0) {
            $validated['status'] = 'habis';
        } elseif ($validated['stok'] <= $validated['stok_minimum']) {
            $validated['status'] = 'menipis';
        } else {
            $validated['status'] = 'tersedia';
        }

        $material->update($validated);

        return redirect()->route('material.index')
            ->with('success', 'Material berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('material.index')
            ->with('success', 'Material berhasil dihapus!');
    }
}