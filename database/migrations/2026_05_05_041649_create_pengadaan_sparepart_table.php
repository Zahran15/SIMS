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
        Schema::create('pengadaan_sparepart', function (Blueprint $table) {
            $table->id('id_pengadaan');
            $table->foreignId('id_sparepart')->constrained('sparepart', 'id_sparepart')->cascadeOnDelete();
            $table->date('tgl_pesan');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('total', 12, 2);
            $table->enum('status_pengadaan', ['dipesan', 'diterima', 'dibatalkan'])->default('dipesan'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_sparepart');
    }
};
