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
    Schema::create('servis', function (Blueprint $table) {
        $table->increments('id_servis'); 
        $table->unsignedInteger('id_booking');
        $table->foreign('id_booking')->references('id_booking')->on('booking')->cascadeOnDelete();
        $table->string('kode_servis', 20);
        $table->date('tgl_masuk');
        $table->date('perkiraan_selesai');
        $table->enum('status_servis', ['menunggu', 'proses', 'selesai', 'bisa diambil', 'sudah diambil', 'dibatalkan'])->default('menunggu');
        $table->enum('status_pelunasan', ['belum lunas', 'sudah lunas'])->default('belum lunas');
        $table->decimal('total_biaya', 12, 2)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
