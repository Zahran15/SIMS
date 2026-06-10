<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    protected $table = 'tools';
    protected $primaryKey = 'id_tools';

    protected $fillable = [
        'id_user',
        'nama_tools',
        'jumlah',
        'status'
    ];

    public $timestamps = true;

     // RELASI KE USER
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }
}