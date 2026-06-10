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
            $table->id('id_detail_sparepart');
            $table->foreignId('id_servis')->constrained('servis', 'id_servis')->cascadeOnDelete();
            $table->foreignId('id_sparepart')->constrained('sparepart', 'id_sparepart')->cascadeOnDelete();
            $table->integer('qty')->default(1);
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
