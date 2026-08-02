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
        $table->increments('id_booking'); 
        $table->unsignedInteger('id_pelanggan');
        $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->cascadeOnDelete();
        $table->string('kode_booking', 20);
        $table->date('tgl_booking');
        $table->string('merk_tipe', 30);
        $table->string('spesifikasi', 100);
        $table->text('keluhan');
        $table->enum('metode_pengembalian', ['diantar', 'ambil sendiri']);
        $table->text('kelengkapan');
        $table->enum('kategori_servis', ['ringan', 'sedang', 'berat']);
        $table->enum('status_dp', ['belum lunas', 'sudah lunas']);
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
