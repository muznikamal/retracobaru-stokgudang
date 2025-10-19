<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'check.device'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']) // pastikan hanya user login yang bisa masuk
        ->name('dashboard');

    // Admin only (CRUD penuh)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('barang', BarangController::class);
        Route::resource('devices', DeviceController::class)->except(['create','store']); 
        Route::put('devices/{device}/approve', [DeviceController::class,'approve'])->name('devices.approve');
        Route::delete('devices/{device}/reject', [DeviceController::class,'reject'])->name('devices.reject');

        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        

        Route::resource('barang-masuk', BarangMasukController::class)->except(['show']);
        Route::resource('barang-keluar', BarangKeluarController::class)->except(['show']);
    });

    // Staff + Admin untuk input & lihat
    Route::middleware(['role:staff|admin','check.device'])->group(function () {
        Route::get('my-device', [DeviceController::class,'myDevice'])->name('devices.my');
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::resource('barang', BarangController::class);
        Route::resource('barang-masuk', BarangMasukController::class)->only(['index','create','store','update','edit']);
        Route::resource('barang-keluar', BarangKeluarController::class)->only(['index','create','store','update','edit']);
        Route::get('laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    });
});


require __DIR__.'/auth.php';
