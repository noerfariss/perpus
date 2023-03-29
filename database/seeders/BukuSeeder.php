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
                'kategori_id' => Kategori::where('kategori', 'Novel')->first()->id,
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
                'kategori_id' => Kategori::where('kategori', 'Buku Pelajaran Siswa kelas 7')->first()->id,
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

        $kelas9 = [
            [
                'judul' => 'Aktif Berolahraga Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/9/Kelas9_Aktif_Berolah_Raga_3_989.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Alam_Sekitar_IPA_Terpadu_632.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Arena Pendidikan Jasmani Olahraga dan Kesehatan Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 8,
                'pdf' => 'demo/9/Kelas9_Arena_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_1002.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Asiknya Belajar Bahasa dan Sastra Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/9/Kelas9_Asyiknya_Belajar_Bahasa_dan_Sastra_Indonesia_3_691.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa dan Sastra Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 14,
                'pdf' => 'demo/9/Kelas9_Bahasa_dan_Sastra_Indonesia_3_45.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 14,
                'pdf' => 'demo/9/Kelas9_Bahasa_Indonesia_9_60.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Indonesia 1236 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 14,
                'pdf' => 'demo/9/Kelas9_Bahasa_Indonesia_1236.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Indonesia Bahasa Bangsaku Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Bahasa_Indonesia_Bahasa_Bangsaku_3.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Kepercayaan Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kepercayaan-BS-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Indonesia 116 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 9,
                'pdf' => 'demo/9/Kelas9_Bahasa_Indonesia_Indonesia_Kelas_IX_116.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Inggris 117 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 3,
                'pdf' => 'demo/9/Kelas9_Bahasa_Inggris_117.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Berbahasa dan Bersastra Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 13,
                'pdf' => 'demo/9/Kelas9_Berbahasa_Dan_Bersastra_Indonesia_1239.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Berbahasa Indonesia 1208 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 13,
                'pdf' => 'demo/9/Kelas9_Berbahasa_Indonesia_1208.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PPKN Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_buku_siswa_ppkn_kelas_ix_2175.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Cakap Berbahasa Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Cakap_Berbahasa_Indonesia_923.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'English in Focus Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_English_in_Focus_74.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA 710 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Alam_3_710.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA 682 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Alam_682.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA 121 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Alam_Kelas_IX_121.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS 720 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Sosial_3_720.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS 111 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Sosial_111.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS 159 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Sosial_159.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS 600 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Sosial_600.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS 605 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Sosial_605.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS untuk MTS 67 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Ilmu_Pengetahuan_Sosial_untuk_SMP_MTs_67.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Jelajah Cakrawala Sosial 3 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Jelajah_Cakrawala_Sosial_3_669.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Kreatif Berbahasa Indonesia 3 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Kreatif_Berbahasa_Indonesia_3_703.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika 3 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Matematika_3_1197.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika 47 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Matematika_47.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika 1195 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_MATEMATIKA_1195.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika 1202 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_MATEMATIKA_1202.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika Aktif dan Menyenangkan Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Matematika_Aktif_dan_Menyenangkan_84.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Membaca Cakrawal TIK Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Membuka_Cakrawala_Teknologi_Informasi_Dan_Komunikasi_986.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Membuka Jendela Ilmu Pengetahuan dan Sastra Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Membuka_Jendela_Ilmu_Pengetahuan_Bahasa_dan_Sastra_Indonesia_27.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Mudah Belajar Matematika Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Mudah_Belajar_Matematika_kelas_IX_19.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pegangan Belajar Matematika 3 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pegangan_Belajar_Matematika_3_163.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pelajaran Bahasa Indonesia 59 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pelajaran_Bahasa_Indonesia_59.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA Terpadu dan Kontekstual Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pembelajaran_Ilmu_Pengetahuan_Alam_Terpadu_Dan_Kontekstual_IX_181.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PAI Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Agama_Islam_1191.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PAI 1210 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Agama_Islam_1210.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PAI 1213 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Agama_Islam_1213.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PAI 1214 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Agama_Islam_1214.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 3 967 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_3_967.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 3 992 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_3_992.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 3 1000 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_3_1000.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 3 997 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_977.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 996 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_996.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 1007 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_1007.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Jasmani Olahraga dan Kesehatan 1011 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Jasmani_Olahraga_dan_Kesehatan_1011.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 685 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Kewarganegaraan_3_685.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 748 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Kewarganegaraan_3_748.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 676 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Kewarganegaraan_676.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 802 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Kewarganegaraan_802.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 838 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Kewarganegaraan_838.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 877 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_Kewarganegaraan_877.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pendidikan Kewarganegaraan 3 1275 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Pendidikan_kewarganegaraan_1275.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Penjasorkes Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Penjasorkes_IX_1223.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PKN Kecakapan Berbahasa dan Bernegara Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_PKn_Kecakapan_Berbangsa_dan_Bernegara_665.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Satelit TIK Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Satelit_TIK_984.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 971 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_Dan_Komunikasi_971.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 973 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_Dan_Komunikasi_973.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 979 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_Dan_Komunikasi_979.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 981 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_Dan_Komunikasi_981.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 988 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_Dan_Komunikasi_988.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 991 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_Dan_Komunikasi_991.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'TIK 1212 Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 7,
                'pdf' => 'demo/9/Kelas9_Teknologi_Informasi_dan_Komunikasi_1212.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($kelas9 as $item) {
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
                'kategori_id' => Kategori::where('kategori', 'Buku Pelajaran Siswa kelas 9')->first()->id,
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


        $kelas8 = [
            [
                'judul' => 'Bahasa Indonesia Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Bahasa-Indonesia-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Bahasa Inggris Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Bahasa-Inggris-BS-KLS-VIII-nsn.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Buddha Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Buddha-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Hindu Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Hindu-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Informatika Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Informatika-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPA Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/IPA-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'IPS Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/IPS-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Islam Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Islam-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Katolik Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Katolik-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Pojok Buku Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Kelas8_pjok_buku_siswa_smp_mts_kelas_viii_2027.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Kepercayaan Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Kepercayaan-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Khonghucu Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Khonghucu-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Kristen Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Kristen-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Matematika-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Matematika Baru Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/Matematika-BS-KLS-VIII-Baru.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'PPKN Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/PPKN-BS-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($kelas8 as $item) {
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
                'kategori_id' => Kategori::where('kategori', 'Buku Pelajaran Siswa kelas 8')->first()->id,
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

        // =============================== BUKU PANDUAN

        $panduan9 = [
            [
                'judul' => 'Buku Guru Bahasa Indonesia Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/9/panduan/Kelas9_buku_guru_bahasa_indonesia_kelas_ix_2119.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Buku Guru Bahasa Inggris Kelas IX',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/9/panduan/Kelas9_buku_guru_bahasa_inggris_kelas_ix_2141.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($panduan9 as $item) {
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
                'kategori_id' => Kategori::where('kategori', 'Buku Panduan Guru kelas 9')->first()->id,
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

        $panduan8 = [
            [
                'judul' => 'Panduan Bahasa Indonesia Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Bahasa-Indonesia-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Bahasa Inggris Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Bahasa-Inggris-BG-KLS-VIII-nsn.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Budidaya Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Prakarya-Budidaya-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Buddha Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Buddha-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Kerajinan Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Prakarya-Kerajinan-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Hindu Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Hindu-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Pengolahan Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Prakarya-Pengolahan-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Informatika Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Informatika-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Rekayasa Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Prakarya-Rekayasa-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan IPA Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/IPA-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan IPS Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/IPS-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Islam Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Islam-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Katolik Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Katolik-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Kepercayaan Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Kepercayaan-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Musik Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Seni-Musik-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Khonghucu Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Khonghucu-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Rupa Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Seni-Rupa-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Kristen Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Kristen-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Tari Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Seni-Tari-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Teater Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Seni-Teater-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Matematika Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Matematika-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan PJOK Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/PJOK-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan PPKN Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/PPKN-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Matematika Baru Kelas VIII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/8/panduan/Matematika-BG-KLS-VIII-Baru.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($panduan8 as $item) {
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
                'kategori_id' => Kategori::where('kategori', 'Buku Panduan Guru kelas 8')->first()->id,
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

        $panduan7 = [
            [
                'judul' => 'Panduan Bahasa Indonesia Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Bahasa-Indonesia-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Bahasa Inggris Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Bahasa-Inggris-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Buddha Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Buddha-BG-KLS-VII (1).pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Hindu Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Hindu-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Pengolahan Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Prakarya-Pengolahan-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Informatika Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/IInformatika-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Rekayasa Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/IPA-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan IPA Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/IPS-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan IPS Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/IPS-BG-KLS-VIII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Islam Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Islam-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Katolik Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/KATOLIK-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Kepercayaan Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Kepercayaan-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Khonghucu Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Khonghucu-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Kristen Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Kristen-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Matematika Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Matematika-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Matematika Licensi Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Matematika-BG-KLS-VII-Licensi.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan PJOK Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/PJOK-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan PPKN Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/PPKN-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Budi Daya Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Prakarya-Budi-Daya-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Kerajinan Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Prakarya-Kerajinan-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Pengolahan Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Prakarya-Pengolahan-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Prakarya Rekayasa Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Prakarya-Rekayasa-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Rupa Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Seni-Rupa-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Tari Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Seni-Tari-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
            [
                'judul' => 'Panduan Seni Teater Kelas VII',
                'isbn' => '123-4556-678-93-1',
                'pengarang' => 'aaaa',
                'stok' => 4,
                'pdf' => 'demo/7/panduan/Seni-Teater-BG-KLS-VII.pdf',
                'penerbit_id' => Penerbit::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($panduan7 as $item) {
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
                'kategori_id' => Kategori::where('kategori', 'Buku Panduan Guru kelas 7')->first()->id,
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
