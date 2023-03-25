<?php

namespace Database\Seeders;

use App\Models\Penerbit;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenerbitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Erlangga', 'Yudhistira', 'Tiga Serangkai', 'Intan Pariwara', 'Balai Pustaka', 'Kanisius', 'Ganeca Exact', 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi'];

        $kategories = [];
        $kode = Penerbit::orderby('id', 'desc')->first();

        if ($kode === NULL) {
            $i = 1;
            $kategories = $this->input_item($data, $i);
        } else {
            $last_kode = (int) substr($kode->kode, 2);
            $last_kode = $last_kode + 1;

            $kategories = $this->input_item($data, $last_kode);
        }

        Penerbit::insert($kategories);
    }

    private function input_item($kategori_arr, $nomor)
    {
        foreach ($kategori_arr as $item) {
            $kategories[] = [
                'kode' => 'KP' . str_pad($nomor++, 4, '0', STR_PAD_LEFT),
                'penerbit' => $item,
                'created_at' => Carbon::now(),
            ];
        }

        return $kategories;
    }
}
