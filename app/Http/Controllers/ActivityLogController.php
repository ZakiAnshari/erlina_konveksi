<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(10);

        // Format deskripsi agar lebih mudah dibaca (opsional jika ingin ubah di controller, bisa juga langsung di blade)
        $logs->getCollection()->transform(function ($log) {
            // Cek apakah deskripsi sudah memiliki format baris, jika belum tambahkan penyesuaian
            if (strpos($log->description, "\n") !== false) {
                $log->formatted_description = nl2br(e($log->description)); // untuk tampilan HTML di blade
            } else {
                $log->formatted_description = e($log->description); // fallback
            }
            return $log;
        });

        return view('admin.logs.index', compact('logs'));
    }
}
