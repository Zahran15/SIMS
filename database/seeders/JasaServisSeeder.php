<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JasaServisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('jasa_servis')->insert([
            [
                'nama_jasa' => 'Cleaning Laptop',
                'harga' => 75000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Ganti Thermal Paste',
                'harga' => 50000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Instal Ulang Windows',
                'harga' => 100000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Instal Driver',
                'harga' => 50000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Backup Data',
                'harga' => 75000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Upgrade RAM',
                'harga' => 50000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Pemasangan SSD',
                'harga' => 75000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Penggantian Keyboard',
                'harga' => 100000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Perbaikan Touchpad',
                'harga' => 80000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Perbaikan Engsel Laptop',
                'harga' => 150000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Perbaikan Port Charger',
                'harga' => 100000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Perbaikan Port USB',
                'harga' => 100000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Penggantian LCD',
                'harga' => 150000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Perbaikan Motherboard',
                'harga' => 250000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jasa' => 'Penggantian Baterai',
                'harga' => 75000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}