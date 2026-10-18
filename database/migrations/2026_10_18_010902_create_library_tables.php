<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('buku', function (Blueprint $table) {
            $table->id('id_buku');
            $table->string('kode_buku', 30)->unique();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->unsignedInteger('stok')->default(0);
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->string('nis_nip', 30)->unique();
            $table->string('nama');
            $table->enum('jenis', ['siswa', 'guru']);
            $table->string('kelas', 20)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->foreignId('id_anggota')->constrained('anggota', 'id_anggota')->restrictOnDelete();
            $table->foreignId('id_user')->constrained('users', 'id_user')->restrictOnDelete();
            $table->date('tgl_pinjam');
            $table->date('tgl_jatuh_tempo');
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });

        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id('id_detail_peminjaman');
            $table->foreignId('id_peminjaman')->constrained('peminjaman', 'id_peminjaman')->cascadeOnDelete();
            $table->foreignId('id_buku')->constrained('buku', 'id_buku')->restrictOnDelete();
            $table->unsignedInteger('jumlah_pinjam');
            $table->string('kondisi_pinjam', 20)->default('baik');
            $table->timestamps();
        });

        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id('id_pengembalian');
            $table->foreignId('id_peminjaman')->constrained('peminjaman', 'id_peminjaman')->restrictOnDelete();
            $table->foreignId('id_user')->constrained('users', 'id_user')->restrictOnDelete();
            $table->date('tgl_kembali');
            $table->unsignedInteger('total_denda')->default(0);
            $table->timestamps();
        });

        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id('id_detail_pengembalian');
            $table->foreignId('id_pengembalian')->constrained('pengembalian', 'id_pengembalian')->cascadeOnDelete();
            $table->foreignId('id_detail_peminjaman')->constrained('detail_peminjaman', 'id_detail_peminjaman')->restrictOnDelete();
            $table->string('kondisi_kembali', 20)->default('baik');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
        Schema::dropIfExists('pengembalian');
        Schema::dropIfExists('detail_peminjaman');
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('anggota');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('kategori');
    }
};