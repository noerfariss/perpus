<?php

namespace App\Http\Controllers;

use App\Exports\JabatanExport;
use App\Facade\Weblog;
use App\Models\Jabatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:jabatan-read')->only('index');
        $this->middleware('permission:jabatan-create')->only(['create', 'store']);
        $this->middleware('permission:jabatan-update')->only(['edit', 'update']);
        $this->middleware('permission:jabatan-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.jabatan.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $data = Jabatan::query()
            ->when($cari, function ($e, $cari) {
                $e->where('jabatan', 'like', '%' . $cari . '%')->orWhere('kode', 'like', '%' . $cari . '%');
            })
            ->where('status', $request->status)
            ->orderBy('kode')
            ->get();

        if ($request->filled('export')) {
            return Excel::download(new JabatanExport($data), 'JABATAN.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY HH:mm');
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('jabatan-update') ? '<a href="' . route('jabatan.edit', ['jabatan' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('jabatan-delete') ?  '<a href="' . route('jabatan.destroy', ['jabatan' => $e->id]) . '" data-title="' . $e->jabatan . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('jabatan-update') ? '<a href="' . route('jabatan.destroy', ['jabatan' => $e->id]) . '" data-title="' . $e->jabatan . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

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
            'jabatan' => 'required|unique:jabatans,jabatan',
            'jabatan.*' => 'required|distinct',
        ]);

        return view('backend.jabatan.create', compact('validator'));
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
            'jabatan' => 'required|unique:jabatans,jabatan',
            'jabatan.*' => 'nullable|distinct',
        ]);

        $jabatan_arr = [];
        foreach ($request->jabatan as $item) {
            if ($item <> '') {
                $jabatan_arr[] = $item;
            }
        }

        DB::beginTransaction();
        try {
            $jabatanes = [];
            $kode = Jabatan::orderby('id', 'desc')->first();

            if ($kode === NULL) {
                $i = 1;
                $jabatanes = $this->input_item($jabatan_arr, $i);
            } else {
                $last_kode = (int) substr($kode->kode, 2);
                $last_kode = $last_kode + 1;

                $jabatanes = $this->input_item($jabatan_arr, $last_kode);
            }

            Jabatan::insert($jabatanes);
            DB::commit();

            Weblog::set('Menambahkan Jabatan');
            return redirect(route('jabatan.index'))->with([
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

    private function input_item($jabatan_arr, $nomor)
    {
        foreach ($jabatan_arr as $item) {
            $jabatanes[] = [
                'kode' => 'KJ' . str_pad($nomor++, 4, '0', STR_PAD_LEFT),
                'jabatan' => $item,
                'created_at' => Carbon::now(),
            ];
        }

        return $jabatanes;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Jabatan $jabatan)
    {
        $validator = JsValidatorFacade::make([
            'jabatan' => 'required|unique:jabatans, jabatan, id' . $jabatan->id,
        ]);

        return view('backend.jabatan.edit', compact('validator', 'jabatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $validasi = $request->validate([
            'jabatan' => 'required|unique:jabatans,jabatan,id' . $jabatan->id,
        ]);

        DB::beginTransaction();
        try {
            Jabatan::find($jabatan->id)->update($validasi);
            DB::commit();

            Weblog::set('Edit Jabatan : ' . $request->jabatan);
            return redirect(route('jabatan.index'))->with([
                'pesan' => '<div class="alert alert-success">Jabatan berhasil diperbarui</div>',
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
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jabatan $jabatan)
    {
        $status = $jabatan->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Jabatan::find($jabatan->id)->update(['status' => false]);
                Weblog::set('Menghapus Jabatan : ' . $jabatan->jabatan);
            } else {
                Jabatan::find($jabatan->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan Jabatan : ' . $jabatan->jabatan);
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
