<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $casts = ['tanggal_bayar' => 'datetime',];

    protected $fillable = [
        'id_booking',
        'id_servis',
        'jenis_pembayaran',
        'metode_pembayaran',
        'nominal',
        'status_pembayaran',
        'snap_token',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'tanggal_bayar'
    ];

    // RELASI KE BOOKING
    public function booking()
    {
        return $this->belongsTo(Booking::class,'id_booking');
    }

    // RELASI KE SERVIS
    public function servis()
    {
        return $this->belongsTo(Servis::class,'id_servis');
    }
}