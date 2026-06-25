<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Sparepart Riil/Umum untuk Komputer & Laptop
        $sparepartUtama = [
            [
                'nama_sparepart' => 'SSD NVMe Kingmax 512GB',
                'kategori' => 'Penyimpanan',
                'stok' => 10,
                'harga_jual' => 550000.00,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'RAM DDR4 V-Gen SODIMM 8GB',
                'kategori' => 'Memori',
                'stok' => 15,
                'harga_jual' => 320000.00,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Keyboard Laptop Universal ASUS X441',
                'kategori' => 'Keyboard',
                'stok' => 5,
                'harga_jual' => 125000.00,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Baterai Laptop Lenovo ThinkPad L470',
                'kategori' => 'Baterai',
                'stok' => 0, // Contoh stok habis
                'harga_jual' => 450000.00,
                'status' => 'tidak tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Thermal Paste Noctua NT-H1',
                'kategori' => 'Aksesoris',
                'stok' => 8,
                'harga_jual' => 110000.00,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('sparepart')->insert($sparepartUtama);

        // 2. Tambahan Data Dummy Acak menggunakan Faker
        $faker = Faker::create('id_ID');
        $kategoriList = ['Penyimpanan', 'Memori', 'Keyboard', 'Baterai', 'Layar LCD', 'Aksesoris', 'Mainboard'];

        for ($i = 0; $i < 10; $i++) {
            $stok = $faker->numberBetween(0, 20);
            
            // Logic agar status sinkron dengan jumlah stok
            $status = ($stok > 0) ? 'tersedia' : 'tidak tersedia';

            DB::table('sparepart')->insert([
                'nama_sparepart' => $faker->randomElement(['LCD Screen', 'Charger Adaptor', 'Fan Cooling', 'SSD Sata', 'RAM DDR5']) . ' ' . $faker->word() . ' ' . $faker->bothify('##??'),
                'kategori' => $faker->randomElement($kategoriList),
                'stok' => $stok,
                'harga_jual' => $faker->numberBetween(15, 150) * 10000, // Range harga 150rb s.d 1.5jt kelipatan 10rb
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}