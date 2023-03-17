<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku_item()
    {
        return $this->belongsTo(BukuItem::class, 'buku_item_id');
    }

    public function denda()
    {
        return $this->hasMany(Denda::class, 'peminjaman_id');
    }
}
