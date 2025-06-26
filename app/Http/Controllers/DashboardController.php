<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Pendapatan;
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

        return view('admin.dashboard.index', compact(
            'totalPendapatan',
            'totalPengeluaran',
            'totalHutang',
            'saldo'
        ));
    }
}
