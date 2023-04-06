<?php

use App\Models\BukuItem;
use App\Models\Kategori;
use App\Models\Penerbit;
use App\Models\Umum;
use Illuminate\Support\Facades\DB;

function menuAktif($url = NULL)
{
    $openUrl = url()->current();
    $parse = parse_url($openUrl, PHP_URL_PATH);
    $explode = explode('/', $parse);
    $newUrl = '/' . $explode[1] . '/' . $explode[2];

    $path = '/auth';

    if ($url <> NULL) {
        if (gettype($url) === 'string') {
            return ($newUrl === $path . '/' . $url) ? 'active' : '';
        } else {
            $listUrl = [];
            foreach ($url as $item) {
                $listUrl[] = $path . '/' . $item;
            }

            if (in_array($newUrl, $listUrl)) {
                return 'open active';
            }
        }
    }
}


function statusBtn()
{
    return '<div class="btn-group float-end" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="status" value="1" id="status1" autocomplete="off" checked="checked" onclick="datatables.ajax.reload()">
                <label class="btn btn-outline-warning btn-xs btn-check-label" for="status1">Aktif</label>

                <input type="radio" class="btn-check" name="status" value="0" id="status2" autocomplete="off" onclick="datatables.ajax.reload()">
                <label class="btn btn-outline-warning btn-xs btn-check-label" for="status2">Nonaktif</label>
            </div>';
}


function exportBtn($tipe = [], $url = '', $filename = '')
{
    if (gettype($tipe) === 'array' && !empty($tipe)) {
        $btn = '<div class="float-end me-2">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bxs-file-doc"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="">';
        $btn .= (in_array('data', $tipe)) ? '<li><a class="dropdown-item btn-export" href="' . $url . '" data-ext="data" data-filename="' . $filename . '"><i class="bx bxs-spreadsheet"></i> Data</a></li>' : '';
        $btn .= (in_array('foto', $tipe)) ? '<li><a class="dropdown-item btn-export" href="' . $url . '" data-ext="foto" data-filename="' . $filename . '"><i class="bx bxs-file-image"></i> Data + Foto</a></li>' : '';
        $btn .= (in_array('pdf', $tipe)) ? '<li><a class="dropdown-item btn-export" href="' . $url . '" data-ext="pdf" data-filename="' . $filename . '"><i class="bx bxs-file-pdf"></i> PDF</a></li>' : '';
        $btn .= '</ul>
                </div>';

        return $btn;
    } else if (gettype($tipe) === 'string') {
        $btn = '<div class="float-end me-2">
                    <a class="btn btn-sm btn-outline-primary btn-export" href="' . $url . '" data-ext="data" data-filename="' . $filename . '"><i class="bx bxs-file-doc float-start"></i> Export</a>';
        $btn .= '</div>';

        return $btn;
    }
}

function pecahTanggal($tanggal)
{
    if (str_contains($tanggal, 'to')) {
        $pecah = explode(' ', $tanggal);
        $tmulai = trim($pecah[0]);
        $tkahir = trim($pecah[2]);
    } else {
        $tmulai = $tanggal;
        $tkahir = $tanggal;
    }

    return [$tmulai, $tkahir];
}

function zona_waktu()
{
    return Umum::first()->timezone;
}

function getKodePenerbit()
{
    $kodePenerbit = Penerbit::orderBy('id', 'desc')->first();
    if ($kodePenerbit === NULL) {
        $kode = 'KP0001';
    } else {
        $getKode = $kodePenerbit->kode;
        $pecah = (int) substr($getKode, 2);
        $newKode = $pecah + 1;
        $kode = 'KP' . str_pad($newKode, 4, '0', STR_PAD_LEFT);
    }

    return $kode;
}

function getKodeKategori()
{
    $kodePenerbit = Kategori::orderBy('id', 'desc')->first();
    if ($kodePenerbit === NULL) {
        $kode = 'KG0001';
    } else {
        $getKode = $kodePenerbit->kode;
        $pecah = (int) substr($getKode, 2);
        $newKode = $pecah + 1;
        $kode = 'KG' . str_pad($newKode, 4, '0', STR_PAD_LEFT);
    }

    return $kode;
}

function getKodeBuku()
{
    $kodePenerbit = BukuItem::orderBy('id', 'desc')->first();
    if ($kodePenerbit === NULL) {
        $kode = 'BK00001';
    } else {
        $getKode = $kodePenerbit->kode;
        $pecah = (int) substr($getKode, 2);
        $newKode = $pecah + 1;
        $kode = 'BK' . str_pad($newKode, 5, '0', STR_PAD_LEFT);
    }

    return $kode;
}

function getKodeTransaksi()
{
    $no_transaksi = DB::table('peminjaman_transaksi')
        ->whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->orderBy('id', 'desc')
        ->first();

    if ($no_transaksi === null) {
        $nomor = 'PJ' . date('Y') . '' . date('m') . '0001';
    } else {

        $tahun = substr($no_transaksi->kode, 2, 4);
        $bulan = substr($no_transaksi->kode, 6, 2);
        $urutan = (int) substr($no_transaksi->kode, 8);
        $newUrutan = $urutan + 1;

        $nomor = 'PJ' . $tahun . $bulan . str_pad($newUrutan, 4, '0', STR_PAD_LEFT);
    }

    return  $nomor;
}

function s3_url($path)
{
    return 'https://s3.ap-southeast-1.amazonaws.com/' . env('AWS_BUCKET') . '/' . $path;
}

function base_url($path)
{
    return env('FILESYSTEM_DISK') === 's3' ? s3_url($path) : url('/storage') . '/' . $path;
}
