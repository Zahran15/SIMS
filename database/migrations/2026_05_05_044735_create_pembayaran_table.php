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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_booking')->constrained('booking','id_booking')->cascadeOnDelete();
            $table->foreignId('id_servis')->nullable()->constrained('servis', 'id_servis')->nullOnDelete();
            $table->enum('jenis_pembayaran', ['deposit', 'pelunasan']);
            $table->enum('metode_pembayaran', ['cash', 'midtrans']);
            $table->decimal('nominal', 12, 2);
            $table->enum('status_pembayaran', ['pending', 'sukses', 'gagal'])->default('pending');
            $table->string('snap_token')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
