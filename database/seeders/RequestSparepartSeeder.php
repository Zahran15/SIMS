<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RequestSparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil data penugasan teknisi dan sparepart yang tersedia
        $penugasanList = DB::table('penugasan_teknisi')->pluck('id_penugasan')->toArray();
        $sparepartList = DB::table('sparepart')->get();

        // Fallback keamanan jika data master kosong
        if (empty($penugasanList) || $sparepartList->isEmpty()) {
            return; // Lewati seeder jika tabel prasyarat belum terisi
        }

        // Kumpulan alasan dummy disesuaikan dengan kebutuhan teknis
        $alasanDummy = [
            'Komponen bawaan sudah short parah dan tidak bisa di-inject tegangan lagi.',
            'Stok bawaan unit pelanggan sudah aus/rusak fisik, perlu ganti baru.',
            'Permintaan langsung dari pelanggan untuk upgrade performa komponen.',
            'Komponen lama pecah/patah saat dilakukan proses pembongkaran casing.'
        ];

        // 2. Buat beberapa data dummy request sparepart (misal 8 data)
        for ($i = 0; $i < 8; $i++) {
            // Ambil sparepart secara acak untuk tahu nama/kategorinya
            $sparepart = $faker->randomElement($sparepartList);

            DB::table('request_sparepart')->insert([
                'id_penugasan' => $faker->randomElement($penugasanList),
                'id_sparepart' => $sparepart->id_sparepart,
                'jumlah' => $faker->numberBetween(1, 2), // Biasanya minta 1 atau 2 pcs
                'alasan' => 'Untuk unit pelanggan. ' . $faker->randomElement($alasanDummy),
                'status_request' => $faker->randomElement(['pending_admin', 'dikirim_ke_pelanggan', 'disetujui_pelanggan', 'disetujui', 'ditolak']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}