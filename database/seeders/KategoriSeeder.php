<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'kategori' => 'Buku Pelajaran Siswa kelas 9',
                'akses_siswa' => true,
                'akses_guru' => true,
                'urutan' => 1,
            ],
            [
                'kategori' => 'Buku Pelajaran Siswa kelas 8',
                'akses_siswa' => true,
                'akses_guru' => true,
                'urutan' => 2,
            ],
            [
                'kategori' => 'Buku Pelajaran Siswa kelas 7',
                'akses_siswa' => true,
                'akses_guru' => true,
                'urutan' => 3,
            ],
            [
                'kategori' => 'Buku Panduan Guru kelas 9',
                'akses_siswa' => false,
                'akses_guru' => true,
                'urutan' => 4,
            ],
            [
                'kategori' => 'Buku Panduan Guru kelas 8',
                'akses_siswa' => false,
                'akses_guru' => true,
                'urutan' => 5,
            ],
            [
                'kategori' => 'Buku Panduan Guru kelas 7',
                'akses_siswa' => false,
                'akses_guru' => true,
                'urutan' => 6,
            ],
            [
                'kategori' => 'Novel',
                'akses_siswa' => true,
                'akses_guru' => true,
                'urutan' => 7,
            ],
            [
                'kategori' => 'Buku Umum',
                'akses_siswa' => true,
                'akses_guru' => true,
                'urutan' => 8,
            ]
        ];

        $kategories = [];
        $kode = Kategori::orderby('id', 'desc')->first();

        if ($kode === NULL) {
            $i = 1;
            $kategories = $this->input_item($data, $i);
        } else {
            $last_kode = (int) substr($kode->kode, 2);
            $last_kode = $last_kode + 1;

            $kategories = $this->input_item($data, $last_kode);
        }

        Kategori::insert($kategories);
    }

    private function input_item($kategori_arr, $nomor)
    {
        foreach ($kategori_arr as $item) {
            $kategories[] = [
                'kode' => 'KG' . str_pad($nomor++, 4, '0', STR_PAD_LEFT),
                'kategori' => $item['kategori'],
                'akses_siswa' => $item['akses_siswa'],
                'akses_guru' => $item['akses_guru'],
                'urutan' => $item['urutan'],
                'created_at' => Carbon::now(),
            ];
        }

        return $kategories;
    }
}
