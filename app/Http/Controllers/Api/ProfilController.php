<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
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
                ->select('*')
                ->addSelect(DB::raw('case when foto is null or foto = "" then "' . url('/storage/foto/pasfoto.jpg') . '" else concat("' . url('/storage/anggota') . '","/", foto) end as foto'))
                ->first();
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
            Anggota::where('id', Auth::id())->update($request->all());
            DB::commit();

            $user = Anggota::with([
                'kelas',
                'kota'
            ])
                ->where('id', Auth::id())->first();

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
                Storage::disk('public')->put('anggota/' . $imageName, base64_decode($image));

                // Simpan versi thumb_
                $path = base64_decode($image);
                $thum = Image::make($path)->resize(80, 80, function ($size) {
                    $size->aspectRatio();
                });
                $thumPath = public_path('/storage/anggota') . '/thum_' . $imageName;
                $thum = Image::make($thum)->save($thumPath);

                // Simpan gambar ke Database
                Anggota::where('id', Auth::id())->update(['foto' => $imageName]);
                $data = Anggota::where('id', Auth::id())->first();

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
}
