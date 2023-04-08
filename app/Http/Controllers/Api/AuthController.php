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
use Illuminate\Support\Facades\Storage;
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
                ->first();

            $foto = $user->foto;
            if ($foto) {
                $user['foto'] = base_url($foto);
            } else {
                $user['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
            }

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
            $banner = Banner::where('status', true)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $data['banner'] = $banner;
            foreach ($banner as $key => $row) {
                if ($row->gambar) {
                    $data['banner'][$key]['foto'] = base_url($row->gambar);
                } else {
                    $data['banner'][$key]['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/banner.jpg');
                }
            }

            $jabatan = Auth::user()->jabatan;
            $kategori = Kategori::query()
                ->has('buku')
                ->with([
                    'buku' => fn ($e) => $e->select('id', 'judul', 'foto', 'pengarang', 'isbn')
                        ->where('status', true)
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

            $data['kategori'] = $kategori;
            foreach ($kategori as $key => $item) {
                foreach ($item->buku as $x => $row) {
                    if ($row->foto) {
                        $data['kategori'][$key]['buku'][$x]['foto'] = base_url($row->foto);
                    } else {
                        $data['kategori'][$key]['buku'][$x]['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/coverbook.jpg');
                    }
                }
            }

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|file',
        ]);

        if ($validator->fails()) {
            return $this->responError(data: $validator->errors());
        }

        if ($request->hasFile('gambar')) {
            try {
                $gambar = $request->file('gambar');
                $path = Storage::disk('s3')->put('belajar', $gambar);
                $url = Storage::disk('s3')->url($path);

                $data = [
                    'path' => $path,
                    'url' => $url
                ];

                return $this->responOk(data: $data);
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                return $this->responError();
            }
        }
    }
}
