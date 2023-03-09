<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaExport;
use App\Facade\Weblog;
use App\Http\Requests\PasswordRequest;
use App\Models\Anggota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Maatwebsite\Excel\Facades\Excel;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Intervention\Image\Facades\Image;

class AnggotaController extends Controller
{

    private static $siswa   = 'siswa';

    public function __construct()
    {
        $this->middleware('permission:siswa-read')->only('index');
        $this->middleware('permission:siswa-create')->only(['create', 'store']);
        $this->middleware('permission:siswa-update')->only(['edit', 'update']);
        $this->middleware('permission:siswa-delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.siswa.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $kelas = $request->kelas;

        $data = Anggota::query()
            ->with(['kelas', 'kota'])
            ->when($kelas, function ($e, $kelas) {
                $e->where('kelas_id', $kelas);
            })
            ->when($cari, function ($e, $cari) {
                $e->where('nomor_induk', 'like', '%' . $cari . '%')->orWhere('nomor_anggota', 'like', '%' . $cari . '%')->orWhere('nama', 'like', '%' . $cari . '%');
            })
            ->where('status', $request->status)
            ->where('jabatan', self::$siswa)
            ->orderBy('id')
            ->get();

        if ($request->filled('export')) {
            Weblog::set('Export data siswa');
            return Excel::download(new AnggotaExport($data, $request->all()), 'SISWA.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('foto', function ($e) {
                $foto = ($e->foto === "" || $e->foto === null) ? '/storage/user/user_nw.png' : '/storage/anggota/thum_' . $e->foto;
                return '<div><img src="' . url($foto) . '" class="rounded" width="40"/></div>';
            })
            ->addColumn('anggota', function ($e) {
                $jenis_kelamin = $e->jenis_kelamin === 'L' ? '<span class="badge bg-danger">Laki-laki</span>' : '<span class="badge bg-warning">Perempuan</span>';
                $anggota = '<a href="#" class="btn-link btn-anggota"
                            data-id="'.$e->id.'"
                            data-anggota="'.$e->nomor_anggota.'"
                            data-induk="'.$e->nomor_anggota.'"
                            data-foto="'.$e->foto.'"
                            data-nama="'.$e->nama.'"
                            data-jenis_kelamin="'.strip_tags($jenis_kelamin).'"
                            data-kelas="'.$e->kelas->kelas.'"
                            data-ttl="'.$e->kota->kota.', '.Carbon::parse($e->tanggal_lahir)->isoFormat('DD MMMM YYYY').'"
                            data-alamat="'.$e->alamat.'">
                                <h2 style="font-size:.9rem; font-weight:bold; margin:0 0 2px 0; padding:0;">' . strtoupper($e->nama) . '</h2>
                                <h1 style="font-size:.8rem; margin:0; padding:0;">' . $e->nomor_anggota . ' | ' . $e->nomor_induk . '</h1>
                                <h3 style="font-size:.75rem; margin:4px 0 0 0; padding:0;">' . $jenis_kelamin . '</h3>
                            </a>';

                return $anggota;
            })
            ->addColumn('ttl', function ($e) {
                $anggota = '<h1 style="font-size:.8rem; margin:0; padding:0;">' . $e->kota->kota .  '</h1>
                            <h2 style="font-size:.8rem; margin:0; padding:0;">' . Carbon::parse($e->tanggal_lahir)->isoFormat('DD MMMM YYYY') . '</h2>';

                return $anggota;
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('siswa-update') ? '<a href="' . route('siswa.edit', ['siswa' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('siswa-delete') ?  '<a href="' . route('siswa.destroy', ['siswa' => $e->id]) . '" data-title="' . $e->nomor_anggota . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('siswa-update') ? '<a href="' . route('siswa.destroy', ['siswa' => $e->id]) . '" data-title="' . $e->nomor_anggota . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';
                $btnPassword = Laratrust::isAbleTo('siswa-update') ? '<button type="button"
                                                                                    class="btn btn-xs btn-open-ganti-password"
                                                                                    data-id="' . $e->id . '"
                                                                                    data-anggota="' . $e->nomor_anggota . '"
                                                                                    data-induk="' . $e->nomor_induk . '"
                                                                                    data-nama="' . $e->nama . '"
                                                                                    data-kelas="' . $e->kelas->kelas . '"
                                                                                    ><i class="bx bxs-key"></i></button>' : '';

                if ($e->status == true) {
                    return $btnPassword . ' ' . $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['foto', 'anggota', 'ttl', 'aksi'])
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
            'nomor_induk' => 'required|unique:anggotas,nomor_induk',
            'nomor_anggota' => 'required|unique:anggotas,nomor_anggota',
            'password' => 'nullable',
            'foto' => 'nullable',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'kota_id' => 'required',
            'tanggal_lahir' => 'required',
            'kelas_id' => 'required',
            'jabatan' => 'nullable',
            'alamat' => 'nullable',
            'coba' => 'required'
        ], [
            'kota_id.required' => 'Tempat lahir wajib diisi',
            'kelas_id.required' => 'Kelas wajib diisi'
        ]);

        $kode_anggota = $this->kode_anggota();

        return view('backend.siswa.create', compact('validator', 'kode_anggota'));
    }

    public function kode_anggota()
    {
        // nomor anggota
        // 2022KS0001
        // 2022 --- Tahun registrasi
        // KS/KG --- Kode Siswa / Guru
        // 0001 --- No. Urut siswa

        $data = Anggota::query()
            ->where('jabatan', self::$siswa)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->orderBy('id', 'desc')
            ->first();

        if ($data === null) {
            $kode_anggota = date('Y') . 'KS' . str_pad(1, 4, '0', STR_PAD_LEFT);

            return $kode_anggota;
        } else {

            $last_kode = $data->nomor_anggota;
            $start_kode = substr($last_kode, 0, 6);
            $end_kode = (int) substr($last_kode, 6);
            $next_kode = $end_kode + 1;

            $kode_anggota = $start_kode . str_pad($next_kode, 4, '0', STR_PAD_LEFT);

            return $kode_anggota;
        }
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
            'nomor_induk' => 'required|unique:anggotas,nomor_induk',
            'nomor_anggota' => 'required|unique:anggotas,nomor_anggota',
            'password' => 'nullable',
            'foto' => 'nullable',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'kota_id' => 'required',
            'tanggal_lahir' => 'required',
            'kelas_id' => 'required',
            'jabatan' => 'nullable',
            'alamat' => 'nullable',
        ], [
            'kota_id.required' => 'Tempat lahir wajib diisi',
            'kelas_id.required' => 'Kelas wajib diisi'
        ]);

        $request->merge([
            'jabatan' => self::$siswa,
            'password' => Hash::make('password'),
        ]);

        DB::beginTransaction();
        try {
            Anggota::create($request->except('proengsoft_jsvalidation'));
            DB::commit();

            Weblog::set('Menambahkan Anggota baru : ' . $request->nomor_anggota);
            return redirect(route('siswa.index'))->with([
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
    public function edit(Anggota $siswa)
    {
        $validator = JsValidatorFacade::make([
            'nomor_induk' => [
                'required',
                Rule::unique('anggotas')->ignore($siswa->id)
            ],
            'nomor_anggota' => [
                'required',
                Rule::unique('anggotas')->ignore($siswa->id)
            ],
            'password' => 'nullable',
            'foto' => 'nullable',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'kota_id' => 'required',
            'tanggal_lahir' => 'required',
            'kelas_id' => 'required',
            'jabatan' => 'nullable',
            'alamat' => 'nullable',
        ], [
            'kota_id.required' => 'Tempat lahir wajib diisi',
            'kelas_id.required' => 'Kelas wajib diisi'
        ]);

        return view('backend.siswa.edit', compact('validator', 'siswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anggota $siswa)
    {
        $request->validate([
            'nomor_induk' => [
                'required',
                Rule::unique('anggotas')->ignore($siswa->id)
            ],
            'nomor_anggota' => [
                'required',
                Rule::unique('anggotas')->ignore($siswa->id)
            ],
            'password' => 'nullable',
            'password' => 'nullable',
            'foto' => 'nullable',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'kota_id' => 'required',
            'tanggal_lahir' => 'required',
            'kelas_id' => 'required',
            'jabatan' => 'nullable',
            'alamat' => 'nullable',
        ], [
            'kota_id.required' => 'Tempat lahir wajib diisi',
            'kelas_id.required' => 'Kelas wajib diisi'
        ]);

        DB::beginTransaction();
        try {
            Anggota::where('id', $siswa->id)->update($request->except(['proengsoft_jsvalidation', '_token', '_method']));
            DB::commit();

            Weblog::set('Memperbarui Anggota : ' . $request->nomor_anggota);
            return redirect(route('siswa.index'))->with([
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


    public function ganti_password(PasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            Anggota::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
            DB::commit();
            Weblog::set('Ganti password anggota : ' . $request->nomor_anggota);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());
            return response()->json([
                'errors' => 'Terjadi kesalahan, cobalah kembali'
            ], 500);
        }
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anggota $siswa)
    {
        $status = $siswa->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Anggota::find($siswa->id)->update(['status' => false]);
                Weblog::set('Menghapus anggota : ' . $siswa->nomor_anggota);
            } else {
                Anggota::find($siswa->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan anggota : ' . $siswa->nomor_anggota);
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
            $thumPath = public_path('/storage/anggota') . '/thum_' . $foto;
            $thum = Image::make($thum)->save($thumPath);

            $request->file->storeAs('public/anggota', $foto);

            return response()->json([
                'file' => $foto,
            ]);
        }
    }
}
