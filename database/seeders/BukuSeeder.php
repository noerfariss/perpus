<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\BukuItem;
use App\Models\Kategori;
use App\Models\Penerbit;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $novel = [
            [
                'judul' => 'Biji Merah Luna',
                'isbn' => '978-602-244-926-3',
                'pengarang' => 'Ammy Kudo',
                'stok' => 9,
                'foto' => 'demo/novel/Biji_Merah_Luna_Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Aku Sudah Besar',
                'isbn' => '978-602-244-930-0',
                'pengarang' => 'Futri Wijayanti',
                'stok' => 7,
                'foto' => 'demo/novel/Aku_Sudah_Besar_Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Gambar Lucu Mika',
                'isbn' => '978-602-244-936-2',
                'pengarang' => 'Tyas Widjati',
                'stok' => 3,
                'foto' => 'demo/novel/Gambar_Lucu_Mika_Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Karena Anggrek Ibu',
                'isbn' => '978-602-244-944-7',
                'pengarang' => 'Debby Lukito Goeyardi, Widyasari Hanaya',
                'stok' => 11,
                'foto' => 'demo/novel/Karena_Anggrek_Ibu_Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Namaku Kali',
                'isbn' => '978-602-427-921-9',
                'pengarang' => 'Anna Farida, Felishia',
                'stok' => 2,
                'foto' => 'demo/novel/Namaku_Kali_Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Naik-Naik Ke puncak Bukit',
                'isbn' => '978-602-244-937-9',
                'pengarang' => 'Sarah Fauzia',
                'stok' => 1,
                'foto' => 'demo/novel/Naik_Naik_Kepuncak_Bukit_Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($novel as $item) {
            $buku = Buku::create([
                'judul' => $item['judul'],
                'isbn' => $item['isbn'],
                'pengarang' => $item['pengarang'],
                'stok' => $item['stok'],
                'foto' => $item['foto'],
                'penerbit_id' => $item['penerbit_id'],
            ]);

            DB::table('buku_kategori')->insert([
                'buku_id' => $buku->id,
                'kategori_id' => Kategori::where('kategori', 'novel')->first()->id,
                'created_at' => Carbon::now(),
            ]);

            // stok
            $stok = $item['stok'];
            $kode = getKodeBuku();
            $newKode = (int) substr($kode, 2);

            for ($i = 0; $i < $stok; $i++) {
                BukuItem::create([
                    'buku_id' => $buku->id,
                    'kode' => 'BK' . str_pad($newKode++, 5, '0', STR_PAD_LEFT),
                ]);
            }
        }

        $kelas7 = [
            [
                'judul' => 'Bahasa Indonesia Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/Bahasa-Indonesia-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Inggris Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/7/Bahasa-Inggris-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Buddha Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 8,
                'pdf' => 'demo/7/Buddha-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Hindu Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/Hindu-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Informatika Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 14,
                'pdf' => 'demo/7/Informatika-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 14,
                'pdf' => 'demo/7/IPA-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 14,
                'pdf' => 'demo/7/IPS-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Islam Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/7/Islam-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Kepercayaan Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/7/Kepercayaan-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Khonghucu Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 9,
                'pdf' => 'demo/7/Khonghucu-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Kristen Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 3,
                'pdf' => 'demo/7/Kristen-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 13,
                'pdf' => 'demo/7/Matematika-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika Licensi Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 13,
                'pdf' => 'demo/7/Matematika-BS-KLS-VII-Licensi.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PPKN Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/7/PPKN-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Seni Musik Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/7/Seni-Musik-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($kelas7 as $item) {
            $buku = Buku::create([
                'judul' => $item['judul'],
                'isbn' => $item['isbn'],
                'pengarang' => $item['pengarang'],
                'stok' => $item['stok'],
                'pdf' => $item['pdf'],
                'penerbit_id' => $item['penerbit_id'],
            ]);

            DB::table('buku_kategori')->insert([
                'buku_id' => $buku->id,
                'kategori_id' => Kategori::where('kategori', 'umum')->first()->id,
                'created_at' => Carbon::now(),
            ]);

            // stok
            $stok = $item['stok'];
            $kode = getKodeBuku();
            $newKode = (int) substr($kode, 2);

            for ($i = 0; $i < $stok; $i++) {
                BukuItem::create([
                    'buku_id' => $buku->id,
                    'kode' => 'BK' . str_pad($newKode++, 5, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }
}
