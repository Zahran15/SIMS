<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    protected $table = 'servis';
    protected $primaryKey = 'id_servis';

    protected $fillable = [
        'id_booking',
        'kode_servis',
        'tgl_masuk',
        'perkiraan_selesai',
        'status_servis',
        'status_pelunasan',
        'total_biaya'
    ];

    // 🔗 Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    // 🔗 Relasi ke Penugasan Teknisi
    public function penugasan()
    {
        return $this->hasOne(PenugasanTeknisi::class, 'id_servis');
    }

    // DETAIL JASA
    public function detailJasa()
    {
        return $this->hasMany(DetailServisJasa::class, 'id_servis', 'id_servis');
    }

    // DETAIL SPAREPART
    public function detailSparepart()
    {
        return $this->hasMany(DetailServisSparepart::class, 'id_servis', 'id_servis');
    }

    // HISTORI AKTIVITAS SERVIS
    public function histori()
    {
        return $this->hasMany(HistoriAktivitas::class, 'id_servis', 'id_servis');
    }

    // PEMBAYARAN
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class,'id_servis');
    }
}