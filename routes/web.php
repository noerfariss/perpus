<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PengembalianController;
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

Route::get('/email', function () {
    try {
        Mail::to('noerfaris@gmail.com')->send(new HelloMail());
        return 'berhasil';
    } catch (\Throwable $th) {
        return $th->getMessage();
    }
});

Route::get('/', function(){
    return view('frontend.app');
});
Route::get('/profil', function(){
    return view('frontend.app');
});
Route::get('/fitur', function(){
    return view('frontend.app');
});
Route::get('/kontak', function(){
    return view('frontend.app');
});

Route::middleware('xss')->group(function () {
    Route::any('/login', [LoginController::class, 'index'])->name('login');

    Route::middleware(['auth'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
            Route::any('/profil', [UserController::class, 'profil'])->name('profil');
            Route::post('/simpan-foto', [UserController::class, 'simpan_foto'])->name('simpan-foto');

            Route::any('/password', [UserController::class, 'password'])->name('password');
            Route::post('/ganti-password', [UserController::class, 'ganti_password'])->name('ganti-password');
            Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
            Route::any('/weblog', [LogController::class, 'index'])->name('aktivitas');
            Route::get('/force-login/{id}', [LoginController::class, 'force_login'])->name('force-login');

            // Aktivitas
            Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
            Route::post('/peminjaman/anggota', [PeminjamanController::class, 'get_anggota'])->name('get-anggota');
            Route::post('/peminjaman/buku', [PeminjamanController::class, 'cari_buku'])->name('cari-buku');
            Route::post('/peminjaman/simpan', [PeminjamanController::class, 'simpan_peminjaman'])->name('simpan-peminjaman');
            Route::get('/peminjaman/notransaksi', [AjaxController::class, 'get_no_transaksi'])->name('get-no-transaksi');

            Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
            Route::post('/pengembalian/anggota', [PengembalianController::class, 'get_anggota'])->name('get-anggota-kembali');
            Route::post('/pengembalian/daftar-buku', [PengembalianController::class, 'daftar_buku'])->name('pengembalian.daftar_buku');
            Route::post('/pengembalian/simpan', [PengembalianController::class, 'proses_kembali'])->name('pengembalian.simpan');

            Route::resource('history', HistoryController::class);
            Route::post('ajax-history', [HistoryController::class, 'ajax'])->name('ajax-history');

            // Data Buku
            Route::resource('buku', BukuController::class);
            Route::post('/ajax-buku', [BukuController::class, 'ajax'])->name('ajax-buku');
            Route::post('/buku/hapus-item', [BukuController::class, 'hapus_item'])->name('hapus_buku_item');
            Route::post('/buku/tambah-stok', [BukuController::class, 'tambah_stok'])->name('tambah_stok');
            Route::get('/kode-kategori', [BukuController::class, 'getKodeKategori'])->name('buku.get_kode_kategori');
            Route::get('/kode-penerbit', [BukuController::class, 'getKodePenerbit'])->name('buku.get_kode_penerbit');

            Route::resource('kategori', KategoriController::class);
            Route::post('/ajax-kategori', [KategoriController::class, 'ajax'])->name('ajax-kategori');

            Route::resource('penerbit', PenerbitController::class);
            Route::post('/ajax-penerbit', [PenerbitController::class, 'ajax'])->name('ajax-penerbit');

            // Data Master
            Route::resource('siswa', AnggotaController::class);
            Route::get('/siswa/kartu/{anggota}', [AnggotaController::class, 'kartu'])->name('siswa.kartu');
            Route::post('/ajax-siswa', [AnggotaController::class, 'ajax'])->name('ajax-siswa');
            Route::post('/siswa-ganti-password', [AnggotaController::class, 'ganti_password'])->name('siswa-ganti-password');

            Route::resource('guru', GuruController::class);
            Route::post('/ajax-guru', [GuruController::class, 'ajax'])->name('ajax-guru');

            Route::resource('kelas', KelasController::class);
            Route::post('/ajax-kelas', [KelasController::class, 'ajax'])->name('ajax-kelas');

            // Pengaturan
            Route::singleton('umum', UmumController::class);
            Route::get('/umum/peminjaman', [UmumController::class, 'peminjaman'])->name('umum.peminjaman');
            Route::any('/umum/peminjaman/edit', [UmumController::class, 'editPinjam'])->name('umum.peminjaman.edit');

            Route::resource('user', UserController::class);
            Route::post('/ajax-user', [UserController::class, 'ajax'])->name('ajax-user');

            Route::resource('banner', BannerController::class);
            Route::post('/ajax-banner', [BannerController::class, 'ajax'])->name('ajax-banner');

            Route::resource('role', RoleController::class);
            Route::post('/ajax-role', [RoleController::class, 'ajax'])->name('ajax-roles');
            Route::resource('permission', PermissionController::class);
            Route::post('/ajax-permission', [PermissionController::class, 'ajax'])->name('ajax-permission');

            // ajax
            Route::prefix('ajax')->group(function () {
                Route::post('/role', [AjaxController::class, 'role'])->name('drop-role');
                Route::post('/provinsi', [AjaxController::class, 'provinsi'])->name('drop-provinsi');
                Route::post('/kota', [AjaxController::class, 'kota'])->name('drop-kota');
                Route::post('/kecamatan', [AjaxController::class, 'kecamatan'])->name('drop-kecamatan');
                Route::post('/kelas', [AjaxController::class, 'kelas'])->name('drop-kelas');
                Route::post('/kategori', [AjaxController::class, 'kategori'])->name('drop-kategori');
                Route::post('/penerbit', [AjaxController::class, 'penerbit'])->name('drop-penerbit');
                Route::post('/ganti-foto', [AjaxController::class, 'ganti_foto'])->name('ganti-foto');
                Route::post('/ganti-pdf', [AjaxController::class, 'ganti_pdf'])->name('ganti-pdf');
            });
        });
    });
});
