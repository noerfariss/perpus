<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategori');
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id');
    }
}
