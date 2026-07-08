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
    Schema::create('tools', function (Blueprint $table) {
        $table->increments('id_tools'); 
        $table->unsignedInteger('id_user');
        $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
        $table->string('nama_tools');
        $table->integer('jumlah')->default(0);
        $table->enum('status', ['tersedia', 'tidak tersedia']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
