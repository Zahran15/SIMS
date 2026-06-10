<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailServisJasa extends Model
{
    protected $table = 'detail_servis_jasa';

    protected $primaryKey = 'id_detail_jasa';

    protected $fillable = [
        'id_servis',
        'id_jasa',
        'harga',
        'subtotal',
    ];

    // RELASI KE SERVIS
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'id_servis', 'id_servis');
    }

    // RELASI KE JASA SERVIS
    public function jasa()
    {
        return $this->belongsTo(JasaServis::class, 'id_jasa', 'id_jasa');
    }
}