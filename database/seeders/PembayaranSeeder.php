<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN DP
        |--------------------------------------------------------------------------
        */


        $bookingList = DB::table('booking')
            ->where('status_booking','diterima')
            ->get();
        foreach($bookingList as $booking)
        {
            $status =
                $booking->status_dp == 'sudah lunas'
                ? 'sukses'
                : 'pending';
            $metode =
                $booking->id_booking % 2 == 0
                ? 'transfer'
                : 'cash';
            DB::table('pembayaran')->insert([
                'id_booking'=>$booking->id_booking,
                'id_servis'=>null,
                'jenis_pembayaran'=>'dp',
                'metode_pembayaran'=>$metode,
                'nominal'=>50000,
                'status_pembayaran'=>$status,
                'snap_token'=>
                    $metode=='transfer'
                    ? 'snap-'.Str::random(20)
                    : null,
                'midtrans_order_id'=>
                    $metode=='transfer'
                    ? 'DP-'.Str::upper(Str::random(10))
                    : null,
                'midtrans_transaction_id'=>
                    $status=='sukses'
                    ? Str::uuid()
                    : null,
                'tanggal_bayar'=>
                    $status=='sukses'
                    ? Carbon::parse($booking->tgl_booking)
                        ->addHours(2)
                    : null,
                'created_at'=>now(),
                'updated_at'=>now()
            ]);

        }





        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN PELUNASAN
        |--------------------------------------------------------------------------
        */
        $servisList = DB::table('servis')
            ->whereIn(
                'status_servis',
                [
                    'selesai',
                    'bisa diambil',
                    'sudah diambil'
                ]
            )
            ->get();
        foreach($servisList as $servis)
        {
            if(
                $servis->status_pelunasan
                !=
                'sudah lunas'
            )
            {
                continue;
            }
            $booking =
                DB::table('booking')
                ->where(
                    'id_booking',
                    $servis->id_booking
                )->first();
            $nominal = $servis->total_biaya;
            // kurangi DP jika sudah bayar
            if($booking->status_dp == 'sudah lunas')
            {
                $nominal -= 50000;
            }
            DB::table('pembayaran')->insert([
                'id_booking'=>$booking->id_booking,
                'id_servis'=>$servis->id_servis,
                'jenis_pembayaran'=>'pelunasan',
                'metode_pembayaran'=>
                    $servis->id_servis % 2 == 0
                    ? 'transfer'
                    : 'cash',
                'nominal'=>$nominal,
                'status_pembayaran'=>'sukses',
                'snap_token'=>null,
                'midtrans_order_id'=>null,
                'midtrans_transaction_id'=>null,
                'tanggal_bayar'=>
                    Carbon::parse(
                        $servis->perkiraan_selesai
                    )->addHours(3),
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }
    }
}