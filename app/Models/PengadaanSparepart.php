<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengadaanSparepart extends Model
{
    protected $table = 'pengadaan_sparepart';
    protected $primaryKey = 'id_pengadaan';

    protected $fillable = [
        'id_sparepart',
        'tgl_pesan',
        'jumlah',
        'harga_beli',
        'total',
        'status_pengadaan'
    ];

    public $timestamps = true;

    // 🔗 RELASI KE SPAREPART
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'id_sparepart', 'id_sparepart');
    }
}