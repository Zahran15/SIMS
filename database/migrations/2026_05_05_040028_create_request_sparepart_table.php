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
        Schema::create('request_sparepart', function (Blueprint $table) {
            $table->increments('id_request');
            $table->unsignedInteger('id_penugasan');
            $table->foreign('id_penugasan')->references('id_penugasan')->on('penugasan_teknisi')->cascadeOnDelete();
            $table->unsignedInteger('id_sparepart');
            $table->foreign('id_sparepart')->references('id_sparepart')->on('sparepart')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->text('alasan');
            $table->enum('status_request', ['pending_admin','dikirim_ke_pelanggan','disetujui_pelanggan','disetujui','ditolak'])->default('pending_admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_sparepart');
    }
};
