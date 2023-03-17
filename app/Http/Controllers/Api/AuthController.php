<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'anggota' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validasi->fails()) {
            return $this->responError(data: $validasi->errors(), kode: 422);
        }

        $auth = Auth::guard('anggota')->attempt([
            'nomor_anggota' => $request->anggota,
            'password' => $request->password,
            'status' => true
        ]);

        if ($auth) {
            $user = Auth::guard('anggota')->user();
            $token = $user->createToken($user->nama)->plainTextToken;

            return $this->responOk(data: $user, token: $token);
        } else {
            return $this->responError('Nomor Anggota atau password salah!', kode: 422);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return $this->responOk('Berhasil Logout', $user);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function cek_user()
    {
        try {
            $user = Auth::user();
            return $this->responOk('Data berhasil ditemukan', $user);

        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError(kode: 422);
        }
    }
}
