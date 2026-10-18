<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tgl_jatuh_tempo', '<', today())->count();
        $terbaru = Peminjaman::with('anggota')->latest('id_peminjaman')->take(5)->get();

        return view('dashboard', compact('totalBuku', 'totalAnggota', 'sedangDipinjam', 'terlambat', 'terbaru'));
    }
}