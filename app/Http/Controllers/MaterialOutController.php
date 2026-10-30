<?php

namespace App\Http\Controllers;

use App\Models\MaterialOut;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MaterialOutController extends Controller
{
    /**
     * Dashboard Operator - Form Input Material Out
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();
        
        // Filter
        $query = MaterialOut::with(['material', 'user']);
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', $today);
        }
        
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_out', 'like', "%{$search}%")
                  ->orWhere('nama_produk', 'like', "%{$search}%")
                  ->orWhere('nama_operator', 'like', "%{$search}%")
                  ->orWhere('nama_mesin', 'like', "%{$search}%");
            });
        }
        
        $materialOuts = $query->latest()->paginate(15);
        
        // Statistik hari ini
        $stats = [
            'total_out' => MaterialOut::whereDate('tanggal', $today)->sum('qty_out'),
            'total_transaksi' => MaterialOut::whereDate('tanggal', $today)->count(),
            'shift_ini' => MaterialOut::whereDate('tanggal', $today)
                ->where('shift', $user->shift ?? '1')->sum('qty_out'),
        ];
        
        // Material tersedia untuk dropdown
        $materials = Material::where('status', '!=', 'habis')
            ->where('stok', '>', 0)
            ->orderBy('nama_material')
            ->get();
        
        // Daftar operator
        $operators = User::where('role', 'operator')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('material-out.index', compact(
            'materialOuts', 'stats', 'materials', 'operators', 'today'
        ));
    }

    /**
     * Simpan Material Out
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam_out' => 'required',
            'shift' => 'required|in:1,2,3',
            'nama_operator' => 'required|string|max:255',
            'nama_produk' => 'required|string|max:255',
            'nama_mesin' => 'nullable|string|max:255',
            'material_id' => 'required|exists:materials,id',
            'jenis_material' => 'required|string|max:100',
            'warna' => 'nullable|string|max:100',
            'qty_out' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        $material = Material::findOrFail($validated['material_id']);

        // Cek stok mencukupi
        if ($validated['qty_out'] > $material->stok) {
            return back()->with('error', 
                "Stok {$material->nama_material} tidak mencukupi! " .
                "Tersedia: {$material->stok} {$material->satuan}, " .
                "Diminta: {$validated['qty_out']} kg"
            )->withInput();
        }

        DB::beginTransaction();
        try {
            // Buat nomor out unik
            $nomorOut = 'OUT-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Simpan data material out
            MaterialOut::create([
                'nomor_out' => $nomorOut,
                'tanggal' => $validated['tanggal'],
                'jam_out' => $validated['jam_out'],
                'shift' => $validated['shift'],
                'nama_operator' => $validated['nama_operator'],
                'nama_produk' => $validated['nama_produk'],
                'nama_mesin' => $validated['nama_mesin'],
                'material_id' => $validated['material_id'],
                'jenis_material' => $validated['jenis_material'],
                'warna' => $validated['warna'],
                'qty_out' => $validated['qty_out'],
                'keterangan' => $validated['keterangan'],
                'user_id' => auth()->id(),
            ]);

            // Kurangi stok material
            $material->stok -= $validated['qty_out'];
            
            // Auto update status material
            if ($material->stok == 0) {
                $material->status = 'habis';
            } elseif ($material->stok <= $material->stok_minimum) {
                $material->status = 'menipis';
            } else {
                $material->status = 'tersedia';
            }
            $material->save();

            DB::commit();

            return redirect()->route('material-out.index')
                ->with('success', "✅ Material Out berhasil dicatat! Nomor: {$nomorOut}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus Material Out
     */
    public function destroy(MaterialOut $materialOut)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok material
            $material = $materialOut->material;
            $material->stok += $materialOut->qty_out;
            
            if ($material->stok > $material->stok_minimum) {
                $material->status = 'tersedia';
            } elseif ($material->stok > 0) {
                $material->status = 'menipis';
            }
            $material->save();

            $materialOut->delete();
            DB::commit();

            return back()->with('success', 'Data Material Out berhasil dihapus & stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Get info material via AJAX (untuk form)
     */
    public function getMaterialInfo($id)
    {
        $material = Material::findOrFail($id);
        return response()->json([
            'nama' => $material->nama_material,
            'jenis' => $material->jenis,
            'stok' => $material->stok,
            'satuan' => $material->satuan,
        ]);
    }
}