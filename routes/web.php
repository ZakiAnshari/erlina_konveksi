<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanPendapatanController;
use App\Http\Controllers\LaporanPengeluaranController;

//LOGIN
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticating']);
});

// ADMIN
Route::middleware(['auth'])->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index']);
    //PENDAPATAN
    Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan.index');
    Route::post('/pendapatan-add', [PendapatanController::class, 'store'])->name('pendapatan.store');
    Route::get('/pendapatan-edit/{id}', [PendapatanController::class, 'edit']);
    Route::post('/pendapatan-edit/{id}', [PendapatanController::class, 'update']);
    Route::get('/pendapatan-destroy/{id}', [PendapatanController::class, 'destroy']);
    //PENGELUARAN
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::post('/pengeluaran-add', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/pengeluaran-edit/{id}', [PengeluaranController::class, 'edit']);
    Route::post('/pengeluaran-edit/{id}', [PengeluaranController::class, 'update']);
    Route::get('/pengeluaran-destroy/{id}', [PengeluaranController::class, 'destroy']);
    //HUTANG
    Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index');
    Route::post('/hutang-add', [HutangController::class, 'store'])->name('hutang.store');
    Route::get('/hutang-edit/{id}', [HutangController::class, 'edit']);
    Route::post('/hutang-edit/{id}', [HutangController::class, 'update']);
    Route::get('/hutang-destroy/{id}', [HutangController::class, 'destroy']);
    //KARYAWAN
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('/karyawan-add', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan-edit/{id}', [KaryawanController::class, 'edit']);
    Route::post('/karyawan-edit/{id}', [KaryawanController::class, 'update']);
    Route::get('/karyawan-destroy/{id}', [KaryawanController::class, 'destroy']);
    //LAPORAN-PENDAPATAN
    Route::get('/laporan/pendapatan', [LaporanPendapatanController::class, 'index'])->name('laporan.pendapatan');
    Route::get('/laporan/pendapatan-cetak', [LaporanPendapatanController::class, 'cetakpendapatan'])->name('laporan.pendapatan-cetak');
    //LAPORAN-PENGELUARAN
    Route::get('/laporan/pengeluaran', [LaporanPengeluaranController::class, 'index'])->name('laporan.pengeluaran');
    Route::get('/laporan/pengeluaran-cetak', [LaporanPengeluaranController::class, 'cetakpengeluaran'])->name('laporan.pengeluaran-cetak');
    // USER
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user-add', [UserController::class, 'store'])->name('user.store');
    Route::get('/user-edit/{id}', [UserController::class, 'edit']);
    Route::post('/user-edit/{id}', [UserController::class, 'update']);
    Route::get('/user-destroy/{id}', [UserController::class, 'destroy']);
    Route::get('/user-show/{id}', [UserController::class, 'show'])->name('user.show');
    // LOGOUT
    Route::get('/logout', [AuthController::class, 'logout']);
});
