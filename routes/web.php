<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseCategoryController;
use App\Http\Controllers\CounselingRecordController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Utama (Semua Role)
    Route::get('/', [CounselingRecordController::class, 'dashboard'])->name('bk.dashboard');

    // Pencatatan & Kelola Identitas Siswa (Super Admin, Psikologi, Staff)
    Route::middleware('role:super_admin,psikologi,staff')->group(function (): void {
        Route::get('/identitas', [CounselingRecordController::class, 'identitasIndex'])->name('bk.identitas');
        Route::post('/identitas', [CounselingRecordController::class, 'identitasStore'])->name('bk.identitas.store');
        Route::put('/identitas/{counselingRecord}', [CounselingRecordController::class, 'identitasUpdate'])->name('bk.identitas.update');
    });

    // Pencatatan & Penanganan Kasus/Konseling Penuh + Kelola Kategori Case (Super Admin & Psikologi)
    Route::middleware('role:super_admin,psikologi')->group(function (): void {
        Route::get('/kasus/tambah', [CounselingRecordController::class, 'create'])->name('bk.create');
        Route::post('/kasus', [CounselingRecordController::class, 'store'])->name('bk.store');
        Route::get('/kasus/{counselingRecord}/edit', [CounselingRecordController::class, 'edit'])->name('bk.edit');
        Route::put('/kasus/{counselingRecord}', [CounselingRecordController::class, 'update'])->name('bk.update');
        Route::delete('/kasus/{counselingRecord}', [CounselingRecordController::class, 'destroy'])->name('bk.destroy');

        Route::get('/kategori-case', [CaseCategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori-case', [CaseCategoryController::class, 'store'])->name('categories.store');
        Route::put('/kategori-case/{caseCategory}', [CaseCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori-case/{caseCategory}', [CaseCategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Rekap Data Kasus & Pencarian (Super Admin, Psikologi, Staff, Atasan)
    Route::middleware('role:super_admin,psikologi,staff,atasan')->group(function (): void {
        Route::get('/kasus', [CounselingRecordController::class, 'index'])->name('bk.index');
        Route::get('/kasus/{counselingRecord}', [CounselingRecordController::class, 'show'])->name('bk.show');
    });

    // Laporan Bulanan BK (Super Admin, Psikologi, Atasan)
    Route::middleware('role:super_admin,psikologi,atasan')->group(function (): void {
        Route::get('/laporan-bulanan', [CounselingRecordController::class, 'monthlyReport'])->name('bk.report');
        Route::get('/laporan-bulanan/export-csv', [CounselingRecordController::class, 'exportMonthlyCsv'])->name('bk.report.csv');
    });

    // Manajemen Akun Pengguna (Hanya Super Admin)
    Route::middleware('role:super_admin')->group(function (): void {
        Route::get('/akun', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/akun', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/akun/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/akun/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});
