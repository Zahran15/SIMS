<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailServisJasaSeeder extends Seeder
{
    public function run(): void
    {

        $servisList = DB::table('servis')->get();

        $jasaList = DB::table('jasa_servis')->get();


        if(
            $servisList->isEmpty() ||
            $jasaList->isEmpty()
        ){
            return;
        }
        foreach($servisList as $servis)
        {
            // setiap servis 1-2 jasa
            $jumlahJasa = rand(1,2);
            $jasaDipilih = $jasaList->random($jumlahJasa);
            foreach($jasaDipilih as $jasa)
            {
                DB::table('detail_servis_jasa')->insert([
                    'id_servis'=>$servis->id_servis,
                    'id_jasa'=>$jasa->id_jasa,
                    'harga'=>$jasa->harga,
                    'subtotal'=>$jasa->harga,
                    'created_at'=>now(),
                    'updated_at'=>now()

                ]);

            }

        }

    }
}