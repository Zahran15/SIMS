<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->cascadeOnDelete();
            $table->string('kode_booking')->unique();
            $table->date('tgl_booking');
            $table->string('merk_tipe');
            $table->string('spesifikasi');
            $table->string('keluhan');
            $table->enum('metode_pengambilan', ['diantar', 'ambil sendiri']);
            $table->text('kelengkapan');
            $table->enum('kategori_servis', ['ringan', 'sedang', 'berat']);
            $table->enum('status_deposit', ['belum lunas', 'sudah lunas']);
            $table->enum('status_booking', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
