<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('kode_supplier', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(12);

        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier,' . $supplier->id,
            'nama_supplier' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diupdate!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus!');
    }
}