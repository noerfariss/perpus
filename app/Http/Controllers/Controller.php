<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responOk($message = 'Success', $data = [], $token = '')
    {
        $data = [
            'message' => $message,
            'success' => true,
            'data' => $data,
        ];

        if ($token !== '') {
            $data['token'] = $token;
        }

        return response()->json($data);
    }

    public function responError($message = 'Error', $data = [], $kode = 500)
    {
        $data = [
            'message' => $message,
            'success' => false,
            'errors' => $data,
        ];

        return response()->json($data, $kode);
    }
}
