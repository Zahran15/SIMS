<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil semua data servis untuk skenario pelunasan
        $servisList = DB::table('servis')->get();
        // 2. Ambil semua data booking untuk skenario DP
        $bookingList = DB::table('booking')->get();

        if ($bookingList->isEmpty()) {
            return;
        }

        // --- SKENARIO 1: Pembayaran DP (Dari tabel Booking) ---
        foreach ($bookingList as $booking) {
            // Kita buat transaksi DP jika status DP di booking adalah 'sudah lunas' atau acak untuk testing pending
            $statusBayar = $booking->status_dp === 'sudah lunas' ? 'sukses' : $faker->randomElement(['pending', 'gagal']);
            $metode = $faker->randomElement(['cash', 'transfer']);
            
            $isMidtrans = $metode === 'transfer';
            $nominalDp = 50000.00; // Kita patok DP rata-rata 50rb

            DB::table('pembayaran')->insert([
                'id_booking' => $booking->id_booking,
                'id_servis' => null, // DP biasanya belum ada id_servis
                'jenis_pembayaran' => 'dp',
                'metode_pembayaran' => $metode,
                'nominal' => $nominalDp,
                'status_pembayaran' => $statusBayar,
                'snap_token' => $isMidtrans ? 'st_' . Str::random(40) : null,
                'midtrans_order_id' => $isMidtrans ? 'DP-' . strtoupper(Str::random(10)) : null,
                'midtrans_transaction_id' => $isMidtrans && $statusBayar === 'sukses' ? $faker->uuid : null,
                'tanggal_bayar' => $statusBayar === 'sukses' ? $booking->tgl_booking . ' 10:00:00' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // --- SKENARIO 2: Pembayaran Pelunasan (Dari tabel Servis) ---
        foreach ($servisList as $servis) {
            // Hanya buat data pelunasan jika total biayanya lebih dari 0 dan statusnya mendukung
            if ($servis->total_biaya <= 0) {
                continue;
            }

            // Cek apakah booking pasangannya sudah bayar DP sukses sebelumnya
            $bookingTerkait = DB::table('booking')->where('id_booking', $servis->id_booking)->first();
            $sudahPotongDp = $bookingTerkait && $bookingTerkait->status_dp === 'sudah lunas';
            
            // Nominal pelunasan = total biaya - nominal DP (jika ada DP)
            $nominalPelunasan = $sudahPotongDp ? ($servis->total_biaya - 50000) : $servis->total_biaya;
            if ($nominalPelunasan < 0) { $nominalPelunasan = 0; }

            $statusBayar = $servis->status_pelunasan === 'sudah lunas' ? 'sukses' : $faker->randomElement(['pending', 'gagal']);
            $metode = $faker->randomElement(['cash', 'transfer']);
            $isMidtrans = $metode === 'transfer';

            DB::table('pembayaran')->insert([
                'id_booking' => $servis->id_booking,
                'id_servis' => $servis->id_servis,
                'jenis_pembayaran' => 'pelunasan',
                'metode_pembayaran' => $metode,
                'nominal' => $nominalPelunasan,
                'status_pembayaran' => $statusBayar,
                'snap_token' => $isMidtrans ? 'st_' . Str::random(40) : null,
                'midtrans_order_id' => $isMidtrans ? 'PLN-' . strtoupper(Str::random(10)) : null,
                'midtrans_transaction_id' => $isMidtrans && $statusBayar === 'sukses' ? $faker->uuid : null,
                'tanggal_bayar' => $statusBayar === 'sukses' ? $servis->tgl_masuk . ' 16:30:00' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}