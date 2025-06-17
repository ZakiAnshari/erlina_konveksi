<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $hari = $request->hari;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = Pengeluaran::query();

        if ($hari && $bulan && $tahun) {
            $tanggal = "$tahun-$bulan-$hari";
            $query->whereDate('tanggal', $tanggal);
        } elseif ($bulan && $tahun) {
            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        } elseif ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $pengeluarans = $query->get();

        return view('admin.laporan.pengeluaran.index', compact('pengeluarans'));
    }

    public function cetakpengeluaran(Request $request)
    {
        $query = Pengeluaran::query();

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

        $pengeluarans = $query->orderBy('tanggal')->get();

        return view('admin.laporan.pengeluaran.cetak', compact('pengeluarans'));
    }
}
