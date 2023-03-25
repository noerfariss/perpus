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

        $umum = [
            [
                'judul' => 'Informatika untuk SMP Kelas VII',
                'isbn' => '978-602-244-428-2',
                'pengarang' => 'Maresha Caroline Wijanto, Irya Wisnubhadra, Vania Natali, Wahyono, Sri Mulyati, Ari Wardhani, Sutardi, Heni Pratiwi, Budiman Saputra, Kurnia Astiani, Sumiati',
                'stok' => 4,
                'foto' => 'demo/umum/Informatika-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Ilmu Pengetahuan Sosial untuk SMP Kelas VII',
                'isbn' => '978-602-244-306-3',
                'pengarang' => 'M. Nursa’ban, Supardi, Mohammad Rizky Satria, Sari Oktafiana',
                'stok' => 7,
                'foto' => 'demo/umum/IPS-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Ilmu Pengetahuan Alam untuk SMP Kelas VII',
                'isbn' => '978-602-244-384-1',
                'pengarang' => 'Budiyanti Dwi Hardanie, Victoriani Inabuy, Cece Sutia, Okky Fajar Tri Maryana, Sri Handayani Lestari',
                'stok' => 3,
                'foto' => 'demo/umum/IPA-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Indonesia untuk SMP Kelas VII',
                'isbn' => '978-602-244-308-7',
                'pengarang' => 'Sofie Dewayani',
                'stok' => 11,
                'foto' => 'demo/umum/Bahasa-Indonesia-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Agama Kristen dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-457-2',
                'pengarang' => 'Janse Belandina Non-Serrano',
                'stok' => 2,
                'foto' => 'demo/umum/Kristen-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Agama Khonghucu dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-333-9',
                'pengarang' => 'Lucky Cahya Wandirta, Hartono',
                'stok' => 1,
                'foto' => 'demo/umum/Khonghucu-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],

            [
                'judul' => 'Pendidikan Agama Katolik dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-410-7',
                'pengarang' => 'Lorensius Atrik Wibawa, Maman Sutarman',
                'stok' => 4,
                'foto' => 'demo/umum/Katolik-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Agama Islam dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-434-3',
                'pengarang' => 'Rudi Ahmad Suryadi, Sumiyati',
                'stok' => 7,
                'foto' => 'demo/umum/Islam-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Agama Hindu dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-367-4',
                'pengarang' => 'I Gusti Agung Made Swebawai',
                'stok' => 3,
                'foto' => 'demo/umum/Hindu-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Agama Buddha dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-493-0',
                'pengarang' => 'Mujiyanto, Wiryanto',
                'stok' => 11,
                'foto' => 'demo/umum/Buddha-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika untuk SMP Kelas VII',
                'isbn' => '-392',
                'pengarang' => 'Tim Gakko Tosho',
                'stok' => 7,
                'foto' => 'demo/umum/Matematika-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kepercayaan Terhadap Tuhan Yang Maha Esa dan Budi Pekerti untuk SMP Kelas VII',
                'isbn' => '978-602-244-335-3',
                'pengarang' => 'Jaya Damanik',
                'stok' => 3,
                'foto' => 'demo/umum/Kepercayaan-BS-KLS-VII-Cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Indonesia untuk SMP Kelas VIII',
                'isbn' => '978-602-244-298-1',
                'pengarang' => 'Mujiyanto, Wiryanto',
                'stok' => 6,
                'foto' => 'demo/umum/Bahasa-Indonesia-BS-KLS-VIII-cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],

            [
                'judul' => 'Informatika untuk SMP Kelas VIII',
                'isbn' => '978-602-244-427-5',
                'pengarang' => 'Vania Natali, Mewati Ayub, Maresha Caroline Wijanto, Irya Wisnubhadra, Natalia, Husnul Hakim, Wahyono, Sri Mulyati, Sutardi, Heni Pratiwi, Budiman Saputra, Kurniawan Kartawidjaja, Hanson Prihantoro Putro',
                'stok' => 6,
                'foto' => 'demo/umum/Informatika-BS-KLS-VIII-cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Ilmu Pengetahuan Sosial untuk SMP Kelas VIII',
                'isbn' => '978-602-244-306-3',
                'pengarang' => 'Supardi, Mohammad Rizky Satria, Sari Oktafiana, M. Nursa?ban',
                'stok' => 6,
                'foto' => 'demo/umum/IPS-BS-KLS-VIII-cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Ilmu Pengetahuan Alam untuk SMP Kelas VIII',
                'isbn' => '978-602-244-383-4',
                'pengarang' => 'Okky Fajar Tri Maryana, Victoriani Inabuy, Cece Sutia, Budiyanti Dwi Hardanie, Sri Handayani Lestari',
                'stok' => 6,
                'foto' => 'demo/umum/IPA-BS-KLS-VIII-cover.png',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($umum as $item) {
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
