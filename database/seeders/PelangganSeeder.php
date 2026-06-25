<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan format data Indonesia
        
        // Membuat 15 data dummy pelanggan
        for ($i = 1; $i <= 15; $i++) {
            // Generate kode pelanggan otomatis, misal: PLG001, PLG002, ..., PLG015
            $kodePelanggan = 'PLG' . str_pad($i, 3, '0', STR_PAD_LEFT);

            DB::table('pelanggan')->insert([
                'kode_pelanggan' => $kodePelanggan,
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('pelanggan123'), // Password default untuk testing login pelanggan
                'status' => $faker->randomElement(['aktif', 'nonaktif']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}