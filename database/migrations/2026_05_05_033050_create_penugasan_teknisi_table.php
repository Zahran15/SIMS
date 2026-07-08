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
            $table->increments('id_penugasan'); 
            $table->unsignedInteger('id_servis');
            $table->foreign('id_servis')->references('id_servis')->on('servis')->cascadeOnDelete();
            $table->unsignedInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $table->enum('prioritas', ['ringan', 'sedang', 'berat'])->nullable();
            $table->date('estimasi_selesai')->nullable();
            $table->enum('status_penugasan', ['belum dikerjakan','sedang dikerjakan','menunggu sparepart','selesai','gagal']);
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
