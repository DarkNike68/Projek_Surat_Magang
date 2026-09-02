<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// Controller dari Projek Surat
use App\Http\Controllers\Admin\BuatSuratController;
use App\Http\Controllers\Admin\RiwayatSuratController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\LetterCodeController;
// Controller dari Proyek Manajemen Dokumen

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// RUTE UNTUK SEMUA PENGGUNA YANG SUDAH LOGIN
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/surat/{surat}/upload', [DashboardController::class, 'uploadFile'])->name('dashboard.surat.upload');
    Route::post('/dashboard/surat/{surat}/upload-final', [DashboardController::class, 'uploadFinalFile'])->name('dashboard.surat.uploadFinal');
    
    
    // Riwayat Surat
    Route::get('/riwayat-surat', [RiwayatSuratController::class, 'index'])->name('riwayat.index');

    // Proses Pembuatan Surat
    Route::prefix('buat-nomor-surat')->name('surat.')->group(function () {
        Route::get('/langkah-1', [BuatSuratController::class, 'showJabatanStep'])->name('step1.show');
        Route::post('/langkah-1', [BuatSuratController::class, 'storeJabatanStep'])->name('step1.store');
        Route::get('/langkah-2', [BuatSuratController::class, 'showJenisSuratStep'])->name('step2.show');
        Route::post('/langkah-2', [BuatSuratController::class, 'storeJenisSuratStep'])->name('step2.store');
        Route::get('/langkah-3', [BuatSuratController::class, 'showFinalStep'])->name('step3.show');
        Route::post('/langkah-3', [BuatSuratController::class, 'generateAndStore'])->name('generate');
        Route::get('/download-template', [BuatSuratController::class, 'downloadTemplate'])->name('template.download');
        Route::get('/preview-nomor', [BuatSuratController::class, 'previewNomorSurat'])->name('preview');
    });

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === RUTE KHUSUS AKSES FILE (Pengecekan Keamanan Manual di Controller) ===
Route::get('/dashboard/surat/{surat}/lihat-file', [DashboardController::class, 'lihatFile'])->name('dashboard.surat.lihatFile');


// RUTE KHUSUS UNTUK ADMIN
Route::middleware(['auth', 'can:view-admin-menu'])->prefix('admin')->name('admin.')->group(function () {
    // === Rute dari Proyek Surat (Bagian Admin) ===
    Route::prefix('letter-codes')->name('letter-codes.')->group(function () {
        Route::get('/jenis-surat', [LetterCodeController::class, 'index'])->name('jenis-surat.index');  
        Route::get('/jabatan', [LetterCodeController::class, 'index'])->name('jabatan.index');
        Route::post('/', [LetterCodeController::class, 'store'])->name('store');
        Route::put('/{letterCode}', [LetterCodeController::class, 'update'])->name('update');
        Route::delete('/{letterCode}', [LetterCodeController::class, 'destroy'])->name('destroy');
    });
    // === Rute dari Proyek Surat (Bagian Admin) ===
    Route::post('/surat/{surat}/update', [DashboardController::class, 'adminUpdate'])->name('surat.update');
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::post('/arsip', [ArsipController::class, 'store'])->name('arsip.store');
    Route::delete('/arsip/{type}/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
    Route::post('/surat/{surat}/arsip', [ArsipController::class, 'storeArsip'])->name('surat.arsip');
    Route::patch('/arsip/{type}/{id}', [ArsipController::class, 'update'])->name('arsip.update');
    Route::post('/surat/{surat}/unarchive', [ArsipController::class, 'unarchive'])->name('surat.unarchive');
    
    // === Rute dari Proyek Manajemen Dokumen ===
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/permissions/search-users', [PermissionController::class, 'searchUsers'])->name('permissions.searchUsers');
    Route::post('/permissions/exceptions/update', [PermissionController::class, 'updateException'])->name('permissions.updateException');
    Route::get('/permissions/exceptions', [PermissionController::class, 'getExceptions'])->name('permissions.getExceptions');
    
    Route::resource('users', UserController::class);

    // === Rute API (Khusus Admin) ===
    Route::get('/api/raks', [ArsipController::class, 'getRaks'])->name('api.raks');
    Route::get('/api/rak/{rak}/skats', [ArsipController::class, 'getSkatsByRak'])->name('api.skats');
    Route::get('/api/skat/{skat}/outners', [ArsipController::class, 'getOutnersBySkat'])->name('api.outners');
});

require __DIR__.'/auth.php';
