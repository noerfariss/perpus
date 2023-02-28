<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class UserExport implements FromView, WithEvents
{
    public $data;
    public $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('backend.users.export', ['data' => $this->data, 'request' => $this->request]);
    }

    public function registerEvents(): array
    {
        if ($this->request['ext'] == 'foto') {
            return [AfterSheet::class => function (AfterSheet $event) {
                $records = $this->data;
                $nomor = 2;
                foreach ($records as $row) {
                    $this->setImage2Excel($event, 'G' . $nomor, $row->foto, 0, 130);
                    $event->sheet->getDelegate()->getRowDimension($nomor)->setRowHeight(100);
                    $nomor++;
                }
            }];
        } else {
            return [];
        }
    }

    private function setImage2Excel($event, $position, $path, $width, $height)
    {
        if ($path != '' || $path != null) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setCoordinates($position);
            $drawing->setPath(public_path('storage/foto/' . $path));
            ($width == 0) ? null : $drawing->setWidth($width);
            ($height == 0) ? null : $drawing->setHeight($height);
            $drawing->setWorksheet($event->sheet->getDelegate());
        }
    }
}
