<?php

use App\Http\Controllers\BahanbakarController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\Users;
use App\Models\Pemeliharaan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
        return view('dashboard.index');
});

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