<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->cari;

        try {
            $data = Buku::query()
                ->select('*')
                ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/user/coverbook.jpg') . '" else concat("' . url('/storage/buku') . '","/thum_", foto) end as foto'))
                ->withCount([
                    'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                ])
                ->with([
                    'kategori' => fn ($e) => $e->select('id', 'kode', 'kategori'),
                    'penerbit' => fn ($e) => $e->select('id', 'kode', 'penerbit'),
                ])
                ->when($cari, function ($e, $cari) {
                    $e->where(function ($e) use ($cari) {
                        $e->where('judul', 'like', '%' . $cari . '%')->orWhere('isbn', 'like', '%' . $cari . '%');
                    });
                })
                ->where('status', true)
                ->orderBy('id', 'desc')
                ->get();

            foreach ($data as $item) {
                $records[] = [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'foto' => $item->foto,
                    'pengarang' => $item->pengarang,
                    'isbn' => $item->isbn,
                    'stok' => $item->stok,
                    'dipinjam' => $item->dipinjam,
                    'status_pinjam' => ($item->stok - $item->dipinjam) === 0 ? 'Kosong' : 'Tersedia',
                    'kategori' => $item->kategori,
                    'penerbit' => $item->penerbit,
                    'created_at' => $item->created_at,
                ];
            }

            return $this->responOk(data: $records);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function show($id)
    {
        try {
            $data = Buku::query()
                ->select('*')
                ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/user/coverbook.jpg') . '" else concat("' . url('/storage/buku') . '","/thum_", foto) end as foto'))
                ->withCount([
                    'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                ])
                ->with([
                    'kategori',
                    'penerbit',
                ])
                ->where('status', true)
                ->where('id', $id);

            if ($data->count() > 0) {
                $data = $data->first();

                $data = [
                    'id' => $data->id,
                    'judul' => $data->judul,
                    'foto' => $data->foto,
                    'pengarang' => $data->pengarang,
                    'isbn' => $data->isbn,
                    'stok' => $data->stok,
                    'dipinjam' => $data->dipinjam,
                    'status_pinjam' => ($data->stok - $data->dipinjam) === 0 ? 'Kosong' : 'Tersedia',
                    'kategori' => $data->kategori,
                    'penerbit' => $data->penerbit,
                    'created_at' => $data->created_at,
                ];

                return $this->responOk(data: $data);
            } else {
                return $this->responError('Data tidak ditemukan', kode: 422);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError();
        }
    }

    public function peminjaman(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        try {
            $data = Peminjaman::query()
                ->with([
                    'buku_item' => fn ($e) => $e->with([
                        'buku' => fn ($e) => $e->with([
                            'kategori',
                            'penerbit'
                        ])
                            ->select('*')
                            ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/user/coverbook.jpg') . '" else concat("' . url('/storage/buku') . '","/thum_", foto) end as foto')),
                    ]),
                ])
                ->withSum('denda', 'denda')
                ->where('anggota_id', Auth::id())
                ->where('status', true)
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('id', 'desc');

            if ($data->count() > 0) {
                foreach ($data->get() as $item) {
                    $records[] = [
                        'id' => $item->id,
                        'tgl_pinjam' => Carbon::parse($item->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY'),
                        'batas_pengembalian' => Carbon::parse($item->batas_pengembalian)->isoFormat('DD MMM YYYY'),
                        'status_pinjam' => $item->is_kembali === 1 ? 'Dikembalikan' : 'Dipinjam',
                        'tgl_kembali' => $item->is_kembali === 1 ? Carbon::parse($item->updated_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY') : '-',
                        'denda' => $item->denda_sum_denda === null ? 0 : (int) $item->denda_sum_denda,
                        'buku' => [
                            'id' => $item->buku_item->buku->id,
                            'kode_buku' => $item->buku_item->kode,
                            'judul' => $item->buku_item->buku->judul,
                            'foto' => $item->buku_item->buku->foto,
                            'pengarang' => $item->buku_item->buku->pengarang,
                            'isbn' => $item->buku_item->buku->isbn,
                            'kategori' => $item->buku_item->buku->kategori,
                            'penerbit' => $item->buku_item->buku->penerbit,
                        ],
                    ];
                }

                return $this->responOk(data: $records);
            } else {
                return $this->responOk('Data tidak tersedia');
            }

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }
}
