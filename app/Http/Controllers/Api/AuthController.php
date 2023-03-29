<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Banner;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'anggota' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validasi->fails()) {
            return $this->responError(data: $validasi->errors(), kode: 422);
        }

        $auth = Auth::guard('anggota')->attempt([
            'nomor_anggota' => $request->anggota,
            'password' => $request->password,
            'status' => true
        ]);

        if ($auth) {
            $user = Anggota::query()
                ->with([
                    'kelas',
                    'kota'
                ])
                ->where('nomor_anggota', $request->anggota)
                ->select('*')
                ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/foto/pasfoto.jpg') . '" else concat("' . url('/storage/anggota') . '","/", foto) end as foto'))
                ->first();

            $token = $user->createToken($user->nama)->plainTextToken;

            return $this->responOk(data: $user, token: $token);
        } else {
            return $this->responError('Nomor Anggota atau password salah!', kode: 422);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return $this->responOk('Berhasil Logout', $user);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function init_home()
    {
        $data = [];

        try {
            $jabatan = Auth::user()->jabatan;
            $kategori = Kategori::query()
                ->has('buku')
                ->with([
                    'buku' => fn ($e) => $e->select('id', 'judul', 'pengarang', 'isbn')
                        ->where('status', true)
                        ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/user/coverbook.jpg') . '" else concat("' . url('/storage/buku') . '","/thum_", foto) end as foto'))
                        ->orderBy('id', 'desc'),
                ])
                ->when($jabatan, function ($e, $jabatan) {
                    if ($jabatan === 'siswa') {
                        $e->where('akses_siswa', true);
                    } else {
                        $e->where('akses_guru', true);
                    }
                })
                ->where('status', true)
                ->orderBy('urutan')
                ->orderBy('id', 'desc')
                ->limit(8)
                ->get()
                ->map(function ($e) {
                    return $e->setRelation('buku', $e->buku->take(10));
                });

            $banner = Banner::where('status', true)
                ->select('id', 'keterangan')
                ->addSelect(DB::raw('concat("' . url('/storage/banner') . '","/", gambar) as foto'))
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $data['banner'] = $banner;
            $data['kategori'] = $kategori;

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }
}
