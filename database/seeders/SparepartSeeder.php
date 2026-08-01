<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('sparepart')->insert([
            [
                'nama_sparepart' => 'SSD SATA 512GB',
                'kategori' => 'Penyimpanan',
                'stok' => 10,
                'harga_jual' => 550000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'RAM DDR4 SODIMM 8GB',
                'kategori' => 'Memori',
                'stok' => 12,
                'harga_jual' => 320000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Keyboard Laptop ASUS X441',
                'kategori' => 'Keyboard',
                'stok' => 5,
                'harga_jual' => 150000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'LCD Laptop 14 Inch',
                'kategori' => 'Layar',
                'stok' => 4,
                'harga_jual' => 850000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Baterai Laptop ASUS A416',
                'kategori' => 'Baterai',
                'stok' => 2,
                'harga_jual' => 450000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Thermal Paste',
                'kategori' => 'Aksesoris',
                'stok' => 15,
                'harga_jual' => 50000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Kipas Pendingin Laptop',
                'kategori' => 'Pendingin',
                'stok' => 3,
                'harga_jual' => 180000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Port Charger DC Jack',
                'kategori' => 'Power',
                'stok' => 6,
                'harga_jual' => 75000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Port USB Laptop',
                'kategori' => 'I/O',
                'stok' => 5,
                'harga_jual' => 60000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Engsel Laptop',
                'kategori' => 'Casing',
                'stok' => 2,
                'harga_jual' => 120000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Adaptor Charger 65W',
                'kategori' => 'Power',
                'stok' => 6,
                'harga_jual' => 250000,
                'status' => 'tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Sengaja stok habis agar nanti muncul di Pengadaan Sparepart
            [
                'nama_sparepart' => 'Motherboard Laptop',
                'kategori' => 'Mainboard',
                'stok' => 0,
                'harga_jual' => 1800000,
                'status' => 'tidak tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Touchpad Laptop',
                'kategori' => 'Input',
                'stok' => 0,
                'harga_jual' => 250000,
                'status' => 'tidak tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_sparepart' => 'Webcam Laptop',
                'kategori' => 'Kamera',
                'stok' => 0,
                'harga_jual' => 150000,
                'status' => 'tidak tersedia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}