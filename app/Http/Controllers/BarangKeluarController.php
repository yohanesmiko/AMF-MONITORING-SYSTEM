<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('jenis', 'keluar')
            ->with(['inventory', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_transaksi', 'like', "%{$search}%");
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        $transactions = $query->latest()->paginate(10);

        // Statistik
        $totalTransaksi = Transaction::where('jenis', 'keluar')->count();
        $totalBarang = Transaction::where('jenis', 'keluar')->sum('jumlah');
        $totalNilai = Transaction::where('jenis', 'keluar')->sum(DB::raw('jumlah * harga'));
        $transaksiHariIni = Transaction::where('jenis', 'keluar')
            ->whereDate('tanggal', today())
            ->count();

        return view('barang-keluar.index', compact(
            'transactions', 
            'totalTransaksi', 
            'totalBarang', 
            'totalNilai', 
            'transaksiHariIni'
        ));
    }

    public function create()
    {
        $inventories = Inventory::where('stok', '>', 0)->get();
        return view('barang-keluar.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'jumlah' => 'required|integer|min:1',
            'tujuan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        // Ambil data inventory
        $inventory = Inventory::find($validated['inventory_id']);
        
        // Cek stok mencukupi
        if ($validated['jumlah'] > $inventory->stok) {
            return back()
                ->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $inventory->stok . ' ' . $inventory->satuan)
                ->withInput();
        }

        // Buat transaksi
        $transaction = Transaction::create([
            'nomor_transaksi' => 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'jenis' => 'keluar',
            'inventory_id' => $validated['inventory_id'],
            'jumlah' => $validated['jumlah'],
            'harga' => $inventory->harga_jual,
            'tujuan' => $validated['tujuan'],
            'keterangan' => $validated['keterangan'] ?? null,
            'tanggal' => $validated['tanggal'],
            'user_id' => auth()->id(),
        ]);

        // KURANGI STOK - INI BAGIAN PENTING!
        // Kurangi stok
$inventory->stok -= $validated['jumlah'];

// Auto update status
if ($inventory->stok == 0) {
    $inventory->status = 'habis';
} elseif ($inventory->stok <= $inventory->stok_minimum) {
    $inventory->status = 'menipis';
} else {
    $inventory->status = 'tersedia';
}
$inventory->save();

        // Update status berdasarkan stok
        if ($inventory->stok == 0) {
            $inventory->status = 'habis';
        } elseif ($inventory->stok <= 10) {
            // Stok menipis tapi masih tersedia
            $inventory->status = 'tersedia';
        }
        
        // Simpan perubahan stok
        $inventory->save();

        return redirect()->route('barang-keluar.index')
            ->with('success', 'Barang keluar berhasil dicatat! Stok ' . $inventory->nama_barang . ' berkurang ' . $validated['jumlah'] . ' ' . $inventory->satuan);
    }
}