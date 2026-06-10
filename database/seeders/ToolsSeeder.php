<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tools')->insert([
            [
                'id_user' => 3,
                'nama_tools' => 'Obeng Set',
                'jumlah' => 5,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'nama_tools' => 'Blower',
                'jumlah' => 2,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'nama_tools' => 'Solder',
                'jumlah' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 5,
                'nama_tools' => 'Multimeter',
                'jumlah' => 1,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'nama_tools' => 'Thermal Gun',
                'jumlah' => 0,
                'status' => 'tidak tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}