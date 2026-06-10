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
        Schema::create('penugasan_teknisi', function (Blueprint $table) {
            $table->id('id_penugasan');
            $table->foreignId('id_servis')->constrained('servis', 'id_servis')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->enum('prioritas', ['rendah', 'normal', 'tinggi', 'urgent']);
            $table->date('estimasi_selesai');
            $table->enum('status_penugasan', ['belum dikerjakan', 'sedang dikerjakan', 'menunggu sparepart', 'selesai', 'gagal']);
            $table->text('catatan_teknisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_teknisi');
    }
};
