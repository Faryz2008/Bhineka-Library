<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail_peminjaman';
    protected $guarded = [];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}