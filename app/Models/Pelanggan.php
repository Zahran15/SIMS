<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable; // <-- 1. Import Notifiable

class Pelanggan extends Authenticatable
{
    use Notifiable; // <-- 2. Pakai Trait Notifiable

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    
    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'alamat',
        'no_hp',
        'email',
        'password',
        'status'
    ];
    
    protected $hidden = [
        'password'
    ];

    public function setPasswordAttribute($value)
    {
        // Hindari double hash jika password sudah di-hash sebelumnya
        if (!empty($value) && Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // Override relasi notification karena primaryKey kustom (id_pelanggan)
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable', null, 'notifiable_id', 'id_pelanggan')
                    ->orderBy('created_at', 'desc');
    }
}