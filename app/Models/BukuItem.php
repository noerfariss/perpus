<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuItem extends Model
{
    use HasFactory;

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_item_id');
    }

    public function peminjaman_belum_kembali()
    {
        return $this->peminjaman()->where('is_kembali', false);
    }
}
