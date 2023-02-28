<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission-read')->only('index');
        $this->middleware('permission:permission-create')->only(['create', 'store']);
        $this->middleware('permission:permission-update')->only(['edit', 'update']);
        $this->middleware('permission:permission-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.permission.index');
    }

    public function ajax(Request $request)
    {
        if ($request->is_role == true || $request->is_role <> NULL) {
            if ($request->is_edit == true) {
                $role = $request->role;
                $data = Permission::query()
                    ->withCount([
                        'permission_role' => function ($e) use ($role) {
                            $e->where('role_id', $role);
                        }
                    ])
                    ->orderBy('name')
                    ->get();

            } else {
                $data = Permission::query()
                    ->orderBy('name')
                    ->get();
            }
        } else {
            $data = Permission::query()
                ->groupBy('grup')
                ->orderBy('name')
                ->get();
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('permission-update') ? '<a href="' . route('permission.edit', ['permission' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('permission-delete') ?  '<a href="' . route('permission.destroy', ['permission' => $e->id]) . '" data-title="' . $e->grup . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';

                return $btnEdit . ' ' . $btnDelete;
            })
            ->addColumn('checkbox', function ($e) {
                if(isset($e->permission_role_count) && $e->permission_role_count > 0){
                    return '<input type="checkbox" checked="checked" name="permission[]" class="dt-checkboxes" autocomplete="off" value="' . $e->id . '">';
                }else{
                    return '<input type="checkbox" name="permission[]" class="dt-checkboxes" autocomplete="off" value="' . $e->id . '">';
                }
            })
            ->rawColumns(['aksi', 'checkbox'])
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
            'name' => 'required|alpha_dash',
            'description' => 'nullable'
        ], [
            'name.required' => 'Permission wajib diisi',
            'name.alpha_dash' => 'Permission tidak boleh mengandung spasi',
        ]);

        return view('backend.permission.create', compact('validator'));
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
            'name' => 'required|alpha_dash',
            'description' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            $permissions = ['create', 'read', 'update', 'delete', 'print'];
            foreach ($permissions as $item) {
                Permission::create([
                    'grup' => strtolower($request->name),
                    'name' => strtolower($request->name) . '-' . $item,
                    'display_name' => $request->display_name . ' ' . $item,
                    'description' => $request->description,
                ]);
            }

            DB::commit();

            Weblog::set('Membuat permission : ' . $request->name);
            return redirect(route('permission.index'))->with([
                'pesan' => '<div class="alert alert-success">Permission berhasil ditambahkan</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());

            return redirect(route('permission.create'))->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $validator = JsValidatorFacade::make([
            'name' => 'required|alpha_dash',
            'description' => 'nullable'
        ], [
            'name.required' => 'Permission wajib diisi',
            'name.alpha_dash' => 'Permission tidak boleh mengandung spasi',
        ]);

        return view('backend.permission.edit', compact('permission', 'validator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $validasi = $request->validate([
            'name' => 'required|alpha_dash',
            'description' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            $permissions = ['create', 'read', 'update', 'delete', 'print'];

            // delete permission lama
            Permission::where('grup', $permission->grup)->delete();

            foreach ($permissions as $item) {
                Permission::create([
                    'grup' => strtolower($request->name),
                    'name' => strtolower($request->name) . '-' . $item,
                    'display_name' => $request->display_name . ' ' . $item,
                    'description' => $request->description,
                ]);
            }

            DB::commit();

            Weblog::set('Edit permission : ' . $request->name);
            return redirect(route('permission.index'))->with([
                'pesan' => '<div class="alert alert-success">Permission berhasil diperbarui</div>',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());

            return redirect(route('permission.create'))->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $grup = $permission->grup;
            Permission::where('grup', $grup)->delete();
            Weblog::set('Menghapus permission : ' . $permission->grup);
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
