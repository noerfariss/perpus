<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Umum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $user = Anggota::with([
                'kelas',
                'kota'
            ])
                ->where('id', Auth::id())
                ->first();

            $foto = $user->foto;
            if ($foto) {
                $user['foto'] = base_url($foto);
            } else {
                $user['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
            }

            return $this->responOk('Data berhasil ditemukan', $user);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError(kode: 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'kota_id' => 'required',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'nullable',
            'alamat' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->responError('Terjadi kesalahan', $validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            Anggota::where('id', Auth::id())->update([
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'kota_id' => $request->kota_id,
                'tanggal_lahir' => $request->tanggal_lahir,
                'kelas_id' => $request->kelas_id ? $request->kelas_id : NULL,
                'alamat' => $request->alamat,
            ]);
            DB::commit();

            $user = Anggota::with([
                'kelas',
                'kota'
            ])
                ->where('id', Auth::id())->first();

            $foto = $user->foto;
            if ($foto) {
                $user['foto'] = base_url($foto);
            } else {
                $user['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
            }

            return $this->responOk(data: $user);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_lama' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return $this->responError(data: $validator->errors(), kode: 422);
        }

        if (Hash::check($request->password_lama, Auth::user()->password)) {

            DB::beginTransaction();
            try {
                Anggota::where('id', Auth::id())->update(['password' => Hash::make($request->password)]);
                DB::commit();

                return $this->responOk();
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                DB::rollBack();

                return $this->responError('Terjadi kesalahan, cobalah kembali', kode: 422);
            }
        } else {
            $error = [
                'password_lama' => [
                    'Password lama Anda salah!'
                ],
            ];
            return $this->responError('Password lama Anda salah', data: $error,  kode: 422);
        }
    }

    public function ganti_foto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responError(data: $validator->errors(), kode: 422);
        }

        DB::beginTransaction();
        try {
            // Simpan gambar
            $image_64 = $request->foto;
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = time() . '.' . $extension;
                // Storage::disk('public')->put('storage/export/' . $imageName, base64_decode($image));

                // Simpan versi thumb_
                $path = base64_decode($image);
                $thum = Image::make($path)->resize(120, 120, function ($size) {
                    $size->aspectRatio();
                });

                $fullPath = 'anggota/' . $imageName;
                $path = Storage::put($fullPath, $thum->stream());

                // Simpan gambar ke Database
                Anggota::where('id', Auth::id())->update(['foto' => $fullPath]);
                $data = Anggota::query()
                    ->with([
                        'kelas',
                        'kota'
                    ])
                    ->where('id', Auth::id())->first();

                $foto = $data->foto;
                if ($foto) {
                    $data['foto'] = base_url($foto);
                } else {
                    $data['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
                }

                DB::commit();
                return $this->responOk(data: $data);
            } else {
                $error = [
                    'foto' => [
                        'Extensi tidak diizinkan!'
                    ],
                ];

                return $this->responError(data: $error);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function kartu_anggota()
    {
        $anggota = Anggota::with(['kelas', 'kota'])->where('nomor_anggota', Auth::user()->nomor_anggota)->first();
        if ($anggota === null) {
            return $this->responError('Data tidak ditemukan', kode: 404);
        }

        $foto = $anggota->foto;
        if ($foto) {
            $anggota['foto'] = base_url($foto);
        } else {
            $anggota['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
        }

        if (env('FILESYSTEM_DISK') === 's3') {
            if ($foto) {
                $url = base_url($foto);
                $contents = file_get_contents($url);
                $name = substr($url, strrpos($url, '/') + 1);
                Storage::disk('public')->put('export/' . $name, $contents);
                $anggota['namafoto'] = $name;
            }
        } else {
            $anggota['namafoto'] = $foto;
        }

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($anggota->nomor_anggota, $generator::TYPE_CODE_128, widthFactor: 1,  height: 40)) . '">';

        $sekolah = Umum::with(['provinsi', 'kota'])->first();
        $pdf = Pdf::loadView('backend.siswa.kartu', compact('sekolah', 'anggota', 'barcode'));
        $pdf->set_paper([0, 0, 243.78, 158.74]); // -------- ukuran ID CARD 8.6cm * 5.6cm

        $konten = $pdf->download()->getOriginalContent();
        Storage::put('kartu/' . Auth::user()->nomor_anggota . '.pdf', $konten);

        $collect = collect($anggota);
        $collect->put('kartu', base_url('kartu' . '/' . Auth::user()->nomor_anggota . '.pdf'));

        return $this->responOk(data: $collect);
    }

    public function sekolah()
    {
        try {
            $data = Umum::with(['kecamatan', 'kota', 'provinsi'])->first();
            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError();
        }
    }
}
