<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $pelanggan = DB::table('pelanggan')
            ->pluck('id_pelanggan')
            ->toArray();


        $kasus = [

            [
                'merk'=>'Asus Vivobook 14',
                'spesifikasi'=>'Core i3 Gen 11 RAM 8GB SSD 512GB',
                'keluhan'=>'Laptop lambat saat digunakan',
                'kategori'=>'ringan'
            ],

            [
                'merk'=>'Lenovo ThinkPad X270',
                'spesifikasi'=>'Core i5 Gen 7 RAM 8GB SSD 256GB',
                'keluhan'=>'Laptop stuck logo Windows',
                'kategori'=>'sedang'
            ],

            [
                'merk'=>'Acer Aspire 3',
                'spesifikasi'=>'Ryzen 3 RAM 8GB HDD 1TB',
                'keluhan'=>'Keyboard beberapa tombol tidak berfungsi',
                'kategori'=>'sedang'
            ],

            [
                'merk'=>'HP Pavilion Gaming',
                'spesifikasi'=>'Core i7 RAM 16GB GTX1650',
                'keluhan'=>'Laptop cepat panas',
                'kategori'=>'ringan'
            ],

            [
                'merk'=>'MacBook Air M1',
                'spesifikasi'=>'Apple M1 RAM 8GB SSD 256GB',
                'keluhan'=>'Baterai cepat habis',
                'kategori'=>'sedang'
            ],

            [
                'merk'=>'Asus X441',
                'spesifikasi'=>'Core i3 RAM 4GB HDD 500GB',
                'keluhan'=>'LCD bergaris',
                'kategori'=>'berat'
            ],

        ];


        for($i=1;$i<=20;$i++)
        {

            if($i<=15)
            {
                $statusBooking='diterima';

                $statusDP = $i <= 10
                    ? 'sudah lunas'
                    : 'belum lunas';

            }
            elseif($i<=18)
            {
                $statusBooking='pending';
                $statusDP='belum lunas';
            }
            else
            {
                $statusBooking='ditolak';
                $statusDP='belum lunas';
            }


            $data = $faker->randomElement($kasus);


            $tanggal = now()->subDays(rand(1,30));


            DB::table('booking')->insert([

                'id_pelanggan'=>
                    $faker->randomElement($pelanggan),

                'kode_booking'=>
                    'BK-'.$tanggal->format('Ymd').'-'.
                    str_pad($i,3,'0',STR_PAD_LEFT),

                'tgl_booking'=>$tanggal,

                'merk_tipe'=>$data['merk'],

                'spesifikasi'=>$data['spesifikasi'],

                'keluhan'=>$data['keluhan'],

                'metode_pengembalian'=>
                    $faker->randomElement([
                        'ambil sendiri',
                        'diantar'
                    ]),

                'kelengkapan'=>
                    $faker->randomElement([
                        'Unit laptop',
                        'Unit laptop + Charger',
                        'Unit laptop + Charger + Tas'
                    ]),
                'kategori_servis'=>$data['kategori'],
                'status_dp'=>$statusDP,
                'status_booking'=>$statusBooking,
                'created_at'=>$tanggal,
                'updated_at'=>$tanggal

            ]);
        }
    }
}