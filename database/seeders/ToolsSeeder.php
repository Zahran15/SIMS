<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolsSeeder extends Seeder
{
    public function run(): void
    {

        $teknisi = DB::table('users')
            ->where('role','teknisi')
            ->pluck('id_user')
            ->toArray();


        if(empty($teknisi)){
            return;
        }


        $tools = [

            [
                'nama_tools'=>'Obeng Set Presisi Laptop',
                'jumlah'=>3,
                'status'=>'tersedia'
            ],

            [
                'nama_tools'=>'Spudger Pembuka Casing Laptop',
                'jumlah'=>5,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'Pinset Elektronik',
                'jumlah'=>4,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'Multimeter Digital',
                'jumlah'=>2,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'DC Power Supply',
                'jumlah'=>1,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'USB Bootable Installer',
                'jumlah'=>3,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'Kuas Cleaning Elektronik',
                'jumlah'=>6,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'Air Blower Cleaning Laptop',
                'jumlah'=>2,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'Thermal Paste Applicator',
                'jumlah'=>2,
                'status'=>'tersedia'
            ],


            [
                'nama_tools'=>'Tester Charger Laptop',
                'jumlah'=>1,
                'status'=>'tidak tersedia'
            ],
        ];

        foreach($tools as $index=>$tool)
        {
            DB::table('tools')->insert([
                'id_user'=>
                    $teknisi[
                        $index % count($teknisi)
                    ],
                'nama_tools'=>$tool['nama_tools'],
                'jumlah'=>$tool['jumlah'],
                'status'=>$tool['status'],
                'created_at'=>now(),
                'updated_at'=>now()

            ]);

        }

    }
}