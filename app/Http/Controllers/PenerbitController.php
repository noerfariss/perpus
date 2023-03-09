<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\Penerbit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenerbitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'kode' => 'required|unique:penerbits,kode',
            'penerbit' => 'required',
        ]);

        DB::beginTransaction();
        try {
            if ($request->exists('tipe')) {
                Penerbit::create($request->except(['_token', 'tipe']));
                DB::commit();
                Weblog::set('Menambahkan Penerbit baru : ' . $request->penerbit);

                return response()->json([
                    'message' => 'Data berhasil diinputkan'
                ]);

            } else {
                Penerbit::create($request->except('proengsoft_jsvalidation'));
                DB::commit();
                Weblog::set('Menambahkan Penerbit baru : ' . $request->penerbit);

                return redirect(route('penerbit.index'))->with([
                    'pesan' => '<div class="alert alert-success">Data berhasil ditambahkan</div>',
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function show(Penerbit $penerbit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function edit(Penerbit $penerbit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penerbit $penerbit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penerbit  $penerbit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penerbit $penerbit)
    {
        //
    }
}
