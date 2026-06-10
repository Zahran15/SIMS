<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriAktivitas extends Model
{
    protected $table = 'histori_aktivitas';

    protected $primaryKey = 'id_histori';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_servis',
        'aktivitas',
        'keterangan',
        'tanggal'
    ];

    // RELASI USER
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // RELASI SERVIS
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'id_servis', 'id_servis');
    }
}