<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestSparepart extends Model
{
    protected $table = 'request_sparepart';

    protected $primaryKey = 'id_request';

    protected $fillable = [
        'id_penugasan',
        'id_sparepart',
        'jumlah',
        'alasan',
        'status_request'
    ];

    // 🔗 Relasi ke Penugasan Teknisi
    public function penugasan()
    {
        return $this->belongsTo(
            PenugasanTeknisi::class,
            'id_penugasan'
        );
    }

    // 🔗 Relasi ke Sparepart
    public function sparepart()
    {
        return $this->belongsTo(
            Sparepart::class,
            'id_sparepart'
        );
    }
}