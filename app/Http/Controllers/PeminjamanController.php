<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowAnggotaRequest;
use App\Http\Requests\ShowBukuRequest;
use App\Http\Requests\SimpanPeminjamanRequest;
use App\Models\Anggota;
use App\Models\BukuItem;
use App\Models\Peminjaman;
use App\Models\Umum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.peminjaman.index');
    }

    public function get_anggota(ShowAnggotaRequest $request)
    {

        try {
            $anggota = $request->anggota;
            $data = Anggota::query()
                ->with(['kelas'])
                ->where(function ($e) use ($anggota) {
                    $e->where('nomor_induk', $anggota)->orWhere('nomor_anggota', $anggota);
                })
                ->first();

            if ($data) {
                return response()->json([
                    'message' => 'Data berhasil ditemukan',
                    'status' => true,
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'message' => 'Nomor Induk atau Nomor Anggota tidak ditemukan',
                    'status' => false,
                    'data' => []
                ]);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                'errors' => [
                    'data' => [
                        'Terjadi kesalahan, cobalah kembali'
                    ],
                ],
            ], 500);
        }
    }

    public function cari_buku(ShowBukuRequest $request)
    {
        try {
            $buku = $request->buku;
            $data = BukuItem::query()
                ->with([
                    'buku' => fn ($e) => $e->with(['kategori', 'penerbit']),
                ])
                ->withCount('peminjaman_belum_kembali')
                ->where('kode', $buku)
                ->where('status', true)
                ->first();

            if ($data) {
                if ($data->peminjaman_belum_kembali_count === 0) {
                    return response()->json([
                        'message' => 'Data berhasil ditemukan',
                        'status' => true,
                        'data' => $data,
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Buku masih dipinjam',
                        'status' => false,
                        'data' => [],
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Kode buku tidak ditemukan',
                    'status' => false,
                    'data' => []
                ]);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                'errors' => [
                    'data' => [
                        'Terjadi kesalahan, cobalah kembali'
                    ],
                ],
            ], 500);
        }
    }

    public function simpan_peminjaman(SimpanPeminjamanRequest $request)
    {
        DB::beginTransaction();
        try {
            // Input kode transaksi
            $id_transaksi = DB::table('peminjaman_transaksi')->insertGetId([
                'kode' => $request->kode_transaksi,
                'created_at' => Carbon::now(),
            ]);

            // Cari id Anggota
            $id_anggota = Anggota::where('nomor_anggota', $request->anggota)->first()->id;

            $peminjaman = [];
            $batas_pengembalian = Umum::first()->batas_pengembalian;
            $buku = BukuItem::whereIn('kode', $request->kode_buku_arr)->get();
            foreach ($buku as $item) {
                $peminjaman[] = [
                    'anggota_id' => $id_anggota,
                    'buku_item_id' => $item->id,
                    'transaksi_id' => $id_transaksi,
                    'batas_pengembalian' => Carbon::now()->addDays($batas_pengembalian),
                    'created_at' => Carbon::now(),
                ];
            }

            $data = Peminjaman::insert($peminjaman);
            DB::commit();

            return response()->json([
                'message' => 'Data berhasil diinputkan',
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return response()->json([
                'errors' => [
                    'data' => [
                        'Terjadi kesalahan, cobalah kembali'
                    ],
                ],
            ], 500);
        }
    }
}
