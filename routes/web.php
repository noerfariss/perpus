<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UmumController;
use App\Http\Controllers\UserController;
use App\Mail\HelloMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email', function(){
    try {
        Mail::to('noerfaris@gmail.com')->send(new HelloMail());
        return 'berhasil';
    } catch (\Throwable $th) {
        return $th->getMessage();
    }


});

Route::middleware('xss')->group(function(){
    Route::any('/', [LoginController::class, 'index'])->name('login');

    Route::middleware(['auth'])->group(function(){
        Route::prefix('auth')->group(function(){
            Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
            Route::any('/profil', [UserController::class, 'profil'])->name('profil');
            Route::post('/ganti-foto', [UserController::class, 'ganti_foto'])->name('ganti-foto');
            Route::post('/simpan-foto', [UserController::class, 'simpan_foto'])->name('simpan-foto');
            Route::any('/password', [UserController::class, 'password'])->name('password');
            Route::post('/ganti-password', [UserController::class, 'ganti_password'])->name('ganti-password');
            Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
            Route::any('/weblog', [LogController::class, 'index'])->name('aktivitas');
            Route::get('/force-login/{id}', [LoginController::class, 'force_login'])->name('force-login');

            // Aktivitas


            // Data Buku
            Route::resource('kategori', KategoriController::class);
            Route::post('/ajax-kategori', [KategoriController::class, 'ajax'])->name('ajax-kategori');


            // Data Master
            Route::resource('siswa', AnggotaController::class);
            Route::post('/ajax-siswa', [AnggotaController::class, 'ajax'])->name('ajax-siswa');
            Route::post('/siswa-ganti-password', [AnggotaController::class, 'ganti_password'])->name('siswa-ganti-password');
            Route::post('/ganti-foto-anggota', [AnggotaController::class, 'ganti_foto'])->name('ganti-foto-anggota');

            Route::resource('guru', GuruController::class);
            Route::post('/ajax-guru', [GuruController::class, 'ajax'])->name('ajax-guru');

            Route::resource('kelas', KelasController::class);
            Route::post('/ajax-kelas', [KelasController::class, 'ajax'])->name('ajax-kelas');


            // Pengaturan
            Route::singleton('umum', UmumController::class);
            Route::resource('user', UserController::class);
            Route::post('/ajax-user', [UserController::class, 'ajax'])->name('ajax-user');

            Route::resource('role', RoleController::class);
            Route::post('/ajax-role', [RoleController::class, 'ajax'])->name('ajax-roles');
            Route::resource('permission', PermissionController::class);
            Route::post('/ajax-permission', [PermissionController::class, 'ajax'])->name('ajax-permission');

            // ajax
            Route::prefix('ajax')->group(function(){
                Route::post('/role', [AjaxController::class, 'role'])->name('drop-role');
                Route::post('/provinsi', [AjaxController::class, 'provinsi'])->name('drop-provinsi');
                Route::post('/kota', [AjaxController::class, 'kota'])->name('drop-kota');
                Route::post('/kecamatan', [AjaxController::class, 'kecamatan'])->name('drop-kecamatan');
                Route::post('/kelas', [AjaxController::class, 'kelas'])->name('drop-kelas');

            });
        });
    });

});
