<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Http\Requests\ShowAnggotaRequest;
use App\Http\Requests\SimpanPengembalianRequest;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pengembalian-read')->only('index');
        $this->middleware('permission:pengembalian-create')->only(['create', 'store']);
        $this->middleware('permission:pengembalian-update')->only(['edit', 'update']);
        $this->middleware('permission:pengembalian-delete')->only('delete');
    }

    public function index()
    {
        return view('backend.pengembalian.index');
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

    public function daftar_buku(Request $request)
    {
        $buku = $request->buku;
        $data = Peminjaman::query()
            ->whereHas('anggota', function ($e) use ($request) {
                $e->where('nomor_anggota', $request->nomor_anggota);
            })
            ->with([
                'buku_item' => fn ($e) => $e->with('buku'),
            ])
            ->when($buku, function ($e, $buku) {
                $e->whereHas('buku_item', function ($e) use ($buku) {
                    $e->where('kode', $buku);
                });
            })
            ->where('is_kembali', false)
            ->where('status', true)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($e) {
                return '<input type="checkbox" class="kode_item ' . $e->buku_item->kode . '" name="kode_item[]" value="' . $e->id . '">';
            })
            ->addColumn('tgl_pinjam', function ($e) {
                return Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMMM YYYY');
            })
            ->addColumn('batas_kembali', function ($e) {
                return Carbon::parse($e->batas_pengembalian)->timezone(zona_waktu())->isoFormat('DD MMMM YYYY');
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    public function proses_kembali(SimpanPengembalianRequest $request)
    {
        DB::beginTransaction();
        try {
            // Cari id Anggota
            $id_anggota = Anggota::where('nomor_anggota', $request->anggota)->first()->id;
            Peminjaman::whereIn('id', $request->kode_buku)->where('anggota_id', $id_anggota)->update(['is_kembali' => true]);

            Weblog::set('Mengembalikan buku : ('.implode(", ", $request->kode_buku).')');
            DB::commit();

            return response()->json([
                'message' => 'Data berhasil diinputkan',
                'status' => true,
                'data' => []
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
