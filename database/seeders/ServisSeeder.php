<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ServisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil semua data booking yang tersedia
        $bookingList = DB::table('booking')->get();

        // Antisipasi jika data booking kosong, buat 1 dummy booking dulu
        if ($bookingList->isEmpty()) {
            $idPelanggan = DB::table('pelanggan')->value('id_pelanggan') ?? 1;
            
            $idBookingBaru = DB::table('booking')->insertGetId([
                'id_pelanggan' => $idPelanggan,
                'kode_booking' => 'BKG001',
                'tgl_booking' => now()->format('Y-m-d'),
                'merk_tipe' => 'Asus Vivobook 14',
                'spesifikasi' => 'Core i3 Gen 11, RAM 4GB, SSD 256GB',
                'keluhan' => 'Laptop lemot banget',
                'metode_pengambilan' => 'ambil sendiri',
                'kelengkapan' => 'Unit + Charger',
                'kategori_servis' => 'ringan',
                'status_dp' => 'belum lunas',
                'status_booking' => 'diterima',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bookingList = DB::table('booking')->where('id_booking', $idBookingBaru)->get();
        }

        // 2. Loop data booking untuk dijadikan data servis
        $counter = 1;
        foreach ($bookingList as $booking) {
            // Kita buat format kode servis otomatis: SRV001, SRV002, dst.
            $kodeServis = 'SRV' . str_pad($counter, 3, '0', STR_PAD_LEFT);

            // Set tgl_masuk sama atau beberapa hari setelah tgl_booking
            $tglMasuk = Carbon::parse($booking->tgl_booking)->addDays(rand(0, 2));
            
            // Set perkiraan selesai 3 sampai 5 hari dari tanggal masuk
            $perkiraanSelesai = $tglMasuk->copy()->addDays(rand(3, 5));

            // Logic status agar data testing terlihat variatif namun masuk akal
            $statusServis = $faker->randomElement(['menunggu', 'proses', 'selesai', 'bisa diambil', 'sudah diambil', 'dibatalkan']);
            
            // Jika status sudah diambil, buat status pelunasan otomatis 'sudah lunas'
            if ($statusServis === 'sudah diambil') {
                $statusPelunasan = 'sudah lunas';
                $totalBiaya = $faker->numberBetween(15, 100) * 10000; // Kisaran 150rb - 1jt
            } elseif ($statusServis === 'dibatalkan') {
                $statusPelunasan = 'belum lunas';
                $totalBiaya = 0;
            } else {
                $statusPelunasan = $faker->randomElement(['belum lunas', 'sudah lunas']);
                $totalBiaya = $statusPelunasan === 'sudah lunas' ? $faker->numberBetween(10, 50) * 10000 : $faker->numberBetween(0, 40) * 10000;
            }

            DB::table('servis')->insert([
                'id_booking' => $booking->id_booking,
                'kode_servis' => $kodeServis,
                'tgl_masuk' => $tglMasuk->format('Y-m-d'),
                'perkiraan_selesai' => $perkiraanSelesai->format('Y-m-d'),
                'status_servis' => $statusServis,
                'status_pelunasan' => $statusPelunasan,
                'total_biaya' => $totalBiaya,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter++;
        }
    }
}