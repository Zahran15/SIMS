<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'id_pelanggan',
        'kode_booking',
        'tgl_booking',
        'merk_tipe',
        'spesifikasi',
        'keluhan',
        'metode_pengambilan',
        'kelengkapan',
        'kategori_servis',
        'status_deposit',
        'status_booking'
    ];

    public $timestamps = true;

    // RELASI KE PELANGGAN
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
    // RELASI KE SERVIS
    public function servis()
    {
        return $this->hasOne(Servis::class, 'id_booking');
    }

    // RELASI KE PEMBAYARAN
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class,'id_booking');
    }
}