<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::with('user');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Pinned notes di atas
        $notes = $query->orderByDesc('is_pinned')->latest()->paginate(12);

        return view('catatan.index', compact('notes'));
    }

    public function create()
    {
        return view('catatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'required|in:umum,penting,reminder,laporan',
            'warna' => 'required|in:primary,success,warning,danger,info',
            'is_pinned' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_pinned'] = $request->has('is_pinned');

        Note::create($validated);

        return redirect()->route('catatan.index')
            ->with('success', 'Catatan berhasil ditambahkan!');
    }

    public function edit(Note $catatan)
    {
        return view('catatan.edit', compact('catatan'));
    }

    public function update(Request $request, Note $catatan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'required|in:umum,penting,reminder,laporan',
            'warna' => 'required|in:primary,success,warning,danger,info',
            'is_pinned' => 'nullable|boolean',
        ]);

        $validated['is_pinned'] = $request->has('is_pinned');
        $catatan->update($validated);

        return redirect()->route('catatan.index')
            ->with('success', 'Catatan berhasil diupdate!');
    }

    public function destroy(Note $catatan)
    {
        $catatan->delete();
        return redirect()->route('catatan.index')
            ->with('success', 'Catatan berhasil dihapus!');
    }
}