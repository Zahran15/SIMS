<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparepartSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sparepart')->insert([
            [
                'nama_sparepart' => 'RAM 8GB DDR4',
                'kategori' => 'RAM',
                'stok' => 10,
                'harga_jual' => 300000,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'SSD 256GB',
                'kategori' => 'Storage',
                'stok' => 8,
                'harga_jual' => 500000,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Keyboard Laptop',
                'kategori' => 'Keyboard',
                'stok' => 5,
                'harga_jual' => 250000,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Baterai Laptop',
                'kategori' => 'Baterai',
                'stok' => 0,
                'harga_jual' => 400000,
                'status' => 'tidak tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Fan Processor',
                'kategori' => 'Cooling',
                'stok' => 3,
                'harga_jual' => 150000,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}