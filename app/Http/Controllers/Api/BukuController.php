<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\LogBuku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->cari;
        $kategori = $request->kategori;

        try {
            $data = Buku::query()
                ->withCount([
                    'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                ])
                ->with([
                    'kategori' => fn ($e) => $e->select('id', 'kode', 'kategori'),
                    'penerbit' => fn ($e) => $e->select('id', 'kode', 'penerbit'),
                ])
                ->when($cari, function ($e, $cari) {
                    $e->where(function ($e) use ($cari) {
                        $e->where('judul', 'like', '%' . $cari . '%')->orWhere('isbn', 'like', '%' . $cari . '%');
                    });
                })
                ->when($kategori, function ($e, $kategori) {
                    $e->whereHas('kategori', function ($e) use ($kategori) {
                        $e->where('id', $kategori);
                    });
                })
                ->where('status', true)
                ->orderBy('id', 'desc')
                ->get();

            $records = [];
            foreach ($data as $item) {
                $records[] = [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'foto' => ($item->foto == null or $item->foto == '') ? url('backend/sneat-1.0.0/assets/img/avatars/coverbook.jpg') : base_url($item->foto),
                    'pengarang' => $item->pengarang,
                    'isbn' => $item->isbn,
                    'stok' => $item->stok,
                    'dipinjam' => $item->dipinjam,
                    'status_pinjam' => ($item->stok - $item->dipinjam) === 0 ? 'Kosong' : 'Tersedia',
                    'kategori' => $item->kategori,
                    'penerbit' => $item->penerbit,
                    'created_at' => $item->created_at,
                ];
            }

            if (count($records) > 0) {
                return $this->responOk(data: $records);
            } else {
                return $this->responError('Data tidak tersedia', kode: 404);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function show($id)
    {
        try {
            $data = Buku::query()
                ->withCount([
                    'buku_item as dipinjam' => fn ($e) => $e->has('peminjaman_belum_kembali'),
                ])
                ->with([
                    'kategori',
                    'penerbit',
                ])
                ->where('status', true)
                ->where('id', $id);

            if ($data->count() > 0) {
                $data = $data->first();

                $data = [
                    'id' => $data->id,
                    'judul' => $data->judul,
                    'foto' => ($data->foto == null or $data->foto == '') ? url('backend/sneat-1.0.0/assets/img/avatars/coverbook.jpg') : base_url($data->foto),
                    'pengarang' => $data->pengarang,
                    'isbn' => $data->isbn,
                    'stok' => $data->stok,
                    'dipinjam' => $data->dipinjam,
                    'status_pinjam' => ($data->stok - $data->dipinjam) === 0 ? 'Kosong' : 'Tersedia',
                    'kategori' => $data->kategori,
                    'penerbit' => $data->penerbit,
                    'pdf' => $data->pdf ? base_url($data->pdf) : null,
                    'created_at' => $data->created_at,
                ];

                return $this->responOk(data: $data);
            } else {
                return $this->responError('Data tidak ditemukan', kode: 422);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError();
        }
    }

    public function peminjaman(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        try {
            $data = Peminjaman::query()
                ->with([
                    'buku_item' => fn ($e) => $e->with([
                        'buku' => fn ($e) => $e->with([
                            'kategori',
                            'penerbit'
                        ])
                    ]),
                ])
                ->withSum('denda', 'denda')
                ->where('anggota_id', Auth::id())
                ->where('status', true)
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('id', 'desc');

            if ($data->count() > 0) {
                foreach ($data->get() as $item) {
                    $records[] = [
                        'id' => $item->id,
                        'tgl_pinjam' => Carbon::parse($item->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY'),
                        'batas_pengembalian' => Carbon::parse($item->batas_pengembalian)->isoFormat('DD MMM YYYY'),
                        'status_pinjam' => $item->is_kembali === 1 ? 'Dikembalikan' : 'Dipinjam',
                        'tgl_kembali' => $item->is_kembali === 1 ? Carbon::parse($item->updated_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY') : '-',
                        'denda' => $item->denda_sum_denda === null ? 0 : (int) $item->denda_sum_denda,
                        'buku' => [
                            'id' => $item->buku_item->buku->id,
                            'kode_buku' => $item->buku_item->kode,
                            'judul' => $item->buku_item->buku->judul,
                            'foto' => ($item->buku_item->buku->foto == null or $item->buku_item->buku->foto == '') ? url('backend/sneat-1.0.0/assets/img/avatars/coverbook.jpg') : base_url($item->buku_item->buku->foto),
                            'pengarang' => $item->buku_item->buku->pengarang,
                            'isbn' => $item->buku_item->buku->isbn,
                            'kategori' => $item->buku_item->buku->kategori,
                            'penerbit' => $item->buku_item->buku->penerbit,
                        ],
                    ];
                }

                return $this->responOk(data: $records);
            } else {
                return $this->responOk('Data tidak tersedia');
            }

            return $this->responOk(data: $data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    public function log_buku_read($buku_id)
    {
        DB::beginTransaction();
        try {
            $log = LogBuku::insertGetId([
                'anggota_id' => Auth::id(),
                'buku_id' => $buku_id,
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return $this->responOk('Log buku berhasil diinput', data: $log);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan, Log buku gagal');
        }
    }

    public function log_buku_tutup(Request $request)
    {
        $log = LogBuku::find($request->log_id);
        if ($log === null) {
            return $this->responError('Data log tidak tersedia', kode: 422);
        }

        DB::beginTransaction();
        try {
            $log = LogBuku::where('id', $request->log_id)->update([
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return $this->responOk('Log buku berhasil diperbarui');
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return $this->responError('Terjadi kesalahan, Log buku gagal');
        }
    }

    public function log_siswa(Request $request)
    {
        $tmulai = $request->tmulai;
        $takhir = $request->takhir;
        $siswa = $request->siswa_id;

        try {
            $data = LogBuku::query()
                ->with([
                    'anggota' => fn ($e) => $e->with(['kelas', 'kota']),
                    'buku' => fn ($e) => $e->with(['penerbit', 'kategori'])
                ])
                ->when($siswa, function ($e, $siswa) {
                    $e->where('anggota_id', $siswa);
                })
                ->whereNotNull('updated_at')
                ->whereDate('created_at', '>=', $tmulai)
                ->whereDate('created_at', '<=', $takhir)
                ->orderBy('id', 'desc');

            if ($data->count() === 0) {
                return $this->responError('Data tidak tersedia', kode: 404);
            }

            $data = $data->get();
            $records = [];
            foreach ($data as $item) {
                $buka = Carbon::parse($item->created_at);
                $tutup = Carbon::parse($item->updated_at);

                $records[] = [
                    'id' => $item->id,
                    'tanggal_akses' => Carbon::parse($item->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY'),
                    'durasi' => CarbonInterval::seconds($tutup->diffInSeconds($buka))->cascade()->forHumans(),
                    'anggota' => [
                        'id' => $item->anggota->id,
                        'nomor_induk' => $item->anggota->nomor_induk,
                        'nomor_anggota' => $item->anggota->nomor_anggota,
                        'foto' => ($item->anggota->foto == null or $item->anggota->foto == '') ? url('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png') : base_url($item->anggota->foto),
                        'nama' => $item->anggota->nama,
                        'jenis_kelamin' => $item->anggota->jenis_kelamin,
                        'jabatan' => $item->anggota->jabatan,
                        'alamat' => $item->anggota->alamat,
                        'status' => $item->anggota->status,
                        'kelas_id' => $item->anggota->kelas_id,
                        'kelas' => $item->anggota->kelas,
                        'kota_id' => $item->anggota->kota_id,
                        'kota' => $item->anggota->kota,

                    ],
                    'buku' => [
                        'id' => $item->buku->id,
                        'judul' => $item->buku->judul,
                        'pengarang' => $item->buku->pengarang,
                        'isbn' => $item->buku->isbn,
                        'foto' => ($item->buku->foto == null or $item->buku->foto == '') ? url('backend/sneat-1.0.0/assets/img/avatars/coverbook.jpg') : base_url($item->buku->foto),
                        'kategori' => $item->buku->kategori->implode('kategori', ', '),
                        'penerbit' => $item->buku->penerbit,
                    ]
                ];
            }

            return $this->responOk(data: $records);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return $this->responError('Terjadi kesalahan saat menampilkan data');
        }
    }
}
