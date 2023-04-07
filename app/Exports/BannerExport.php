<?php

namespace App\Exports;

use App\Traits\ExportGambar;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class BannerExport implements FromView, WithEvents
{
    use ExportGambar;

    public $data;
    public $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('backend.banner.export', ['data' => $this->data, 'request' => $this->request]);
    }

    public function registerEvents(): array
    {
        if ($this->request['ext'] == 'foto') {
            return [AfterSheet::class => function (AfterSheet $event) {
                $records = $this->data;
                $nomor = 2;
                foreach ($records as $row) {
                    $this->simpanGambar($event, 'C' . $nomor, $row->gambar, 0, 130);
                    $event->sheet->getDelegate()->getRowDimension($nomor)->setRowHeight(100);
                    $nomor++;
                }
            }];
        } else {
            return [];
        }
    }

    public function __destruct()
    {
        if (env('FILESYSTEM_DISK') === 's3') {
            foreach ($this->data as $item) {
                if ($item->gambar) {
                    $url = base_url($item->gambar);
                    $name = substr($url, strrpos($url, '/') + 1);
                    File::delete(public_path('storage/export/' . $name));
                }
            }
        }
    }
}
