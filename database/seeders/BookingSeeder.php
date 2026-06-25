<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil semua id_pelanggan yang tersedia dari database
        $idPelangganList = DB::table('pelanggan')->pluck('id_pelanggan')->toArray();

        // Antisipasi jika data pelanggan kosong, buat 1 default
        if (empty($idPelangganList)) {
            $idPelangganBaru = DB::table('pelanggan')->insertGetId([
                'kode_pelanggan' => 'PLG001',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Merdeka No. 10, Cilacap',
                'no_hp' => '081234567890',
                'email' => 'budi@gmail.com',
                'password' => bcrypt('pelanggan123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $idPelangganList[] = $idPelangganBaru;
        }

        // 2. Data variasi komponen laptop untuk testing
        $laptopBrands = [
            ['merk' => 'Asus Vivobook 14', 'spek' => 'Core i3 Gen 11, RAM 4GB, SSD 256GB'],
            ['merk' => 'Lenovo ThinkPad X270', 'spek' => 'Core i5 Gen 7, RAM 8GB, SSD 256GB'],
            ['merk' => 'Acer Aspire 3', 'spek' => 'Ryzen 3 3200U, RAM 8GB, HDD 1TB'],
            ['merk' => 'HP Pavilion Gaming', 'spek' => 'Core i7 Gen 10, RAM 16GB, GTX 1650, SSD 512GB'],
            ['merk' => 'MacBook Air M1 2020', 'spek' => 'Apple M1, RAM 8GB, SSD 256GB'],
        ];

        $keluhanList = [
            'Laptop lemot banget dan sering bluescreen',
            'Keyboard beberapa tombol tidak berfungsi',
            'Baterai drop, harus dicolok charger terus',
            'Engsel layar kiri patah dan casing mangap',
            'Tidak bisa masuk windows, cuma mentok di logo',
            'Layar LCD bergaris warna-warni',
            'Kena tumpahan air kopi, sekarang mati total'
        ];

        $kelengkapanList = [
            'Unit laptop + Charger original',
            'Unit laptop saja',
            'Unit laptop + Charger + Tas laptop',
            'Unit laptop + Charger + Dus bawaan'
        ];

        // 3. Generate 10 data booking dummy
        for ($i = 1; $i <= 10; $i++) {
            // Format kode booking otomatis: BKG001, BKG002, dst.
            $kodeBooking = 'BKG' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $laptopRandom = $faker->randomElement($laptopBrands);

            DB::table('booking')->insert([
                'id_pelanggan' => $faker->randomElement($idPelangganList),
                'kode_booking' => $kodeBooking,
                // Tanggal booking acak antara 30 hari ke belakang sampai hari ini
                'tgl_booking' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'merk_tipe' => $laptopRandom['merk'],
                'spesifikasi' => $laptopRandom['spek'],
                'keluhan' => $faker->randomElement($keluhanList),
                'metode_pengembalian' => $faker->randomElement(['diantar', 'ambil sendiri']),
                'kelengkapan' => $faker->randomElement($kelengkapanList),
                'kategori_servis' => $faker->randomElement(['ringan', 'sedang', 'berat']),
                'status_dp' => $faker->randomElement(['belum lunas', 'sudah lunas']),
                'status_booking' => $faker->randomElement(['pending', 'diterima', 'ditolak']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}