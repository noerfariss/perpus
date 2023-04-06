<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Facade\Weblog;
use App\Http\Requests\PasswordRequest;
use App\Models\RoleUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-read')->only('index');
        $this->middleware('permission:user-create')->only(['create', 'store']);
        $this->middleware('permission:user-update')->only(['edit', 'update']);
        $this->middleware('permission:user-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('backend.users.index');
    }

    public function ajax(Request $request)
    {
        $status = $request->status;
        $cari = $request->cari;

        $data = User::query()
            ->with('role_user')
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    return $e->where('username', 'like', '%' . $cari . '%')->orWhere('nama', 'like', '%' . $cari . '%')->orWhere('email', 'like', '%' . $cari . '%');
                });
            })
            ->where('id', '<>', 1)
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->get();

        if ($request->filled('export')) {
            return Excel::download(new UserExport($data, $request->all()), 'USER.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('foto', function ($e) {
                $foto = ($e->foto === "" || $e->foto === null) ? '/backend/sneat-1.0.0/assets/img/avatars/1.png' : '/storage/foto/thum_' . $e->foto;
                return '<div><img src="' . url($foto) . '" class="rounded" width="40"/></div>';
            })
            ->editColumn('created_at', function ($e) {
                return '<div class="badge bg-dark rounded-pill">' . Carbon::parse($e->created_at)->isoFormat('DD MMM YYYY HH:mm') . '</div>';
            })
            ->addColumn('role', function ($e) {
                $roles = [];
                foreach ($e->role_user as $item) {
                    $roles[] = '<span class="badge bg-dark">' . $item->name . '</span>';
                }

                return implode(' ', $roles);
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('user-update') ? '<a href="' . route('user.edit', ['user' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('user-delete') ?  '<a href="' . route('user.destroy', ['user' => $e->id]) . '" data-title="' . $e->username . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('user-update') ? '<a href="' . route('user.destroy', ['user' => $e->id]) . '" data-title="' . $e->username . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';
                $btnPassword = Laratrust::isAbleTo('user-update') ? '<button type="button"
                                                                                    class="btn btn-xs btn-open-ganti-password"
                                                                                    data-id="' . $e->id . '"
                                                                                    data-username="' . $e->username . '"
                                                                                    data-email="' . $e->email . '"
                                                                                    data-nama="' . $e->nama . '"
                                                                                    ><i class="bx bxs-key"></i></button>' : '';
                $btnForceLogin = Laratrust::isAbleTo('user-update') ? '<a href="' . route('force-login', ['id' => $e->id]) . '" class="btn btn-xs"><i class="bx bxs-arrow-from-left" title="Force login"></i></a>' : '';

                if ($e->status == true) {
                    return $btnForceLogin . ' ' . $btnPassword . ' ' . $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['foto', 'aksi', 'created_at', 'role'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $validator = JsValidatorFacade::make([
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('users', 'username'),
            ],
            'nama' => 'required',
            'email' => 'email|required',
            'foto' => 'nullable',
            'role' => 'required'
        ]);

        return view('backend.users.create', compact('validator'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('users', 'username'),
            ],
            'nama' => 'required',
            'email' => 'email|required',
            'foto' => 'nullable',
            'role' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'foto' => $request->foto,
                'password' => Hash::make('password'),
            ]);

            $user->attachRoles($request->role);

            DB::commit();
            Weblog::set('Tambah user ' . $request->username);

            return redirect(route('user.index'))->with([
                'pesan' => '<div class="alert alert-success">User ' . $request->username . ' berhasil ditambahkan</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $validator = JsValidatorFacade::make([
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable',
            'role' => 'required',
        ]);

        return view('backend.users.edit', compact('validator', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validasi = $request->validate([
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            User::find($user->id)->update($validasi);
            RoleUser::where('user_id', $user->id)->delete();

            $user->attachRoles($request->role);

            DB::commit();

            Weblog::set('Memperbarui profil : ' . $request->username);
            return redirect(route('user.index'))->with([
                'pesan' => '<div class="alert alert-success">Profil berhasil diperbarui</div>'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());
            return redirect(route('user.index'))->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah beberapa saat lagi</div>'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $status = $user->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                User::find($user->id)->update(['status' => false]);
                Weblog::set('Menghapus user : ' . $user->username);
            } else {
                User::find($user->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan user : ' . $user->username);
            }

            DB::commit();

            return response()->json([
                'pesan' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Weblog::set($th->getMessage());
            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function ganti_password(PasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            User::find($request->id)->update(['password' => Hash::make($request->password)]);
            DB::commit();
            Weblog::set('Ganti password username : ' . $request->username);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());
            return response()->json([
                'errors' => 'Terjadi kesalahan, cobalah kembali'
            ], 500);
        }
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function profil(Request $request)
    {
        $validasi = [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ];

        if ($request->method() === 'POST') {
            $request->validate($validasi);

            DB::beginTransaction();
            try {
                User::where('id', Auth::id())->update(['nama' => $request->nama, 'email' => $request->email]);
                DB::commit();

                Weblog::set('Memperbarui profil');
                return redirect(route('profil'))->with([
                    'pesan' => '<div class="alert alert-success">Profil berhasil diperbarui</div>'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return redirect(route('profil'))->with([
                    'pesan' => '<div class="alert alert-danger">' . $th->getMessage() . '</div>'
                ]);
            }
        }

        $validator = JsValidatorFacade::make($validasi);

        $user = User::find(Auth::id());
        return view('backend.pengaturan.show', compact('user', 'validator'));
    }

    public function password(Request $request)
    {
        if ($request->method() === 'POST') {
            if (Hash::check($request->password_lama, Auth::user()->password)) {

                DB::beginTransaction();
                try {
                    User::find(Auth::id())->update(['password' => Hash::make($request->password)]);
                    DB::commit();

                    Weblog::set('Ganti password');
                    return redirect(route('password'))->with([
                        'pesan' => '<div class="alert alert-success mt-2">Password berhasil diperbarui</div>'
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();

                    Weblog::set('Ganti password tapi gagal');
                    return redirect(route('password'))->with([
                        'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah beberapa saat lagi</div>'
                    ]);
                }
            } else {
                return redirect(route('password'))->with([
                    'pesan' => '<div class="alert alert-danger mt-2">Password lama Anda salah!</div>'
                ]);
            }
        }

        $validator = JsValidatorFacade::make([
            'password_lama' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        return view('backend.pengaturan.ganti_password', compact('validator'));
    }

    public function weblog(Request $request)
    {
        if ($request->method() === 'POST') {
        }

        return view('backend.pengaturan.ganti_password');
    }

    public function simpan_foto(Request $request)
    {
        DB::beginTransaction();
        try {
            User::find(Auth::id())->update(['foto' => $request->foto]);
            DB::commit();

            Weblog::set('Memperbarui foto profil');

            return response()->json([
                'pesan' => 'Update foto berhasil'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                'pesan' => 'Terjadi kesalahan'
            ]);
        }
    }
}
