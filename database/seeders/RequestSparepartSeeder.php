<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestSparepartSeeder extends Seeder
{
    public function run(): void
    {


        $penugasanList = DB::table('penugasan_teknisi')
            ->where(
                'status_penugasan',
                'menunggu sparepart'
            )
            ->get();
        $sparepartList = DB::table('sparepart')
            ->where('status','tersedia')
            ->get();
        if(
            $penugasanList->isEmpty() ||
            $sparepartList->isEmpty()
        )
        {
            return;
        }
        $alasan = [
            'Keyboard rusak dan perlu diganti.',
            'LCD mengalami kerusakan.',
            'Baterai sudah tidak mampu menyimpan daya.',
            'Port USB tidak berfungsi.',
            'Kipas pendingin mengalami kerusakan.'
        ];
        foreach($penugasanList as $penugasan)
        {
            // sekitar setengah membutuhkan sparepart
            if(rand(1,100)>60)
            {
                continue;
            }
            $sparepart =$sparepartList->random();
            DB::table('request_sparepart')
            ->insert([
                'id_penugasan'=>$penugasan->id_penugasan,
                'id_sparepart'=>$sparepart->id_sparepart,
                'jumlah'=>rand(1,2),
                'alasan'=>$alasan[array_rand($alasan)],
                'status_request'=>'disetujui',
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }

    }
}