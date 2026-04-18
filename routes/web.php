<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\KodeAbsensiController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Anggota\AttendanceCodeController;
use App\Http\Controllers\Anggota\AttendanceHistoryController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\KegiatanController as AnggotaKegiatanController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('anggota', AdminAnggotaController::class)
            ->except('show')
            ->parameters([
                'anggota' => 'user',
            ]);

        Route::middleware('super_admin')->group(function () {
            Route::resource('admins', AdminController::class)
                ->except('show')
                ->parameters([
                    'admins' => 'user',
                ]);
        });

        Route::resource('kegiatan', AdminKegiatanController::class)->except('show');

        Route::get('kode-absensi', [KodeAbsensiController::class, 'index'])->name('kode-absensi.index');
        Route::post('kegiatan/{kegiatan}/kode-absensi', [KodeAbsensiController::class, 'store'])->name('kode-absensi.store');
        Route::patch('kode-absensi/{kodeAbsensi}/toggle', [KodeAbsensiController::class, 'toggle'])->name('kode-absensi.toggle');

        Route::get('absensi', [AdminAbsensiController::class, 'index'])->name('absensi.index');
        Route::get('absensi/create', [AdminAbsensiController::class, 'create'])->name('absensi.create');
        Route::post('absensi', [AdminAbsensiController::class, 'store'])->name('absensi.store');
        Route::get('absensi/{absensi}/edit', [AdminAbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('absensi/{absensi}', [AdminAbsensiController::class, 'update'])->name('absensi.update');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

Route::prefix('anggota')
    ->middleware(['auth', 'role:anggota'])
    ->as('anggota.')
    ->group(function () {
        Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kegiatan', [AnggotaKegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/absensi/kode', [AttendanceCodeController::class, 'create'])->name('absensi.create');
        Route::post('/absensi/kode', [AttendanceCodeController::class, 'store'])->name('absensi.store');
        Route::get('/riwayat', [AttendanceHistoryController::class, 'index'])->name('riwayat.index');
    });

require __DIR__.'/auth.php';
