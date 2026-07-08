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
        Schema::create('detail_servis_jasa', function (Blueprint $table) {
            $table->increments('id_detail_jasa'); 
            $table->unsignedInteger('id_servis');
            $table->foreign('id_servis')->references('id_servis')->on('servis')->cascadeOnDelete();
            $table->unsignedInteger('id_jasa');
            $table->foreign('id_jasa')->references('id_jasa')->on('jasa_servis')->cascadeOnDelete();
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_servis_jasa');
    }
};
