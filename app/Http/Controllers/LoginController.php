<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\Umum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect(route('dashboard'));
        }

        $validasi = [
            'username' => 'required',
            'password' => 'required',
        ];

        if ($request->method() === 'POST') {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'status' => true])) {

                Weblog::set('Login');
                return redirect(route('dashboard'));
            } else {
                return redirect(route('login'))->with([
                    'pesan' => '<div class="alert alert-danger">Username atau password Anda salah!</div>'
                ]);
            }
        }

        $validator = JsValidatorFacade::make($validasi);

        $umum= Umum::first();
        $logo_login= $umum->logo;
        $nama= $umum->nama;

        return view('backend.auth.login', compact('validator', 'logo_login', 'nama'));
    }

    public function force_login($id)
    {
        $user = User::find($id)->first();
        Weblog::set('Force login : ' . $user->username);

        if (Auth::loginUsingId($id)) {
            return redirect(route('dashboard'));
        } else {
            return redirect('/')->with([
                'pesan' => '<div class="alert alert-danger">Username atau password Anda salah!</div>'
            ]);
        }
    }

    public function logout()
    {
        Weblog::set('Logout');
        Auth::logout();
        return redirect(route('login'));
    }
}
