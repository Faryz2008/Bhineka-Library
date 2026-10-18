<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab') === 'pengembalian' ? 'pengembalian' : 'peminjaman';
        $dari = $request->dari;
        $sampai = $request->sampai;

        if ($tab === 'peminjaman') {
            $data = Peminjaman::with(['anggota', 'user'])
                ->withSum('details as total_buku', 'jumlah_pinjam')
                ->when($dari, fn ($q, $d) => $q->whereDate('tgl_pinjam', '>=', $d))
                ->when($sampai, fn ($q, $d) => $q->whereDate('tgl_pinjam', '<=', $d))
                ->latest('id_peminjaman')
                ->paginate(10)->withQueryString();
        } else {
            $data = Pengembalian::with(['peminjaman.anggota', 'user'])
                ->when($dari, fn ($q, $d) => $q->whereDate('tgl_kembali', '>=', $d))
                ->when($sampai, fn ($q, $d) => $q->whereDate('tgl_kembali', '<=', $d))
                ->latest('id_pengembalian')
                ->paginate(10)->withQueryString();
        }

        return view('riwayat.index', compact('tab', 'data'));
    }
}