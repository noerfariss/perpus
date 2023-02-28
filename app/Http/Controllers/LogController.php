<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->method() === 'POST') {
            $data = Log::query()->where('user_id', Auth::id())->with('user')->orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->editColumn('browser', function($e){
                    return $e->browser.' '.$e->browser_version;
                })
                ->editColumn('platform', function($e){
                    return $e->platform.' '.$e->platform_version;
                })
                ->addColumn('tanggal', function ($e) {
                    return '<div class="badge bg-dark rounded-pill">'.Carbon::parse($e->created_at)->isoFormat('DD MMM YYYY HH:mm').'</div>';
                })
                ->rawColumns(['tanggal'])
                ->make();
        }

        return view('backend.pengaturan.log');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }
}
