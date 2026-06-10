<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pelanggan')->insert([
            [
                'id_pelanggan' => 1,
                'kode_pelanggan' => 'PLG001',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jakarta',
                'no_hp' => '081234567890',
                'email' => 'budi@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pelanggan' => 2,
                'kode_pelanggan' => 'PLG002',
                'nama' => 'Siti Aminah',
                'alamat' => 'Bandung',
                'no_hp' => '082345678901',
                'email' => 'siti@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}