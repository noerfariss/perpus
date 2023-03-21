<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $data = Kategori::where('status', true)
                ->withCount('buku')
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
            $data = Kategori::query()
                ->with([
                    'buku' => fn ($e) => $e->select('*')
                        ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/user/coverbook.jpg') . '" else concat("' . url('/storage/buku') . '","/", foto) end as foto'))
                        ->withCount([
                            'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                        ])
                        ->with(['penerbit']),
                ])
                ->withCount('buku')
                ->where('status', true)
                ->where('id', $id);

            if ($data->count() > 0) {
                $data = $data->first();

                $bukus = [];
                foreach($data->buku as $item){
                    $bukus [] = [
                        'id' => $item->id,
                        'judul' => $item->judul,
                        'foto' => $item->foto,
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
