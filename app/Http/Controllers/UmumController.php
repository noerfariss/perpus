<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\Umum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

use function PHPUnit\Framework\returnSelf;

class UmumController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:umum-read')->only('index');
        $this->middleware('permission:umum-create')->only(['create', 'store']);
        $this->middleware('permission:umum-update')->only(['edit', 'update']);
        $this->middleware('permission:umum-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param  \App\Models\Umum  $umum
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $pengaturan = Umum::first();

        return view('backend.umum.show', compact('pengaturan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Umum  $umum
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $umum = Umum::first();
        $validator = JsValidatorFacade::make([
            'nama' => 'required',
            'logo' => 'nullable',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'telpon' => 'nullable',
            'email' => 'nullable|email',
            'website' => 'nullable',
            'timezone' => 'required'
        ]);

        return view('backend.umum.edit', compact('umum', 'validator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Umum  $umum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $umum = Umum::first();
        $validasi = $request->validate([
            'nama' => 'required',
            'logo' => 'nullable',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'telpon' => 'nullable',
            'email' => 'nullable|email',
            'website' => 'nullable',
            'timezone' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($request->logo <> '') {
                Umum::find($umum->id)->update(['logo' => $request->logo]);
            }

            Umum::find($umum->id)->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'provinsi_id' => $request->provinsi,
                'kota_id' => $request->kota,
                'kecamatan_id' => $request->kecamatan,
                'telpon' => $request->telpon,
                'email' => $request->email,
                'website' => $request->website,
                'timezone' => $request->timezone,
            ]);

            DB::commit();
            Weblog::set('Menggganti pengaturan website');

            return redirect(route('umum.show'))->with([
                'pesan' => '<div class="alert alert-success">Pengaturan berhasil diperbarui</div>'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            Weblog::set('Ganti pengaturan gagal');
            Log::info($th->getMessage());
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah beberapa saat lagi</div>'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Umum  $umum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Umum $umum)
    {
        //
    }

    public function peminjaman()
    {
        $pengaturan = Umum::first();

        return view('backend.umum.peminjaman', compact('pengaturan'));
    }

    public function editPinjam(Request $request)
    {
        $validasi = [
            'batas_pengembalian' => 'required|numeric',
            'batas_peminjaman' => 'required|numeric',
            'denda' => 'required|numeric'
        ];

        if ($request->method() === 'PUT') {
            DB::beginTransaction();
            try {
                Umum::where('id', $request->peminjaman)->update($request->only(['batas_pengembalian', 'batas_peminjaman', 'denda']));
                Weblog::set('Mempebarui pengaturan peminjaman buku');
                DB::commit();
                return redirect(route('umum.peminjaman'))->with([
                    'pesan' => '<div class="alert alert-success">Pengaturan berhasil diperbarui</div>'
                ]);
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                DB::rollBack();
                return redirect()->back()->with([
                    'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, silahkan coba kembali</div>'
                ]);
            }
        }

        $pengaturan = Umum::first();
        $validator = JsValidatorFacade::make($validasi, [
            'batas_pengembalian.required' => 'Durasi peminjaman wajib diisi',
            'batas_pengembalian.numeric' => 'Durasi peminjaman harus berupa Angka',
        ]);

        return view('backend.umum.peminjamanEdit', compact('pengaturan', 'validator'));
    }
}
