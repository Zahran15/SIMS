<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $table = 'sparepart';
    protected $primaryKey = 'id_sparepart';
    protected $fillable = [
        'nama_sparepart',
        'kategori',
        'stok',
        'harga_jual',
        'status'
    ];

    public function detailServis()
    {
        return $this->hasMany(DetailServisSparepart::class, 'id_sparepart', 'id_sparepart');
    }
    public $timestamps = true;
}