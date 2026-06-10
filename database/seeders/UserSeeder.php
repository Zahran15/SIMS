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
        $users = [
            [
                'role' => 'admin',
                'nama' => 'Administrator Sistem',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
            ],
            [
                'role' => 'owner',
                'nama' => 'Owner Perusahaan',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('owner'),
            ],
            [
                'role' => 'teknisi',
                'nama' => 'Teknisi Lapangan',
                'email' => 'teknisi@gmail.com',
                'password' => Hash::make('teknisi'),
            ],
            [
                'role' => 'teknisi',
                'nama' => 'Teknisi Cadangan',
                'email' => 'teknisi2@gmail.com',
                'password' => Hash::make('teknisi2'),
            ],
            [
                'role' => 'teknisi',
                'nama' => 'Teknisi Cadangan 2',
                'email' => 'teknisi3@gmail.com',
                'password' => Hash::make('teknisi3'),
            ],

        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'role' => $user['role'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'password' => $user['password'],
                'status' => 'aktif',
                'no_hp' => '08123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}