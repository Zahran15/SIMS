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
        // 1. Data Jasa Servis Laptop yang Umum/Riil
        $jasaUtama = [
            [
                'nama_jasa' => 'Servis Ringan / Pembersihan Berkala',
                'harga' => 75000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Servis Berat / Overhaul Total',
                'harga' => 250000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Ganti Thermal Paste',
                'harga' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Instal Ulang Sistem Operasi (Windows/Linux)',
                'harga' => 100000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Perbaikan Keyboard / Touchpad',
                'harga' => 80000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Penggantian SSD/HDD',
                'harga' => 150000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Upgrade RAM',
                'harga' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Perbaikan Engsel / Casing Laptop',
                'harga' => 100000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('jasa_servis')->insert($jasaUtama);

        // 2. Tambahan Data Dummy Acak (Opsional menggunakan Faker)
        $faker = Faker::create('id_ID');

        $jenisLayanan = ['Perbaikan', 'Pembersihan', 'Pengecekan', 'Upgrade', 'Instalasi', 'Kalibrasi'];
        $komponenLaptop = [
            'Motherboard',
            'Layar LCD',
            'Baterai',
            'Kipas Pendingin (Fan)',
            'Port USB',
            'Adaptor Charger',
            'Speaker',
            'Webcam',
            'Wifi Card',
            'BIOS',
        ];

        for ($i = 0; $i < 5; $i++) {
            DB::table('jasa_servis')->insert([
                // Contoh: "Perbaikan Motherboard", "Pembersihan Kipas Pendingin (Fan)"
                'nama_jasa' => $faker->randomElement($jenisLayanan) . ' ' . $faker->randomElement($komponenLaptop),
                // Generate harga kelipatan 5.000 antara 30.000 sampai 200.000 agar terlihat rapi
                'harga' => $faker->numberBetween(6, 40) * 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}