<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';
    protected $primaryKey = 'id_detail_pengembalian';
    protected $guarded = [];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }

    public function detailPeminjaman()
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail_peminjaman');
    }
}