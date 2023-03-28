<?php

namespace App\Http\Controllers;

use App\Exports\PenerbitExport;
use App\Facade\Weblog;
use App\Models\Penerbit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class PenerbitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:penerbit-read')->only('index');
        $this->middleware('permission:penerbit-create')->only(['create', 'store']);
        $this->middleware('permission:penerbit-update')->only(['edit', 'update']);
        $this->middleware('permission:penerbit-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.penerbit.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $data = Penerbit::query()
            ->when($cari, function ($e, $cari) {
                $e->where('penerbit', 'like', '%' . $cari . '%')->orWhere('kode', 'like', '%' . $cari . '%');
            })
            ->where('status', $request->status)
            ->orderBy('kode')
            ->get();

        if ($request->filled('export')) {
            Weblog::set('Export data penerbit');
            return Excel::download(new PenerbitExport($data), 'PENERBIT.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY HH:mm');
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('penerbit-update') ? '<a href="' . route('penerbit.edit', ['penerbit' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('penerbit-delete') ?  '<a href="' . route('penerbit.destroy', ['penerbit' => $e->id]) . '" data-title="' . $e->penerbit . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('penerbit-update') ? '<a href="' . route('penerbit.destroy', ['penerbit' => $e->id]) . '" data-title="' . $e->penerbit . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

                if ($e->status == true) {
                    return $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $validator = JsValidatorFacade::make([
            'penerbit' => [
                'required',
                Rule::unique('penerbits', 'penerbit'),
            ],
            'penerbit.*' => 'required|distinct',
        ]);

        return view('backend.penerbit.create', compact('validator'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->exists('tipe')) {
            $request->validate([
                'kode' => 'required|unique:penerbits,kode',
                'penerbit' => [
                    'required',
                    Rule::unique('penerbits', 'penerbit'),
                ]
            ]);

            DB::beginTransaction();
            try {
                Penerbit::create($request->except(['_token', 'tipe']));
                DB::commit();
                Weblog::set('Menambahkan Penerbit baru : ' . $request->penerbit);

                return response()->json([
                    'message' => 'Data berhasil diinputkan'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::warning($th->getMessage());
                return redirect()->back()->with([
                    'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
                ]);
            }
        }

        $request->validate([
            'penerbit' => 'required|unique:penerbits,penerbit',
            'penerbit.*' => 'nullable|distinct',
        ]);

        $kategori_arr = [];
        foreach ($request->penerbit as $item) {
            if ($item <> '') {
                $kategori_arr[] = $item;
            }
        }

        DB::beginTransaction();
        try {
            $kategories = [];
            $kode = Penerbit::orderby('id', 'desc')->first();

            if ($kode === NULL) {
                $i = 1;
                $kategories = $this->input_item($kategori_arr, $i);
            } else {
                $last_kode = (int) substr($kode->kode, 2);
                $last_kode = $last_kode + 1;

                $kategories = $this->input_item($kategori_arr, $last_kode);
            }

            Penerbit::insert($kategories);
            DB::commit();

            Weblog::set('Menambahkan penerbit');
            return redirect(route('penerbit.index'))->with([
                'pesan' => '<div class="alert alert-success">Data berhasil ditambahkan</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::warning($th->getMessage());
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    private function input_item($kategori_arr, $nomor)
    {
        foreach ($kategori_arr as $item) {
            $kategories[] = [
                'kode' => 'KP' . str_pad($nomor++, 4, '0', STR_PAD_LEFT),
                'penerbit' => $item,
                'created_at' => Carbon::now(),
            ];
        }

        return $kategories;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function show(Penerbit $penerbit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function edit(Penerbit $penerbit)
    {
        $validator = JsValidatorFacade::make([
            'kode' => 'required',
            'penerbit' => [
                'required',
                Rule::unique('penerbits', 'penerbit')->ignore($penerbit->id),
            ],
        ]);

        return view('backend.penerbit.edit', compact('validator', 'penerbit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penerbit $penerbit)
    {
        $validasi = $request->validate([
            'penerbit' => [
                'required',
                Rule::unique('penerbits', 'penerbit')->ignore($penerbit->id),
            ],
        ]);

        DB::beginTransaction();
        try {
            Penerbit::find($penerbit->id)->update($validasi);
            DB::commit();

            Weblog::set('Edit penerbit : ' . $request->penerbit);
            return redirect(route('penerbit.index'))->with([
                'pesan' => '<div class="alert alert-success">penerbit berhasil diperbarui</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penerbit $penerbit)
    {
        $status = $penerbit->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Penerbit::find($penerbit->id)->update(['status' => false]);
                Weblog::set('Menghapus penerbit : ' . $penerbit->penerbit);
            } else {
                Penerbit::find($penerbit->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan penerbit : ' . $penerbit->penerbit);
            }

            DB::commit();

            return response()->json([
                'pesan' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Weblog::set($th->getMessage());
            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }
}
