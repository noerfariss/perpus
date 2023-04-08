<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $jabatan = Auth::user()->jabatan;
            $data = Kategori::where('status', true)
                ->withCount('buku')
                ->when($jabatan, function ($e, $jabatan) {
                    if ($jabatan === 'siswa') {
                        $e->where('akses_siswa', true);
                    } else {
                        $e->where('akses_guru', true);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function show($id)
    {
        try {
            $jabatan = Auth::user()->jabatan;
            $data = Kategori::query()
                ->with([
                    'buku' => fn ($e) => $e->select('*')
                        ->withCount([
                            'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                        ])
                        ->with(['penerbit']),
                ])
                ->withCount('buku')
                ->when($jabatan, function ($e, $jabatan) {
                    if ($jabatan === 'siswa') {
                        $e->where('akses_siswa', true);
                    } else {
                        $e->where('akses_guru', true);
                    }
                })
                ->where('status', true)
                ->where('id', $id);

            if ($data->count() > 0) {
                $data = $data->first();

                $bukus = [];
                foreach ($data->buku as $item) {
                    $bukus[] = [
                        'id' => $item->id,
                        'judul' => $item->judul,
                        'foto' => ($item->foto == null or $item->foto == '') ? url('backend/sneat-1.0.0/assets/img/avatars/coverbook.jpg') : base_url($item->foto),
                        'pengarang' => $item->pengarang,
                        'isbn' => $item->isbn,
                        'stok' => $item->stok,
                        'dipinjam' => $item->dipinjam,
                        'status_pinjam' => ($item->stok - $item->dipinjam) === 0 ? 'Kosong' : 'Tersedia',
                        'penerbit' => [
                            'id' => $item->penerbit->id,
                            'kode' => $item->penerbit->kode,
                            'penerbit' => $item->penerbit->penerbit,
                        ],
                        'created_at' => $item->created_at,
                    ];
                }

                $records = [
                    'id' => $data->id,
                    'kode' => $data->kode,
                    'kategori' => $data->kategori,
                    'buku_count' => $data->buku_count,
                    'buku' => $bukus,
                ];

                return $this->responOk(data: $records);
            } else {
                return $this->responError('Data tidak ditemukan', kode: 422);
            }

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }
}
