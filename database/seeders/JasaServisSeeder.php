<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class JasaServisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Jasa Servis yang Umum/Riil
        $jasaUtama = [
            [
                'nama_jasa' => 'Servis Ringan / Berkala',
                'harga' => 75000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Servis Berat / Overhaul',
                'harga' => 250000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Ganti Oli Mesin',
                'harga' => 20000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Tune Up Sistem Elektronik',
                'harga' => 120000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Perbaikan Sistem Pengereman',
                'harga' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('jasa_servis')->insert($jasaUtama);

        // 2. Tambahan Data Dummy Acak (Opsional menggunakan Faker)
        $faker = Faker::create('id_ID');
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('jasa_servis')->insert([
                // Contoh: "Instalasi Komponen Tambahan A", "Pengecekan Rutin B"
                'nama_jasa' => $faker->randomElement(['Perbaikan', 'Pembersihan', 'Pengecekan', 'Modifikasi']) . ' ' . $faker->word(),
                // Generate harga kelipatan 5.000 antara 30.000 sampai 200.000 agar terlihat rapi
                'harga' => $faker->numberBetween(6, 40) * 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}