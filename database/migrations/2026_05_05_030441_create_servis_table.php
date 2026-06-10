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
            $table->id ('id_servis');
            $table->foreignId('id_booking')->constrained('booking', 'id_booking')->cascadeOnDelete();
            $table->string('kode_servis')->unique();
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
