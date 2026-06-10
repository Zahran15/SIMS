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
            $table->id('id_request');
            $table->foreignId('id_penugasan')->constrained('penugasan_teknisi', 'id_penugasan')->cascadeOnDelete();
            $table->foreignId('id_sparepart')->constrained('sparepart', 'id_sparepart')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->text('alasan');
            $table->enum('status_request', ['pending', 'disetujui', 'ditolak'])->default('pending');
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
