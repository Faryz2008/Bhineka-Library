<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        foreach (['Fiksi', 'Non-Fiksi', 'Pelajaran', 'Referensi'] as $nama) {
            Kategori::create(['nama_kategori' => $nama]);
        }
    }
}