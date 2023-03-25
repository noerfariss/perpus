<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Kelas;
use App\Models\Kota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Anggota::create([
            'nomor_induk' => 777777,
            'nomor_anggota' => $this->kode_anggota(),
            'password' => Hash::make('password'),
            'nama' => 'TESTER SISWA',
            'jenis_kelamin' => 'L',
            'kota_id' => Kota::inRandomOrder()->first()->id,
            'tanggal_lahir' => '2010-07-07',
            'kelas_id' => Kelas::inRandomOrder()->first()->id,
            'jabatan' => 'siswa',
            'alamat' => '',
        ]);
    }

    public function kode_anggota()
    {
        // nomor anggota
        // 2022KS0001
        // 2022 --- Tahun registrasi
        // KS/KG --- Kode Siswa / Guru
        // 0001 --- No. Urut siswa

        $data = Anggota::query()
            ->where('jabatan', 'siswa')
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->orderBy('id', 'desc')
            ->first();

        if ($data === null) {
            $kode_anggota = date('Y') . 'KS' . str_pad(1, 4, '0', STR_PAD_LEFT);

            return $kode_anggota;
        } else {

            $last_kode = $data->nomor_anggota;
            $start_kode = substr($last_kode, 0, 6);
            $end_kode = (int) substr($last_kode, 6);
            $next_kode = $end_kode + 1;

            $kode_anggota = $start_kode . str_pad($next_kode, 4, '0', STR_PAD_LEFT);

            return $kode_anggota;
        }
    }
}
