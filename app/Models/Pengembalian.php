<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';
    protected $guarded = [];

    protected function casts(): array
    {
        return ['tgl_kembali' => 'date'];
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailPengembalian::class, 'id_pengembalian');
    }
}