<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailServisSparepartSeeder extends Seeder
{
    public function run(): void
    {


        $servisList = DB::table('servis')
            ->whereIn(
                'status_servis',
                [
                    'proses',
                    'selesai',
                    'bisa diambil',
                    'sudah diambil'
                ]
            )
            ->get();
        $sparepartList = DB::table('sparepart')
            ->where('status','tersedia')
            ->get();
        if(
            $servisList->isEmpty() ||
            $sparepartList->isEmpty()
        ){
            return;
        }

        foreach($servisList as $servis)
        {
            // tidak semua servis menggunakan sparepart
            if(rand(1,100) > 60)
            {
                continue;
            }
            $jumlah = rand(1,2);
            $pilihan = $sparepartList->random($jumlah);
            foreach($pilihan as $sparepart)
            {
                $qty = rand(1,2);
                DB::table('detail_servis_sparepart')
                ->insert([
                    'id_servis'=>$servis->id_servis,
                    'id_sparepart'=>$sparepart->id_sparepart,
                    'qty'=>$qty,
                    'harga'=>$sparepart->harga_jual,
                    'subtotal'=>$qty * $sparepart->harga_jual,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
            }
        }
    }
}