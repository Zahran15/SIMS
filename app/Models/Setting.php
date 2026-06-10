<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional, tapi aman jika nama tabelnya singular/plural)
    protected $table = 'settings';

    // Daftarkan kolom yang boleh diisi secara massal
    protected $fillable = [
        'key',
        'value'
    ];

    public static function getByKey($key, $default = null)
{
    $setting = self::where('key', $key)->first();
    return $setting ? $setting->value : $default;
}
};