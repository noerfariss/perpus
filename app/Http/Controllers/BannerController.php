<?php

namespace App\Http\Controllers;

use App\Exports\BannerExport;
use App\Facade\Weblog;
use App\Models\Anggota;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Maatwebsite\Excel\Facades\Excel;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:banner-read')->only('index');
        $this->middleware('permission:banner-create')->only(['create', 'store']);
        $this->middleware('permission:banner-update')->only(['edit', 'update']);
        $this->middleware('permission:banner-delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.banner.index');
    }

    public function ajax(Request $request)
    {
        $data = Banner::query()
            ->where('status', $request->status)
            ->orderBy('id');

        if ($request->filled('export')) {
            Weblog::set('Export data banner');
            return Excel::download(new BannerExport($data->get(), $request->all()), 'BANNER.xlsx');
        }

        return DataTables::eloquent($data)
            ->editColumn('gambar', function ($e) {
                if($e->gambar){
                    return '<img src="'.base_url($e->gambar).'" class="img-fluid">';
                }else{
                    return '-';
                }
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('banner-update') ? '<a href="' . route('banner.edit', ['banner' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('banner-delete') ?  '<a href="' . route('banner.destroy', ['banner' => $e->id]) . '" data-title="' . $e->gambar . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('banner-update') ? '<a href="' . route('banner.destroy', ['banner' => $e->id]) . '" data-title="' . $e->gambar . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

                if ($e->status == true) {
                    return $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['gambar', 'aksi'])
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
            'gambar' => 'required',
            'keterangan' => 'nullable'
        ]);

        return view('backend.banner.create', compact('validator'));
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
            'gambar' => 'required',
            'keterangan' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            Banner::create($request->except('proengsoft_jsvalidation'));
            DB::commit();

            Weblog::set('Menambahkan banner baru : ' . $request->gambar);
            return redirect(route('banner.index'))->with([
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function show(Anggota $anggota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $validator = JsValidatorFacade::make([
            'gambar' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        return view('backend.banner.edit', compact('validator', 'banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'gambar' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            Banner::where('id', $banner->id)->update($request->except(['proengsoft_jsvalidation', '_token', '_method']));
            DB::commit();

            Weblog::set('Memperbarui Banner : ' . $request->gambar);
            return redirect(route('banner.index'))->with([
                'pesan' => '<div class="alert alert-success">Data berhasil diperbarui</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $status = $banner->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Banner::find($banner->id)->update(['status' => false]);
                Weblog::set('Menghapus anggota : ' . $banner->gambar);
            } else {
                Banner::find($banner->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan anggota : ' . $banner->gambar);
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
