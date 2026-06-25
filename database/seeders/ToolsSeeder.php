<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ToolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua id_user yang memiliki role 'teknisi'
        $idTeknisiList = DB::table('users')->where('role', 'teknisi')->pluck('id_user')->toArray();

        // Antisipasi: Jika belum ada user dengan role teknisi sama sekali di database
        if (empty($idTeknisiList)) {
            $idTeknisiBaru = DB::table('users')->insertGetId([
                'nama' => 'Teknisi Utama',
                'role' => 'teknisi',
                'email' => 'teknisi@gmail.com',
                'no_hp' => '085712345678',
                'password' => Hash::make('password123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $idTeknisiList[] = $idTeknisiBaru;
        }

        // 2. Data Tools / Peralatan Servis Riil
        $toolsUtama = [
            [
                'id_user' => $idTeknisiList[0], // Dikaitkan ke teknisi pertama
                'nama_tools' => 'Obeng Set Presisi (Anti Statis)',
                'jumlah' => 3,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $idTeknisiList[0],
                'nama_tools' => 'Solder Station Digital + Blower',
                'jumlah' => 2,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $idTeknisiList[0],
                'nama_tools' => 'Multitester / Avometer Digital',
                'jumlah' => 2,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $idTeknisiList[0],
                'nama_tools' => 'Pinset Lengkung & Lurus',
                'jumlah' => 5,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $idTeknisiList[0],
                'nama_tools' => 'Flash Programmer IC (RT809F/H)',
                'jumlah' => 0, // Contoh alat sedang dipinjam/habis
                'status' => 'tidak tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tools')->insert($toolsUtama);

        // 3. Tambahan Data Dummy Acak menggunakan Faker
        $faker = Faker::create('id_ID');
        $namaAlatDummy = ['Tang Potong', 'Kaca Pembesar LED', 'Alat Pembuka Casing', 'DC Power Supply', 'Separator LCD'];

        for ($i = 0; $i < 5; $i++) {
            $jumlah = $faker->numberBetween(0, 4);
            $status = ($jumlah > 0) ? 'tersedia' : 'tidak tersedia';

            DB::table('tools')->insert([
                // Mengambil secara acak dari daftar id_user teknisi yang tersedia
                'id_user' => $faker->randomElement($idTeknisiList),
                'nama_tools' => $faker->randomElement($namaAlatDummy) . ' ' . $faker->firstName(),
                'jumlah' => $jumlah,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}