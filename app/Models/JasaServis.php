<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JasaServis extends Model
{
    protected $table = 'jasa_servis';
    protected $primaryKey = 'id_jasa';

    protected $fillable = [
        'nama_jasa',
        'harga'
    ];

    public function detailServis()
    {
        return $this->hasMany(DetailServisJasa::class, 'id_jasa', 'id_jasa');
    }
}