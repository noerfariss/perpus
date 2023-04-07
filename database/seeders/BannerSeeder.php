<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['banner1.jpg', 'banner2.jpg'];
        foreach($data as $item){
            Banner::create([
                'gambar' => 'banner/'.$item,
            ]);
        }
    }
}
