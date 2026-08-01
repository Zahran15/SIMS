<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServisSeeder extends Seeder
{
    public function run(): void
    {

        $bookingList = DB::table('booking')
            ->where('status_booking','diterima')
            ->orderBy('id_booking')
            ->get();


        $statusList = [
            'menunggu',
            'menunggu',
            'proses',
            'proses',
            'proses',
            'proses',
            'selesai',
            'selesai',
            'selesai',
            'bisa diambil',
            'bisa diambil',
            'sudah diambil',
            'sudah diambil',
            'selesai',
            'proses'
        ];


        $no = 1;
        foreach($bookingList as $booking)
        {
            $tglMasuk = Carbon::parse(
                $booking->tgl_booking
            )->addDay();
            switch($booking->kategori_servis)
            {
                case 'ringan':
                    $durasi = 2;
                    $biaya = 150000;
                break;


                case 'sedang':
                    $durasi = 4;
                    $biaya = 300000;
                break;


                default:
                    $durasi = 7;
                    $biaya = 750000;
                break;

            }

            $perkiraanSelesai =
                $tglMasuk->copy()
                ->addDays($durasi);
            $statusServis =
                $statusList[$no-1];
            if(
                $statusServis=='menunggu' ||
                $statusServis=='proses'
            )
            {
                $pelunasan='belum lunas';
            }
            elseif(
                $statusServis=='selesai'
            )
            {

                $pelunasan =
                    $no % 2 == 0
                    ? 'sudah lunas'
                    : 'belum lunas';

            }
            else
            {
                $pelunasan='sudah lunas';
            }

            DB::table('servis')->insert([
                'id_booking'=>$booking->id_booking,
                'kode_servis'=>
                    'SRV-'.
                    Carbon::parse($booking->tgl_booking)
                    ->format('Ymd')
                    .'-'.
                    str_pad($no,3,'0',STR_PAD_LEFT),
                'tgl_masuk'=>$tglMasuk,
                'perkiraan_selesai'=>$perkiraanSelesai,
                'status_servis'=>$statusServis,
                'status_pelunasan'=>$pelunasan,
                'total_biaya'=>$biaya,
                'created_at'=>$tglMasuk,
                'updated_at'=>now()

            ]);
            $no++;
        }
    }
}