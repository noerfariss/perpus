<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Facade\Weblog;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Maatwebsite\Excel\Facades\Excel;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelas-read')->only('index');
        $this->middleware('permission:kelas-create')->only(['create', 'store']);
        $this->middleware('permission:kelas-update')->only(['edit', 'update']);
        $this->middleware('permission:kelas-delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.kelas.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $data = Kelas::query()
            ->when($cari, function ($e, $cari) {
                $e->where('kelas', 'like', '%' . $cari . '%')->orWhere('kode', 'like', '%' . $cari . '%');
            })
            ->where('status', $request->status)
            ->orderBy('kode')
            ->get();

        if ($request->filled('export')) {
            return Excel::download(new KelasExport($data), 'KELAS.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY HH:mm');
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('kelas-update') ? '<a href="' . route('kelas.edit', ['kela' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('kelas-delete') ?  '<a href="' . route('kelas.destroy', ['kela' => $e->id]) . '" data-title="' . $e->kelas . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('kelas-update') ? '<a href="' . route('kelas.destroy', ['kela' => $e->id]) . '" data-title="' . $e->kelas . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

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
            'kelas' => 'required|unique:kelas,kelas',
            'kelas.*' => 'required|distinct',
        ]);

        return view('backend.kelas.create', compact('validator'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|unique:kelas,kelas',
            'kelas.*' => 'nullable|distinct',
        ]);

        $kelas_arr = [];
        foreach ($request->kelas as $item) {
            if ($item <> '') {
                $kelas_arr[] = $item;
            }
        }

        DB::beginTransaction();
        try {
            $kelases = [];
            $kode = Kelas::orderby('id', 'desc')->first();

            if ($kode === NULL) {
                $i = 1;
                $kelases = $this->input_item($kelas_arr, $i);
            } else {
                $last_kode = (int) substr($kode->kode, 2);
                $last_kode = $last_kode + 1;

                $kelases = $this->input_item($kelas_arr, $last_kode);
            }

            Kelas::insert($kelases);
            DB::commit();

            Weblog::set('Menambahkan kelas');
            return redirect(route('kelas.index'))->with([
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

    private function input_item($kelas_arr, $nomor)
    {
        foreach ($kelas_arr as $item) {
            $kelases[] = [
                'kode' => 'KK' . str_pad($nomor++, 4, '0', STR_PAD_LEFT),
                'kelas' => $item,
                'created_at' => Carbon::now(),
            ];
        }

        return $kelases;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function edit(Kelas $kela)
    {
        $validator = JsValidatorFacade::make([
            'kelas' => 'required|unique:kelas, kelas, id' . $kela->id,
        ]);

        return view('backend.kelas.edit', compact('validator', 'kela'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kelas $kela)
    {
        $validasi = $request->validate([
            'kelas' => 'required|unique:kelas,kelas,id' . $kela->id,
        ]);

        DB::beginTransaction();
        try {
            Kelas::find($kela->id)->update($validasi);
            DB::commit();

            Weblog::set('Edit Kelas : ' . $request->kelas);
            return redirect(route('kelas.index'))->with([
                'pesan' => '<div class="alert alert-success">Kelas berhasil diperbarui</div>',
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
     * @param  \App\Models\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kelas $kela)
    {
        $status = $kela->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Kelas::find($kela->id)->update(['status' => false]);
                Weblog::set('Menghapus kelas : ' . $kela->kelas);
            } else {
                Kelas::find($kela->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan kelas : ' . $kela->kelas);
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
