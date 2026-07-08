<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Default untuk Login Awal (Admin, Owner, & Teknisi Asli)
        $users = [
            [
                'nama' => 'Super Admin',
                'role' => 'admin',
                'email' => 'admin@gmail.com',
                'no_hp' => '081234567890',
                'password' => Hash::make('admin'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Chief Owner',
                'role' => 'owner',
                'email' => 'owner@gmail.com',
                'no_hp' => '081234567891',
                'password' => Hash::make('owner'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Teknisi Utama',
                'role' => 'teknisi',
                'email' => 'teknisi@gmail.com',
                'no_hp' => '081234567892',
                'password' => Hash::make('teknisi'), 
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // 2. Data Dummy Tambahan (Menggunakan Faker)
        $faker = Faker::create('id_ID'); 

        for ($i = 0; $i < 25; $i++) {
            DB::table('users')->insert([
                'nama' => $faker->name,
                'role' => $faker->randomElement(['admin', 'teknisi']),
                'email' => $faker->unique()->safeEmail,
                'no_hp' => $faker->phoneNumber,
                'password' => Hash::make('password123'), 
                'status' => $faker->randomElement(['aktif', 'nonaktif']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}