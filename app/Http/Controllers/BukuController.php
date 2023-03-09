<?php

namespace App\Http\Controllers;

use App\Exports\BukuExport;
use App\Facade\Weblog;
use App\Models\Buku;
use App\Models\BukuItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Intervention\Image\Facades\Image;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.buku.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $kategori = $request->kategori;

        $data = Buku::query()
            ->with(['kategori', 'penerbit'])
            ->when($kategori, function ($e, $kategori) {
                $e->whereHas('kategori', function ($e) use ($kategori) {
                    $e->where('id', $kategori);
                });
            })
            ->when($cari, function ($e, $cari) {
                $e->where('judul', 'like', '%' . $cari . '%')->orWhere('pengarang', 'like', '%' . $cari . '%')->orWhere('isbn', 'like', '%' . $cari . '%');
            })
            ->where('status', $request->status)
            ->orderBy('judul')
            ->get();

        if ($request->filled('export')) {
            Weblog::set('Export data kelas');
            return Excel::download(new BukuExport($data), 'BUKU.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('foto', function ($e) {
                $foto = ($e->foto === "" || $e->foto === null) ? '/storage/user/coverbook.jpg' : '/storage/buku/thum_' . $e->foto;
                return '<div><img src="' . url($foto) . '" class="img-thumbnail"/></div>';
            })
            ->editColumn('judul', function ($e) {
                $judul = '<a href="#" >
                            <h1 style="font-size:.9rem; font-weight:bold; margin:0; padding:0 0 4px 0; ">' . ucfirst($e->judul) . '</h1>
                            <h2 style="font-size:.8rem; font-weight:normal; margin:0; padding:0 0 4px 0;">Pengarang : ' . $e->pengarang . '</h2>
                            <h2 style="font-size:.8rem; font-weight:normal; margin:0; padding:0 0 4px 0;">Penerbit : ' . $e->penerbit->penerbit . '</h2>
                            <h2 style="font-size:.8rem; font-weight:normal; margin:0; padding:0 0 4px 0;">ISBN : ' . $e->isbn . '</h2>
                          </a>';
                return $judul;
            })
            ->editColumn('kategori', function ($e) {
                return $e->kategori->implode('kategori', '<br>');
            })
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY HH:mm');
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('buku-update') ? '<a href="' . route('buku.edit', ['buku' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('buku-delete') ?  '<a href="' . route('buku.destroy', ['buku' => $e->id]) . '" data-title="' . $e->judul . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('buku-update') ? '<a href="' . route('buku.destroy', ['buku' => $e->id]) . '" data-title="' . $e->judul . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

                if ($e->status == true) {
                    return $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['foto', 'kategori', 'judul', 'aksi'])
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
            'judul' => 'required|unique:bukus,judul',
            'pengarang' => 'nullable',
            'isbn' => 'required',
            'stok' => 'required|numeric|min:1',
            'kategori_id' => 'required',
            'penerbit_id' => 'required',
            'foto' => 'nullable',
        ], [
            'kategori_id.required' => 'Kategori wajib diisi',
            'penerbit_id.required' => 'Penerbit wajib diisi',
        ]);

        $kode = getKodePenerbit();
        $kode_kategori = getKodeKategori();

        return view('backend.buku.create', compact('validator', 'kode', 'kode_kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'judul' => 'required|unique:bukus,judul',
            'pengarang' => 'nullable',
            'isbn' => 'required',
            'stok' => 'required|numeric|min:1',
            'kategori_id' => 'required',
            'penerbit_id' => 'required',
            'foto' => 'nullable',
        ], [
            'kategori_id.required' => 'Kategori wajib diisi',
            'penerbit_id.required' => 'Penerbit wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            // input buku
            $buku = Buku::create($request->except(['kategori_id', 'proengsoft_jsvalidation']));

            // kategori buku
            $kategori_items = [];
            foreach ($request->kategori_id as $item) {
                $kategori_items[] = [
                    'buku_id' => $buku->id,
                    'kategori_id' => $item,
                ];
            }

            DB::table('buku_kategori')->insert($kategori_items);

            // input buku item
            $stok = $request->stok;
            $buku_items = [];
            $kode = getKodeBuku();
            $newKode = (int) substr($kode, 2);

            for ($i = 0; $i <= $stok; $i++) {
                $buku_items[] = [
                    'buku_id' => $buku->id,
                    'kode' => 'BK' . str_pad($newKode++, 5, '0', STR_PAD_LEFT),
                    'created_at' => Carbon::now(),
                ];
            }

            BukuItem::insert($buku_items);
            DB::commit();

            Weblog::set('Menambahkan Buku ' . $request->judul);
            return redirect(route('buku.index'))->with([
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
     * @param  \App\Models\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function edit(Buku $buku)
    {
        $validator = JsValidatorFacade::make([
            'judul' => [
                'required',
                Rule::unique('bukus')->ignore($buku->id),
            ],
            'pengarang' => 'nullable',
            'isbn' => 'required',
            'kategori_id' => 'required',
            'penerbit_id' => 'required',
            'foto' => 'nullable',
        ], [
            'kategori_id.required' => 'Kategori wajib diisi',
            'penerbit_id.required' => 'Penerbit wajib diisi',
        ]);

        $kode = getKodePenerbit();
        $kode_kategori = getKodeKategori();


        return view('backend.buku.edit', compact('validator', 'kode', 'kode_kategori', 'buku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => [
                'required',
                Rule::unique('bukus')->ignore($buku->id),
            ],
            'pengarang' => 'nullable',
            'isbn' => 'required',
            'kategori_id' => 'required',
            'penerbit_id' => 'required',
            'foto' => 'nullable',
        ], [
            'kategori_id.required' => 'Kategori wajib diisi',
            'penerbit_id.required' => 'Penerbit wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            Buku::where('id', $buku->id)->update($request->except(['proengsoft_jsvalidation', '_token', '_method', 'kategori_id']));

            // update kategori
            DB::table('buku_kategori')->where('buku_id', $buku->id)->delete();
            $kategori_items = [];
            foreach ($request->kategori_id as $item) {
                $kategori_items[] = [
                    'buku_id' => $buku->id,
                    'kategori_id' => $item,
                ];
            }
            DB::table('buku_kategori')->insert($kategori_items);

            DB::commit();

            Weblog::set('Memperbarui Buku : ' . $request->judul);
            return redirect(route('buku.index'))->with([
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
     * @param  \App\Models\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buku $buku)
    {
        $status = $buku->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Buku::find($buku->id)->update(['status' => false]);
                Weblog::set('Menghapus buku : ' . $buku->judul);
            } else {
                Buku::find($buku->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan buku : ' . $buku->judul);
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

    public function ganti_foto(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $request->validate([
                'file' => 'required|image|max:2000'
            ]);

            $name = time();
            $ext  = $file->getClientOriginalExtension();
            $foto = $name . '.' . $ext;

            $path = $file->getRealPath();
            $thum = Image::make($path)->resize(80, 80, function ($size) {
                $size->aspectRatio();
            });
            $thumPath = public_path('/storage/buku') . '/thum_' . $foto;
            $thum = Image::make($thum)->save($thumPath);

            $request->file->storeAs('public/buku', $foto);

            return response()->json([
                'file' => $foto,
            ]);
        }
    }
}
