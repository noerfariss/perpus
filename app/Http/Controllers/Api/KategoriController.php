<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $data = Kategori::where('status', true)->orderBy('id', 'desc')->get();
            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function show($id)
    {
        try {
            $data = Kategori::with(['buku'])
                ->withCount('buku')
                ->where('status', true)
                ->where('id', $id);

            if($data->count() > 0){
                return $this->responOk(data:$data->get());
            }else{
                return $this->responError('Data tidak ditemukan', kode:422);
            }

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }
}
