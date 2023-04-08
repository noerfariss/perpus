<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Anggota::query()
                ->with([
                    'kelas',
                    'kota'
                ])
                ->where('status', true)->where('jabatan', 'siswa')
                ->get();

            foreach ($data as $key => $row) {
                if ($row->foto) {
                    $data[$key]['foto'] = base_url($row->foto);
                } else {
                    $data[$key]['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
                }
            }
            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Anggota::query()
                ->with([
                    'kelas',
                    'kota'
                ])
                ->where('status', true)
                ->where('id', $id)
                ->where('jabatan', 'siswa')
                ->first();
            if ($data === null) {
                return $this->responError('Data tidak ditemukan', kode: 404);
            }

            if ($data->foto) {
                $data['foto'] = base_url($data->foto);
            } else {
                $data['foto'] = url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
            }

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
