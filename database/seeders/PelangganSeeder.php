<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $alamat = [
            'Jl. Gatot Subroto Cilacap',
            'Jl. Jenderal Sudirman Cilacap',
            'Jl. Ahmad Yani Kroya',
            'Jl. Diponegoro Sidareja',
            'Jl. Raya Maos',
            'Jl. Pemuda Majenang',
        ];

        for ($i = 1; $i <= 15; $i++) {
            $nama = $faker->name();
            DB::table('pelanggan')->insert([
                'kode_pelanggan' => 'PLG' . str_pad($i,3,'0',STR_PAD_LEFT),
                'nama' => $nama,
                'alamat' => $faker->randomElement($alamat),
                'no_hp' => '08' . $faker->numerify('##########'),
                'email' => Str::slug($nama) . $i . '@gmail.com',
                'password' => Hash::make('pelanggan123'),
                'status' => 'aktif',
                'created_at' => now()->subDays(rand(10,100)),
                'updated_at' => now(),
            ]);
        }
    }
}