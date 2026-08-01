<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PengadaanSparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil sparepart yang stoknya sedikit atau habis
        $sparepartList = DB::table('sparepart')
            ->where('stok', '<=', 10)
            ->get();

        if ($sparepartList->isEmpty()) {
            return;
        }

        foreach ($sparepartList as $sparepart) {

            // Tidak semua sparepart dilakukan pengadaan
            if (!$faker->boolean(70)) {
                continue;
            }

            $tglPesan = Carbon::instance(
                $faker->dateTimeBetween('-30 days', '-3 days')
            );

            $status = $faker->randomElement([
                'diterima',
                'diterima',
                'diterima',
                'dipesan',
                'diajukan',
                'dibatalkan',
            ]);

            $jumlah = $faker->numberBetween(5, 20);

            // Harga beli sekitar 70-85% dari harga jual
            $hargaBeli = round(
                $sparepart->harga_jual * $faker->randomFloat(2, 0.70, 0.85),
                -3
            );

            DB::table('pengadaan_sparepart')->insert([
                'id_sparepart' => $sparepart->id_sparepart,
                'tgl_pesan' => $tglPesan->format('Y-m-d'),
                'jumlah' => $jumlah,
                'harga_beli' => $hargaBeli,
                'total' => $jumlah * $hargaBeli,
                'status_pengadaan' => $status,
                'created_at' => $tglPesan,
                'updated_at' => $tglPesan,
            ]);
        }
    }
}