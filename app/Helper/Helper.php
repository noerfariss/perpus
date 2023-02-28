<?php

use App\Models\mobile_agent;
use App\Models\outlet;
use App\Models\Umum;

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
