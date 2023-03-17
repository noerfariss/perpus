<?php

namespace App\Console\Commands;

use App\Models\Denda as ModelsDenda;
use App\Models\Peminjaman;
use App\Models\Umum;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Denda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'denda:traking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk mentraking denda setiap harinya kepada si peminjam buku';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rupiah = Umum::first()->denda;

        $data = Peminjaman::query()
            ->where([
                'is_kembali' => false,
                'status' => true,
            ])
            ->whereDate('batas_pengembalian', '<', Carbon::now());

        if ($data->count() > 0) {
            DB::beginTransaction();
            try {
                $denda_arr = [];
                foreach ($data->get() as $item) {
                    $denda_arr[] = [
                        'peminjaman_id' => $item->id,
                        'denda' => $rupiah,
                        'created_at' => Carbon::now(),
                    ];
                }

                ModelsDenda::insert($denda_arr);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::warning($th->getMessage());
            }
        }
    }
}
