<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PengadaanSparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil semua data master sparepart yang tersedia
        $sparepartList = DB::table('sparepart')->get();

        // Fallback keamanan jika data master sparepart kosong
        if ($sparepartList->isEmpty()) {
            return;
        }

        // 2. Generate data pengadaan dummy (misal 8 data transaksi pengadaan)
        for ($i = 0; $i < 8; $i++) {
            // Ambil sparepart secara acak
            $sparepart = $faker->randomElement($sparepartList);
            
            // Set jumlah restock barang antara 5 sampai 20 pcs
            $jumlah = $faker->numberBetween(5, 20);

            // Logic bisnis: harga_beli diset lebih murah (~20% lebih rendah) dari harga_jual di master
            $hargaBeli = round(($sparepart->harga_jual * 0.8), -3); // Dibulatkan ke ribuan terdekat
            
            // Hitung total harga pengadaan
            $total = $jumlah * $hargaBeli;

            DB::table('pengadaan_sparepart')->insert([
                'id_sparepart' => $sparepart->id_sparepart,
                // Tanggal pengadaan acak dalam 30 hari ke belakang
                'tgl_pesan' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'jumlah' => $jumlah,
                'harga_beli' => $hargaBeli,
                'total' => $total,
                'status_pengadaan' => $faker->randomElement(['dipesan', 'diterima', 'dibatalkan']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}