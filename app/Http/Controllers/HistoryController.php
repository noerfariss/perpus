<?php

namespace App\Http\Controllers;

use App\Exports\HistoryExport;
use App\Facade\Weblog;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.history.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Peminjaman::query()
            ->withWhereHas(
                'buku_item',
                fn ($e) => $e->withWhereHas('buku'),
            )
            ->where('status', true)
            ->when($cari, function ($e, $cari) {
                $e->whereHas(
                    'buku_item',
                    fn ($e) => $e->where('kode', 'like', '%' . $cari . '%')->orWhere(function ($e) use ($cari) {
                        $e->whereHas('buku', fn ($e) => $e->where('judul', 'like', '%' . $cari . '%'));
                    })
                );
            })
            ->orderBy('id', 'desc');

        if ($request->filled('export')) {
            Weblog::set('Export data kelas');
            return Excel::download(new HistoryExport($data->get()), 'HISTORY.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('tanggal', fn ($e) => Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('YYYY-MM-DD'))
            ->addColumn('jam', fn ($e) => Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('HH:mm'))
            ->addColumn('kode_buku', fn ($e) => $e->buku_item->kode)
            ->addColumn('buku', fn ($e) => $e->buku_item->buku->judul)
            ->addColumn('batas_kembali', fn ($e) => Carbon::parse($e->batas_pengembalian)->timezone(zona_waktu())->isoFormat('YYYY-MM-DD'))
            ->editColumn('is_kembali', fn ($e) => $e->is_kembali ? '<span class="badge bg-success">dikembalikan</span>' : '<span class="badge bg-danger">dipinjam</span>')
            ->rawColumns(['is_kembali'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
