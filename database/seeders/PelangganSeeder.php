<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 
use Faker\Factory as Faker;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan format data Indonesia
        
        // Membuat 25 data dummy pelanggan
        for ($i = 1; $i <= 25; $i++) {
            // Generate kode pelanggan otomatis, misal: PLG001, PLG002
            $kodePelanggan = 'PLG' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // 1. Ambil nama random dari Faker
            $nama = $faker->name;

            // 2. Buat email custom berbasis nama + angka unik + @gmail.com
            // Str::slug mengubah "Budi Utomo" menjadi "budi-utomo", lalu kita hilangkan strip-nya
            $username = str_replace('-', '', Str::slug($nama));
            $email = $username . $i . '@gmail.com'; 

            DB::table('pelanggan')->insert([
                'kode_pelanggan' => $kodePelanggan,
                'nama' => $nama,
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'email' => $email, // Menggunakan email @gmail.com yang sudah dibuat
                'password' => Hash::make('pelanggan123'), 
                'status' => $faker->randomElement(['aktif', 'nonaktif']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}