<?php

use App\Models\Pemeliharaan;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PajakPlatController;
use App\Http\Controllers\BahanbakarController;
use App\Http\Controllers\PajakTahunanController;
use App\Http\Controllers\PemeliharaanController;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// route pegawai
Route::get('/pegawai/{slug}/edit', [Users::class, 'edit']);
Route::put('/pegawai/{slug}', [Users::class, 'update']);
Route::delete('/pegawai/{slug}', [Users::class, 'destroy']);
Route::get('/pegawai/tambah-pegawai', [Users::class, 'create'])->name('users.users-create');
Route::resource('/pegawai', Users::class)->only([
        'index',
        'store'
]);
// route rekening
Route::get('/rekening/{slug}/edit', [RekeningController::class, 'edit']);
Route::put('/rekening/{slug}', [RekeningController::class, 'update']);
Route::delete('/rekening/{id}', [RekeningController::class, 'destroy']);
Route::get('/rekening/tambah-rekening', [RekeningController::class, 'create'])->name('rekening.rekening-create');
Route::resource('/rekening', RekeningController::class)->only([
        'index',
        'store'
]);
// route kendaraan
Route::get('/kendaraan/{slug}/edit', [KendaraanController::class, 'edit']);
Route::get('/kendaraan/detail-kendaraan/{slug}', [KendaraanController::class, 'detail']);
Route::put('/kendaraan/{slug}', [KendaraanController::class, 'update'])->name('kendaraan.update');
Route::delete('/kendaraan/{slug}', [KendaraanController::class, 'destroy']);
Route::get('/kendaraan/tambah-kendaraan', [KendaraanController::class, 'create'])->name('kendaraan.kendaraan-create');
Route::post('/kendaraan/store', [KendaraanController::class, 'store'])->name('kendaraan.store');
Route::get('/kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');

// route pemeliharaan
Route::get('/pemeliharaan/{slug}/edit', [PemeliharaanController::class, 'edit']);
Route::get('/pemeliharaan/{slug}/show', [PemeliharaanController::class, 'show']);
Route::put('/pemeliharaan/{id}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
Route::delete('/pemeliharaan/{id}', [PemeliharaanController::class, 'destroy']);
Route::get('/pemeliharaan/tambah-pemeliharaan', [PemeliharaanController::class, 'create'])->name('pemeliharaan.pemeliharaan-create');
Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan.index');

// route pengeluaran bbm
Route::get('/pengeluaran-bbm/{slug}/edit', [BahanbakarController::class, 'edit']);
Route::get('/pengeluaran-bbm/{slug}/show', [BahanbakarController::class, 'show']);
Route::put('/pengeluaran-bbm/{id}', [BahanbakarController::class, 'update'])->name('pengeluaran-bbm.update');
Route::delete('/pengeluaran-bbm/{id}', [BahanbakarController::class, 'destroy']);
Route::get('/pengeluaran-bbm/tambah-pengeluaran-bbm', [BahanbakarController::class, 'create'])->name('pengeluaran-bbm.bahanbakar-create');
Route::post('/pengeluaran-bbm/store', [BahanbakarController::class, 'store'])->name('pengeluaran-bbm.store');
Route::get('/pengeluaran-bbm', [BahanbakarController::class, 'index'])->name('pengeluaran-bbm.index');

// route pajak tahunan
Route::get('/pajak-tahunan/{slug}/edit', [PajakTahunanController::class, 'edit']);
Route::get('/pajak-tahunan/{slug}/show', [PajakTahunanController::class, 'show']);
Route::put('/pajak-tahunan/{id}', [PajakTahunanController::class, 'update'])->name('pajak-tahunan.update');
Route::delete('/pajak-tahunan/{id}', [PajakTahunanController::class, 'destroy']);
Route::get('/pajak-tahunan/tambah-pajak-tahunan', [PajakTahunanController::class, 'create'])->name('pajak-tahunan.pajaktahunan-create');
Route::post('/pajak-tahunan/store', [PajakTahunanController::class, 'store'])->name('pajak-tahunan.store');
Route::get('/pajak-tahunan', [PajakTahunanController::class, 'index'])->name('pajak-tahunan.index');

// route pajak tahunan
Route::get('/pajak-plat/{slug}/edit', [PajakPlatController::class, 'edit']);
Route::get('/pajak-plat/{slug}/show', [PajakPlatController::class, 'show']);
Route::put('/pajak-plat/{id}', [PajakPlatController::class, 'update'])->name('pajak-plat.update');
Route::delete('/pajak-plat/{id}', [PajakPlatController::class, 'destroy']);
Route::get('/pajak-plat/tambah-pajak-plat', [PajakPlatController::class, 'create'])->name('pajak-plat.pajaktahunan-create');
Route::post('/pajak-plat/store', [PajakPlatController::class, 'store'])->name('pajak-plat.store');
Route::get('/pajak-plat', [PajakPlatController::class, 'index'])->name('pajak-plat.index');

// pengeluaran keuangan
Route::get('/pengeluaran', [KeuanganController::class, 'index'])->name('pajak-plat.index');

Route::get('/chart-data', [DashboardController::class, 'getChartData']);