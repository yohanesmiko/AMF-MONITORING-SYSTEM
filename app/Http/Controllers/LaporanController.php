<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalDari = $request->tanggal_dari ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggalSampai = $request->tanggal_sampai ?? Carbon::now()->format('Y-m-d');

        $transactions = Transaction::with(['inventory', 'supplier', 'user'])
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->latest()
            ->get();

        $masuk = $transactions->where('jenis', 'masuk');
        $keluar = $transactions->where('jenis', 'keluar');

        $stats = [
            'total_transaksi' => $transactions->count(),
            'barang_masuk' => $masuk->count(),
            'barang_keluar' => $keluar->count(),
            'total_masuk' => $masuk->sum('jumlah'),
            'total_keluar' => $keluar->sum('jumlah'),
            'nilai_masuk' => $masuk->sum(fn($t) => $t->jumlah * ($t->harga ?? 0)),
            'nilai_keluar' => $keluar->sum(fn($t) => $t->jumlah * ($t->harga ?? 0)),
        ];

        return view('laporan.index', compact('transactions', 'stats', 'tanggalDari', 'tanggalSampai'));
    }
}