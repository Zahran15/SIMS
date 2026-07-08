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
        Schema::create('histori_aktivitas', function (Blueprint $table) {
            $table->increments('id_histori'); 
            $table->unsignedInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $table->unsignedInteger('id_servis');
            $table->foreign('id_servis')->references('id_servis')->on('servis')->cascadeOnDelete();
            $table->string('aktivitas');
            $table->text('keterangan');
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori_aktivitas');
    }
};
