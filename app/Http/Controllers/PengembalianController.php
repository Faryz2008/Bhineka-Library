<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with('anggota')
            ->withSum('details as total_buku', 'jumlah_pinjam')
            ->where('status', 'dipinjam')
            ->when($request->q, fn ($q, $s) => $q->whereHas('anggota', fn ($a) => $a
                ->where('nama', 'like', "%{$s}%")
                ->orWhere('nis_nip', 'like', "%{$s}%")))
            ->orderBy('tgl_jatuh_tempo')
            ->paginate(10)->withQueryString();

        return view('pengembalian.index', compact('peminjaman'));
    }

    public function create(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('pengembalian.index')->withErrors('Transaksi ini sudah dikembalikan.');
        }

        $peminjaman->load(['anggota', 'details.buku']);

        $hariTelat = $peminjaman->hitungHariTelat(today());
        $totalBuku = $peminjaman->details->sum('jumlah_pinjam');
        $tarif = config('perpustakaan.denda_per_hari');
        $denda = $hariTelat * $totalBuku * $tarif;

        return view('pengembalian.create', compact('peminjaman', 'hariTelat', 'totalBuku', 'tarif', 'denda'));
    }

    public function store(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('pengembalian.index')->withErrors('Transaksi ini sudah dikembalikan.');
        }

        $data = $request->validate([
            'detail' => 'required|array',
            'detail.*.kondisi' => 'required|in:baik,rusak,hilang',
            'detail.*.keterangan' => 'nullable|string|max:255',
        ]);

        $pengembalian = DB::transaction(function () use ($peminjaman, $data) {
            $peminjaman->load('details');

            $hariTelat = $peminjaman->hitungHariTelat(today());
            $totalBuku = $peminjaman->details->sum('jumlah_pinjam');

            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_user' => Auth::id(),
                'tgl_kembali' => today(),
                'total_denda' => $hariTelat * $totalBuku * config('perpustakaan.denda_per_hari'),
            ]);

            foreach ($peminjaman->details as $detail) {
                $input = $data['detail'][$detail->id_detail_peminjaman] ?? ['kondisi' => 'baik', 'keterangan' => null];

                $pengembalian->details()->create([
                    'id_detail_peminjaman' => $detail->id_detail_peminjaman,
                    'kondisi_kembali' => $input['kondisi'],
                    'keterangan' => $input['keterangan'] ?? null,
                ]);

                // buku hilang tidak menambah stok
                if ($input['kondisi'] !== 'hilang') {
                    Buku::where('id_buku', $detail->id_buku)->increment('stok', $detail->jumlah_pinjam);
                }
            }

            $peminjaman->update(['status' => 'dikembalikan']);

            return $pengembalian;
        });

        $pesan = 'Pengembalian berhasil dicatat.';
        if ($pengembalian->total_denda > 0) {
            $pesan .= ' Denda: Rp ' . number_format($pengembalian->total_denda, 0, ',', '.');
        }

        return redirect()->route('pengembalian.index')->with('success', $pesan);
    }
}