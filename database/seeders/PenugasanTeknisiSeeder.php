<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PenugasanTeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil semua id_user yang memiliki role 'teknisi'
        $idTeknisiList = DB::table('users')->where('role', 'teknisi')->pluck('id_user')->toArray();

        // Antisipasi jika belum ada user role teknisi (fallback keamanan)
        if (empty($idTeknisiList)) {
            $idTeknisiBaru = DB::table('users')->insertGetId([
                'nama' => 'Teknisi Utama',
                'role' => 'teknisi',
                'email' => 'teknisi@gmail.com',
                'no_hp' => '085712345678',
                'password' => bcrypt('password123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $idTeknisiList[] = $idTeknisiBaru;
        }

        // 2. Ambil semua data servis yang ada untuk dipasangkan ke penugasan
        $servisList = DB::table('servis')->get();

        // Kumpulan catatan dummy khas teknisi laptop biar kelihatan riil
        $catatanDummy = [
            'Proses pengecekan jalur power pada motherboard, dicurigai IC short.',
            'Keyboard short sebagian, sedang dilakukan pembongkaran untuk penggantian unit baru.',
            'Selesai melakukan re-pasta thermal dan pembersihan fan, suhu kembali normal.',
            'Menunggu konfirmasi owner/pelanggan terkait biaya penggantian LCD panel.',
            'IC Bios corup, sedang proses flashing ulang menggunakan alat programmer.',
            'Kondisi mati total parah setelah kena air, korosi meluas di area chipset. Gagal diperbaiki.',
            'Instalasi ulang Windows 11 Pro beserta driver dasar selesai dilakukan.'
        ];

        // 3. Loop data servis dan buat penugasannya
        foreach ($servisList as $servis) {
            // Tentukan status penugasan secara acak
            $statusPenugasan = $faker->randomElement([
                'belum dikerjakan', 
                'sedang dikerjakan', 
                'menunggu sparepart', 
                'selesai', 
                'gagal'
            ]);

            // Sinkronisasi catatan berdasarkan status pengerjaan
            if ($statusPenugasan === 'gagal') {
                $catatan = 'Kerusakan terlalu parah pada core komponen, ' . $faker->randomElement($catatanDummy);
            } elseif ($statusPenugasan === 'selesai') {
                $catatan = 'Pengerjaan rampung tanpa kendala. ' . $faker->randomElement($catatanDummy);
            } else {
                $catatan = $faker->randomElement($catatanDummy);
            }

            DB::table('penugasan_teknisi')->insert([
                'id_servis' => $servis->id_servis,
                // Mengambil teknisi secara acak dari daftar teknisi yang ada
                'id_user' => $faker->randomElement($idTeknisiList),
                'prioritas' => $faker->randomElement(['ringan', 'sedang', 'berat']),
                // Ambil estimasi selesai disamakan dengan perkiraan_selesai dari tabel servis
                'estimasi_selesai' => $servis->perkiraan_selesai,
                'status_penugasan' => $statusPenugasan,
                'catatan_teknisi' => $catatan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}