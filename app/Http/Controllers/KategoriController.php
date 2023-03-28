<?php

namespace App\Http\Controllers;

use App\Exports\KategoriExport;
use App\Facade\Weblog;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kategori-read')->only('index');
        $this->middleware('permission:kategori-create')->only(['create', 'store']);
        $this->middleware('permission:kategori-update')->only(['edit', 'update']);
        $this->middleware('permission:kategori-delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.kategori.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $data = Kategori::query()
            ->when($cari, function ($e, $cari) {
                $e->where('kategori', 'like', '%' . $cari . '%')->orWhere('kode', 'like', '%' . $cari . '%');
            })
            ->where('status', $request->status)
            ->orderBy('kode')
            ->get();

        if ($request->filled('export')) {
            Weblog::set('Export data kategori');
            return Excel::download(new KategoriExport($data), 'KATEGORI.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY HH:mm');
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('kategori-update') ? '<a href="' . route('kategori.edit', ['kategori' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('kategori-delete') ?  '<a href="' . route('kategori.destroy', ['kategori' => $e->id]) . '" data-title="' . $e->kategori . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('kategori-update') ? '<a href="' . route('kategori.destroy', ['kategori' => $e->id]) . '" data-title="' . $e->kategori . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

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
            'kategori' => [
                'required',
                Rule::unique('kategoris','kategori'),
            ],
            'kategori.*' => 'required|distinct',
        ]);

        return view('backend.kategori.create', compact('validator'));
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
                'kode' => 'required|unique:kategoris,kode',
                'kategori' => [
                    'required',
                    Rule::unique('kategoris'),
                ],
            ]);

            DB::beginTransaction();
            try {
                Kategori::create($request->except(['_token', 'tipe']));
                DB::commit();
                Weblog::set('Menambahkan Kategori baru : ' . $request->kategori);

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
            'kategori' => 'required|unique:kategoris,kategori',
            'kategori.*' => 'nullable|distinct',
        ]);

        $kategori_arr = [];
        foreach ($request->kategori as $item) {
            if ($item <> '') {
                $kategori_arr[] = $item;
            }
        }

        DB::beginTransaction();
        try {
            $kategories = [];
            $kode = Kategori::orderby('id', 'desc')->first();

            if ($kode === NULL) {
                $i = 1;
                $kategories = $this->input_item($kategori_arr, $i);
            } else {
                $last_kode = (int) substr($kode->kode, 2);
                $last_kode = $last_kode + 1;

                $kategories = $this->input_item($kategori_arr, $last_kode);
            }

            Kategori::insert($kategories);
            DB::commit();

            Weblog::set('Menambahkan kategori');
            return redirect(route('kategori.index'))->with([
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
                'kode' => 'KG' . str_pad($nomor++, 4, '0', STR_PAD_LEFT),
                'kategori' => $item,
                'created_at' => Carbon::now(),
            ];
        }

        return $kategories;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Kategori $kategori)
    {
        $validator = JsValidatorFacade::make([
            'kategori' => [
                'required',
                Rule::unique('kategoris','kategori')->ignore($kategori->id),
            ]
        ]);

        return view('backend.kategori.edit', compact('validator', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validasi = $request->validate([
            'kategori' => [
                'required',
                Rule::unique('kategoris','kategori')->ignore($kategori->id),
            ]
        ]);

        DB::beginTransaction();
        try {
            Kategori::find($kategori->id)->update($validasi);
            DB::commit();

            Weblog::set('Edit Kategori : ' . $request->kategori);
            return redirect(route('kategori.index'))->with([
                'pesan' => '<div class="alert alert-success">Kategori berhasil diperbarui</div>',
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
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        $status = $kategori->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Kategori::find($kategori->id)->update(['status' => false]);
                Weblog::set('Menghapus kategori : ' . $kategori->kategori);
            } else {
                Kategori::find($kategori->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan kategori : ' . $kategori->kategori);
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
