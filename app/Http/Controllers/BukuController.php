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
use Illuminate\Support\Str;

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
            ->with([
                'kategori',
                'penerbit',
                'buku_item' => fn ($e) => $e->has('peminjaman_belum_kembali')->where('status', true),
            ])
            ->withCount([
                'buku_item' => fn ($e) => $e->where('status', true),
            ])
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
            ->addColumn('dipinjam', function ($e) {
                return (count($e->buku_item));
            })
            ->editColumn('foto', function ($e) {
                $foto = ($e->foto === "" || $e->foto === null) ? '/storage/user/coverbook.jpg' : '/storage/buku/thum_' . $e->foto;
                return '<div><img src="' . url($foto) . '" class="img-thumbnail"/></div>';
            })
            ->editColumn('judul', function ($e) {
                $judul = '<a href="#" class="detail-anggota" data-id="' . $e->id . '" data-judul="' . $e->judul . '" data-kategori="' . $e->kategori->implode('kategori', ', ') . '" data-pengarang="' . $e->pengarang . '" data-isbn="' . $e->isbn . '">
                            <div style="width:400px; word-wrap: break-word;"><h1 style="font-size:.9rem; font-weight:bold; margin:0; padding:0 0 4px 0;">' . ucfirst($e->judul) . '</h1></div>
                            <h2 style="font-size:.8rem; font-weight:normal; margin:0; padding:0 0 4px 0;">ISBN : ' . $e->isbn . '</h2>
                            <h2 style="font-size:.8rem; font-weight:normal; margin:0; padding:0 0 4px 0;">Pengarang : ' . Str::limit($e->pengarang, 20) . '</h2>
                            <h2 style="font-size:.8rem; font-weight:normal; margin:0; padding:0 0 4px 0;">Penerbit : ' . $e->penerbit->penerbit . '</h2>
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
        $request->validate([
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

            for ($i = 0; $i < $stok; $i++) {
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
        $data = BukuItem::query()
            ->withCount('peminjaman_belum_kembali')
            ->where('buku_id', $buku->id)
            ->where('status', true)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('kode', function ($e) {
                return $e->peminjaman_belum_kembali_count === 1 ? '<span style="color:#999">' . $e->kode . '</span>' : $e->kode;
            })
            ->addColumn('status', function ($e) {
                return $e->peminjaman_belum_kembali_count === 1 ? '<span class="badge bg-secondary">dipinjam</span>' : '<span class="badge bg-success">tersedia</span>';
            })
            ->rawColumns(['kode', 'status'])
            ->make(true);
    }

    public function hapus_item(Request $request)
    {
        DB::beginTransaction();
        try {
            $items_id = [];
            $buku_id = '';
            $items = BukuItem::doesntHave('peminjaman_belum_kembali')
                ->whereIn('id', $request->items);

            if ($items->count() === 0) {
                return response()->json([
                    'message' => 'Data tidak tersedia',
                    'status' => false,
                    'data' => []
                ]);
            }

            foreach ($items->get() as $row) {
                $items_id[] = $row->id;
                $buku_id = $row->buku_id;
            }

            BukuItem::whereIn('id', $items_id)->update(['status' => false]);

            $stok = BukuItem::where('buku_id', $buku_id)->where('status', true)->count();
            Buku::where('id', $buku_id)->update(['stok' => $stok]);
            Weblog::set('Menghapus stok');
            DB::commit();

            return response()->json([
                'message' => 'Kode buku berhasil dihapus',
                'status' => true,
                'data' => [
                    'stok' => $stok,
                ],
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan, cobalah kembali',
                'status' => false,
                'data' => []
            ], 500);
        }
    }

    public function tambah_stok(Request $request)
    {
        $request->validate([
            'stok_new' => 'required|min:0|not_in:0|numeric',
        ]);

        DB::beginTransaction();
        try {
            $buku = Buku::where('id', $request->s_buku_id)->first();
            $stok_sekarang = $buku->stok;
            $judul = $buku->judul;

            Buku::where('id', $request->s_buku_id)->update(['stok' => $stok_sekarang + $request->stok_new]);

            $buku_items = [];
            $kode = getKodeBuku();
            $newKode = (int) substr($kode, 2);

            for ($i = 0; $i < $request->stok_new; $i++) {
                $buku_items[] = [
                    'buku_id' => $request->s_buku_id,
                    'kode' => 'BK' . str_pad($newKode++, 5, '0', STR_PAD_LEFT),
                    'created_at' => Carbon::now(),
                ];
            }
            BukuItem::insert($buku_items);

            DB::commit();

            Weblog::set('Menambahkan stok ' . $request->stok_new . ' di buku ' . $judul);
            return response()->json([
                'message' => 'Stok berhasil ditambahkan',
                'status' => true,
                'data' => [
                    'new_stok' => $stok_sekarang + $request->stok_new,
                ]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
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

    public function getKodeKategori()
    {
        return response()->json([
            'success' => true,
            'data' => getKodeKategori(),
        ]);
    }

    public function getKodePenerbit()
    {
        return response()->json([
            'success' => true,
            'data' => getKodePenerbit(),
        ]);
    }
}
