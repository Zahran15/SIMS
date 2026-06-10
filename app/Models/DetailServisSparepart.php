<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailServisSparepart extends Model
{
    protected $table = 'detail_servis_sparepart';

    protected $primaryKey = 'id_detail_sparepart';

    protected $fillable = [
        'id_servis',
        'id_sparepart',
        'qty',
        'harga',
        'subtotal',
    ];

    // RELASI KE SERVIS
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'id_servis', 'id_servis');
    }

    // RELASI KE SPAREPART
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'id_sparepart', 'id_sparepart');
    }
}