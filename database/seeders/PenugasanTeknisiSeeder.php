<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenugasanTeknisiSeeder extends Seeder
{
    public function run(): void
    {


        $teknisi = DB::table('users')
            ->where('role','teknisi')
            ->where('status','aktif')
            ->pluck('id_user')
            ->toArray();



        if(empty($teknisi))
        {
            return;
        }

        $servisList = DB::table('servis')
            ->join(
                'booking',
                'booking.id_booking',
                '=',
                'servis.id_booking'
            )
            ->select(
                'servis.*',
                'booking.kategori_servis',
                'booking.keluhan'
            )
            ->get();

        foreach($servisList as $index=>$servis)
        {
            switch($servis->status_servis)
            {
                case 'menunggu':
                    $status='belum dikerjakan';
                    $catatan=
                    'Unit masuk dan menunggu pemeriksaan teknisi.';
                break;
                case 'proses':

                    if(
                        str_contains(
                            strtolower($servis->keluhan),
                            'keyboard'
                        )
                        ||
                        str_contains(
                            strtolower($servis->keluhan),
                            'lcd'
                        )
                    )
                    {
                        $status='menunggu sparepart';
                        $catatan=
                        'Menunggu ketersediaan sparepart pengganti.';
                    }
                    else
                    {
                        $status='sedang dikerjakan';
                        $catatan=
                        'Teknisi sedang melakukan proses perbaikan unit.';
                    }
                break;

                case 'selesai':
                case 'bisa diambil':
                case 'sudah diambil':
                    $status='selesai';
                    $catatan=
                    'Servis selesai dan unit sudah melalui pengujian.';
                break;

                default:
                    $status='gagal';
                    $catatan=
                    'Perbaikan tidak dapat dilanjutkan.';
                break;
            }

            DB::table('penugasan_teknisi')->insert([
                'id_servis'=>$servis->id_servis,
                'id_user'=>
                    $teknisi[
                        $index % count($teknisi)
                    ],
                'prioritas'=>$servis->kategori_servis,
                'estimasi_selesai'=>$servis->perkiraan_selesai,
                'status_penugasan'=>$status,
                'catatan_teknisi'=>$catatan,
                'created_at'=>$servis->created_at,
                'updated_at'=>now()


            ]);

        }

    }
}