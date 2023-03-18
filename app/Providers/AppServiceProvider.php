<?php

namespace App\Providers;

use App\Models\Umum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($e){
            $umum = Umum::first();

            $e->with([
                'tanggal_sekarang' => Carbon::now()->timezone(zona_waktu())->isoFormat('dddd, DD MMMM YYYY'),
                'title_web' => $umum->nama,
                'logo' => ($umum->logo === NULL || $umum->logo === '' || $umum->logo == 'logo') ? '<h2 style="margin:.5rem 0 !important; font-weight:bold;">admin</h2>' : '<img src="'.url('/storage/foto/'.$umum->logo).'" height="50">',
                'logo_login' => ($umum->logo === NULL || $umum->logo === '' || $umum->logo == 'logo') ? '<h2>'.env('APP_NAME').'</h2>' : '<img src="'.url('/storage/foto/'.$umum->logo).'" height="60">',
                'favicon' => ($umum->favicon === NULL || $umum->favicon === '' || $umum->favicon == 'favicon') ? 'Admin' : ''.url('/storage/foto/'.$umum->favicon).'',
            ]);
        });

        Schema::defaultStringLength(191);

    }
}
