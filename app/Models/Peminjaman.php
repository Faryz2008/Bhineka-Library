<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date',
            'tgl_jatuh_tempo' => 'date',
        ];
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }

    public function hitungHariTelat($tglKembali = null): int
    {
        $acuan = $tglKembali ? Carbon::parse($tglKembali)->startOfDay() : Carbon::today();
        $jatuhTempo = $this->tgl_jatuh_tempo->copy()->startOfDay();

        return $acuan->greaterThan($jatuhTempo) ? (int) $jatuhTempo->diffInDays($acuan) : 0;
    }
}