<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelas;
use App\Models\Penerbit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AjaxController extends Controller
{
    public function role(Request $request)
    {
        $term = $request->term;
        $data = Role::query()
            ->when($term, function ($e, $term) {
                $e->where('name', 'like', '%' . $term . '%');
            })
            ->select('id', 'name as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function provinsi(Request $request)
    {
        $term = $request->term;
        $data = Provinsi::query()
            ->when($term, function ($e, $term) {
                $e->where('provinsi', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'provinsi as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kota(Request $request)
    {
        $term = $request->term;
        $provinsi = $request->provinsi;
        $data = Kota::query()
            ->when($term, function ($e, $term) {
                $e->where('kota', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->when($provinsi, function ($e, $provinsi) {
                $e->where('provinsi_id', $provinsi);
            })
            ->select('id', 'kota as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kecamatan(Request $request)
    {
        $term = $request->term;
        $kota = $request->kota;
        $data = Kecamatan::query()
            ->when($term, function ($e, $term) {
                $e->where('kecamatan', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->where('kota_id', $kota)
            ->select('id', 'kecamatan as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kelas(Request $request)
    {
        $term = $request->term;
        $data = Kelas::query()
            ->when($term, function ($e, $term) {
                $e->where('kelas', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'kelas as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kategori(Request $request)
    {
        $term = $request->term;
        $data = Kategori::query()
            ->when($term, function ($e, $term) {
                $e->where('kategori', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'kategori as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function penerbit(Request $request)
    {
        $term = $request->term;
        $data = Penerbit::query()
            ->when($term, function ($e, $term) {
                $e->where('penerbit', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'penerbit as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function get_no_transaksi()
    {
        return getKodeTransaksi();
    }

    public function ganti_foto(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $path = $request->path;

            switch ($path) {
                case 'buku':
                    $size_gambar = 200;
                    break;

                case 'foto':
                    $size_gambar = 100;
                    break;

                case 'anggota':
                    $size_gambar = 120;
                    break;

                case 'banner':
                    $size_gambar = 1024;
                    break;

                default:
                    $size_gambar = 150;
                    break;
            }

            $request->validate([
                'file' => 'required|image|max:2000'
            ]);

            $name = time();
            $ext  = $file->getClientOriginalExtension();
            $foto = $name . '.' . $ext;

            $fullPath = $path . '/' . $foto;

            $path = $file->getRealPath();
            $thum = Image::make($path)->resize($size_gambar, $size_gambar, function ($size) {
                $size->aspectRatio();
            });

            $path = Storage::put($fullPath, $thum->stream());

            return response()->json([
                'file' => $fullPath,
            ]);
        }
    }

    public function ganti_pdf(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $request->validate([
                'file' => 'required|mimes:pdf|max:2000'
            ]);

            $path = Storage::put('buku/pdf', $request->file);

            return response()->json([
                'file' => $path,
            ]);
        }
    }
}
