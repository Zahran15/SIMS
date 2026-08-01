<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // Admin
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

            // Owner
            [
                'nama' => 'Budi Santoso',
                'role' => 'owner',
                'email' => 'owner@gmail.com',
                'no_hp' => '081234567891',
                'password' => Hash::make('owner'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Teknisi 1
            [
                'nama' => 'Andi Pratama',
                'role' => 'teknisi',
                'email' => 'teknisi1@gmail.com',
                'no_hp' => '081234567892',
                'password' => Hash::make('teknisi'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Teknisi 2
            [
                'nama' => 'Rizky Saputra',
                'role' => 'teknisi',
                'email' => 'teknisi2@gmail.com',
                'no_hp' => '081234567893',
                'password' => Hash::make('teknisi'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Teknisi 3
            [
                'nama' => 'Fajar Nugroho',
                'role' => 'teknisi',
                'email' => 'teknisi3@gmail.com',
                'no_hp' => '081234567894',
                'password' => Hash::make('teknisi'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}