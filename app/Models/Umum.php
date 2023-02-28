<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umum extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
}
