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
        Schema::create('detail_servis_sparepart', function (Blueprint $table) {
            $table->increments('id_detail_sparepart');
            $table->unsignedInteger('id_servis');
            $table->foreign('id_servis')->references('id_servis')->on('servis')->cascadeOnDelete();
            $table->unsignedInteger('id_sparepart');
            $table->foreign('id_sparepart')->references('id_sparepart')->on('sparepart')->cascadeOnDelete();
            $table->integer('qty');
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
        Schema::dropIfExists('detail_servis_sparepart');
    }
};
