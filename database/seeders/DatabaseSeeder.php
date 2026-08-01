<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PelangganSeeder::class,
            JasaServisSeeder::class,
            SparepartSeeder::class,
            ToolsSeeder::class,
            BookingSeeder::class,
            ServisSeeder::class,
            DetailServisJasaSeeder::class,
            DetailServisSparepartSeeder::class,
            PenugasanTeknisiSeeder::class,
            RequestSparepartSeeder::class,
            PengadaanSparepartSeeder::class,
            HistoriAktivitasSeeder::class,
            PembayaranSeeder::class, 
        ]);
    }
}