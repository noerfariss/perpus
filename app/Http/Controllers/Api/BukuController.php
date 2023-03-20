<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuController extends Controller
{
    public function index()
    {
        try {
            $data = Buku::query()
                ->select('*')
                ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/user/coverbook.jpg') . '" else concat("' . url('/storage/buku') . '","/", foto) end as foto'))
                ->withCount([
                    'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                ])
                ->with([
                    'kategori' => fn ($e) => $e->select('id', 'kode', 'kategori'),
                    'penerbit' => fn ($e) => $e->select('id', 'kode', 'penerbit'),
                ])
                ->where('status', true)
                ->orderBy('id', 'desc')
                ->get();

            foreach($data as $item){
                $records [] = [
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
}
