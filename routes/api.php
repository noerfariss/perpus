<?php

use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\KotaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('xss')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::get('/sekolah', [ProfilController::class, 'sekolah']);
    Route::get('/hitung', [KotaController::class, 'tes']);

    Route::post('/upload-file', [AuthController::class, 'upload']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::get('/init-home', [AuthController::class, 'init_home']);

        Route::prefix('profil')->group(function () {
            Route::apiSingleton('user', ProfilController::class);
            Route::post('/password', [ProfilController::class, 'password']);
            Route::post('/ganti-foto', [ProfilController::class, 'ganti_foto']);
            Route::get('/kartu', [ProfilController::class, 'kartu_anggota']);
        });

        Route::prefix('buku')->group(function () {
            Route::get('/kategori', [KategoriController::class, 'index']);
            Route::get('/kategori/{id}', [KategoriController::class, 'show']);
            Route::post('/list', [BukuController::class, 'index']);
            Route::get('/detail/{id}', [BukuController::class, 'show']);
            Route::post('/peminjaman', [BukuController::class, 'peminjaman']);
            Route::get('/read/{id}', [BukuController::class, 'log_buku_read']);
            Route::post('/tutup', [BukuController::class, 'log_buku_tutup']);
            Route::post('/logsiswa', [BukuController::class, 'log_siswa']);
        });

        Route::prefix('master')->group(function () {
            Route::apiResource('kelas', KelasController::class);
            Route::apiResource('kota', KotaController::class);
            Route::apiResource('siswa', AnggotaController::class);
        });
    });
});
