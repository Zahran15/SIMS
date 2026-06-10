<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'nama',
        'role',
        'email',
        'no_hp',
        'password',
        'status'
    ];

    protected $hidden = [
        'password'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function tools()
    {
        return $this->hasMany(
            Tools::class,
            'id_user',
            'id_user'
        );
    }

    public function histori()
    {
        return $this->hasMany(HistoriAktivitas::class, 'id_user', 'id_user');
    }

    public function penugasan()
    {
        return $this->hasMany(
            PenugasanTeknisi::class,
            'id_user',
            'id_user'
        );
    }

        public function getAuthIdentifierName()
    {
        return 'id_user';
    }
}