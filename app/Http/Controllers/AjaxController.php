<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelas;
use Illuminate\Http\Request;

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
            ->when($provinsi, function($e, $provinsi){
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
}
