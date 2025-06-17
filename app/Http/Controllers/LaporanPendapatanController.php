<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use Illuminate\Http\Request;

class LaporanPendapatanController extends Controller
{
    public function index(Request $request)
    {
        $hari = $request->hari;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = Pendapatan::query();

        if ($hari && $bulan && $tahun) {
            $tanggal = "$tahun-$bulan-$hari";
            $query->whereDate('tanggal', $tanggal);
        } elseif ($bulan && $tahun) {
            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        } elseif ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $pendapatans = $query->get();

        return view('admin.laporan.pendapatan.index', compact('pendapatans'));
    }

    public function cetakpendapatan(Request $request)
    {
        $query = Pendapatan::query();

        // Terapkan filter jika tersedia
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $tanggal = "{$request->tahun}-{$request->bulan}-{$request->hari}";
            $query->whereDate('tanggal', $tanggal);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun)
                ->whereMonth('tanggal', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $pendapatans = $query->orderBy('tanggal')->get();

        return view('admin.laporan.pendapatan.cetak', compact('pendapatans'));
    }
}
