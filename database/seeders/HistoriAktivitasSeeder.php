<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HistoriAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil data master users dan servis
        $userList = DB::table('users')->get();
        $servisList = DB::table('servis')->get();

        // Fallback keamanan jika data master prasyarat kosong
        if ($userList->isEmpty() || $servisList->isEmpty()) {
            return;
        }

        // Kumpulan variasi aktivitas audit log biar terlihat riil
        $logTemplates = [
            [
                'aktivitas' => 'Penerimaan Unit',
                'keterangan' => 'Admin mengubah status booking menjadi diterima dan membuatkan lembar servis masuk.',
            ],
            [
                'aktivitas' => 'Penugasan Teknisi',
                'keterangan' => 'Admin menunjuk teknisi untuk mulai melakukan diagnosa kerusakan unit.',
            ],
            [
                'aktivitas' => 'Mulai Pengerjaan',
                'keterangan' => 'Teknisi mengubah status penugasan menjadi sedang dikerjakan.',
            ],
            [
                'aktivitas' => 'Request Sparepart',
                'keterangan' => 'Teknisi mengajukan permintaan komponen tambahan ke bagian gudang.',
            ],
            [
                'aktivitas' => 'Konfirmasi Selesai',
                'keterangan' => 'Teknisi berhasil menyelesaikan perbaikan unit dan melakukan quality control.',
            ],
            [
                'aktivitas' => 'Pelunasan Pembayaran',
                'keterangan' => 'Pelanggan melakukan pembayaran kasir dan status diubah menjadi lunas.',
            ],
            [
                'aktivitas' => 'Penyerahan Unit',
                'keterangan' => 'Unit laptop telah diambil kembali oleh pelanggan dalam kondisi baik.',
            ],
        ];

        // 2. Generate data histori dummy (misal kita buat 15 rekam aktivitas)
        for ($i = 0; $i < 25; $i++) {
            $user = $faker->randomElement($userList);
            $servis = $faker->randomElement($servisList);
            $template = $faker->randomElement($logTemplates);

            DB::table('histori_aktivitas')->insert([
                'id_user' => $user->id_user,
                'id_servis' => $servis->id_servis,
                'aktivitas' => $template['aktivitas'],
                // Gabungkan template keterangan dengan nama user biar makin detail log-nya
                'keterangan' => $template['keterangan'] . ' (Diproses oleh: ' . $user->nama . ' - ' . ucfirst($user->role) . ')',
                // Tanggal log diacak sekitar 1 sampai 20 hari ke belakang
                'tanggal' => $faker->dateTimeBetween('-20 days', 'now'),
            ]);
        }
    }
}