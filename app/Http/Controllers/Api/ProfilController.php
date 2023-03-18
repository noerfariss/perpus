<?php

namespace App\Http\Controllers\Api;

use App\Facade\Weblog;
use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
                ->where('id', Auth::id())->first();
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

        if($validator->fails()){
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

            return $this->responOk(data:$user);

        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
