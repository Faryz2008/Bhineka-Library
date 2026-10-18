<?php

namespace App\Imports;

use App\Models\Anggota;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AnggotaImport implements ToCollection, WithHeadingRow
{
    public int $jumlah = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            if (blank($row['nis_nip'] ?? null) || blank($row['nama'] ?? null)) {
                continue;
            }

            $jenis = strtolower(trim($row['jenis'] ?? '')) === 'guru' ? 'guru' : 'siswa';

            Anggota::updateOrCreate(
                ['nis_nip' => trim((string) $row['nis_nip'])],
                [
                    'nama' => trim($row['nama']),
                    'jenis' => $jenis,
                    'kelas' => $jenis === 'siswa' ? ($row['kelas'] ?? null) : null,
                    'no_telp' => $row['no_telp'] ?? null,
                ]
            );

            $this->jumlah++;
        }
    }
}