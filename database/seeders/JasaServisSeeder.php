<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JasaServisSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jasa_servis')->insert([
            [
                'nama_jasa' => 'Cleaning Laptop',
                'harga' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Install Ulang OS',
                'harga' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Ganti Thermal Paste',
                'harga' => 40000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Perbaikan Keyboard',
                'harga' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jasa' => 'Servis Mati Total',
                'harga' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}