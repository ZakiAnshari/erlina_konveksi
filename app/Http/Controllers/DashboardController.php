<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $fasilitasCount = Fasilitas::count();
        // $kriteriaCount = Kriteria::count();
        // $userCount = User::count();
        // $rusakberatCount = Fasilitas::where('kondisi_fasilitas', 'rusak berat')->count();

        return view('admin.dashboard.index',[

            // 'fasilitas_count' => $fasilitasCount,
            // 'kriteria_count' => $kriteriaCount,
            // 'user_count' => $userCount,
            // 'rusakberat_count' => $rusakberatCount,

        ]);
    }
}
