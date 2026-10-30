<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialOut;
use Illuminate\Http\Request;

class OperatorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();
        $shiftUser = $user->shift ?? '1';

        $stats = [
            'material_out_hari_ini' => MaterialOut::whereDate('tanggal', $today)
                ->where('shift', $shiftUser)->sum('qty_out'),
            'total_transaksi' => MaterialOut::whereDate('tanggal', $today)->count(),
            'material_menipis' => Material::where('status', 'menipis')->count(),
            'material_habis' => Material::where('status', 'habis')->count(),
        ];

        $riwayatOut = MaterialOut::whereDate('tanggal', $today)
            ->where('shift', $shiftUser)
            ->with('material')
            ->latest()->take(5)->get();

        $materialPerhatian = Material::whereIn('status', ['menipis', 'habis'])
            ->orderBy('stok')->take(5)->get();

        return view('operator.dashboard', compact(
            'stats', 'shiftUser', 'riwayatOut', 'materialPerhatian'
        ));
    }
}