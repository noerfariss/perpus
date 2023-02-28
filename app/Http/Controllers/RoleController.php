<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-read')->only('index');
        $this->middleware('permission:role-create')->only(['create', 'store']);
        $this->middleware('permission:role-update')->only(['edit', 'update']);
        $this->middleware('permission:role-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.role.index');
    }

    public function ajax(Request $request)
    {
        $data = Role::query()
            ->orderBy('name')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('role-update') ? '<a href="' . route('role.edit', ['role' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('role-delete') ?  '<a href="' . route('role.destroy', ['role' => $e->id]) . '" data-title="' . $e->grup . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';

                return $btnEdit . ' ' . $btnDelete;
            })
            ->rawColumns(['aksi'])
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
            'name' => 'required|unique:roles,name',
            'display_name' => 'nullable',
            'description' => 'nullable',
            'permission.*' => 'required',
        ]);

        return view('backend.role.create', compact('validator'));
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
            'name' => 'required|unique:roles,name',
            'display_name' => 'nullable',
            'description' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create($validasi);
            $role->attachPermissions($request->permission);
            DB::commit();

            Weblog::set('Membuat role : ' . $request->name);
            return redirect(route('role.index'))->with([
                'pesan' => '<div class="alert alert-success">Role berhasil ditambahkan</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());

            return redirect(route('role.create'))->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $validator = JsValidatorFacade::make([
            'name' => 'required|unique:roles,name,'.$role->id,
            'display_name' => 'nullable',
            'description' => 'nullable',
        ]);

        return view('backend.role.edit', compact('role','validator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validasi = $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'display_name' => 'nullable',
            'description' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            Role::find($role->id)->update($validasi);
            PermissionRole::where('role_id', $role->id)->delete();

            $role->attachPermissions($request->permission);

            DB::commit();

            Weblog::set('Edit Role : ' . $request->name);
            return redirect(route('role.index'))->with([
                'pesan' => '<div class="alert alert-success">Role berhasil diperbarui</div>',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            Role::find($role->id)->delete();
            Weblog::set('Menghapus Role : ' . $role->name);
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
}
