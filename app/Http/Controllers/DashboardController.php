<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Pendapatan;
use App\Models\ActivityLog;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendapatan = Pendapatan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalHutang = Hutang::sum('jumlah');
        $saldo = $totalPendapatan - $totalPengeluaran - $totalHutang;

        // Ambil data log terbaru (limit 10 atau semua dengan paginate)
        $logs = ActivityLog::with('user')->latest()->paginate(10);

        return view('admin.dashboard.index', compact(
            'totalPendapatan',
            'totalPengeluaran',
            'totalHutang',
            'saldo',
            'logs' // kirim ke view
        ));
    }
}
