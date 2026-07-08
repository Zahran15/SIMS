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
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_booking');
            $table->foreign('id_booking')->references('id_booking')->on('booking')->cascadeOnDelete();
            $table->unsignedInteger('id_servis')->nullable();
            $table->foreign('id_servis')->references('id_servis')->on('servis')->nullOnDelete();
            $table->enum('jenis_pembayaran', ['dp', 'pelunasan']);
            $table->enum('metode_pembayaran', ['cash', 'transfer']);
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
