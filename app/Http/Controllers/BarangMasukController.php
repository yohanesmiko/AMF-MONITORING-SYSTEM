<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('jenis', 'masuk')
            ->with(['inventory', 'supplier', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_transaksi', 'like', "%{$search}%");
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        $transactions = $query->latest()->paginate(10);

        // Statistik
        $totalTransaksi = Transaction::where('jenis', 'masuk')->count();
        $totalBarang = Transaction::where('jenis', 'masuk')->sum('jumlah');
        $totalNilai = Transaction::where('jenis', 'masuk')->sum(\DB::raw('jumlah * harga'));
        $transaksiHariIni = Transaction::where('jenis', 'masuk')
            ->whereDate('tanggal', today())
            ->count();

        return view('barang-masuk.index', compact(
            'transactions', 
            'totalTransaksi', 
            'totalBarang', 
            'totalNilai', 
            'transaksiHariIni'
        ));
    }

    public function create()
    {
        $inventories = Inventory::where('status', 'tersedia')->orWhere('status', 'habis')->get();
        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('barang-masuk.create', compact('inventories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $validated['nomor_transaksi'] = 'BM-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $validated['jenis'] = 'masuk';
        $validated['user_id'] = auth()->id();

        $transaction = Transaction::create($validated);

        // Update stok inventory
        $inventory = Inventory::find($validated['inventory_id']);
        $inventory->stok += $validated['jumlah'];
        $inventory->status = 'tersedia';
        $inventory->save();

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat!');
    }
}