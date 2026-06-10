<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanTeknisi extends Model
{
    protected $table = 'penugasan_teknisi';
    protected $primaryKey = 'id_penugasan';

    protected $fillable = [
        'id_servis',
        'id_user',
        'prioritas',
        'estimasi_selesai',
        'status_penugasan',
        'catatan_teknisi'
    ];

    // 🔗 Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // 🔗 Relasi ke Servis
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'id_servis');
    }

    // 🔗 Relasi ke Teknisi (User)
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}