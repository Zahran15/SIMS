<?php

use App\Models\Setting;

if (!function_exists('getSetting')) {
    /**
     * Mengambil nilai pengaturan berdasarkan key dari database.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function getSetting($key, $default = null)
    {
        try {
            $setting = Setting::where('key', $key)->first();
            
            // Jika data ditemukan dan nilainya tidak kosong, kembalikan nilainya
            if ($setting && !is_null($setting->value)) {
                return $setting->value;
            }
        } catch (\Exception $e) {
            // Mengantisipasi jika tabel 'settings' belum dimigrasi saat aplikasi diakses
            return $default;
        }

        return $default;
    }
}

if (!function_exists('getSettingAsset')) {
    /**
     * Mengambil nilai pengaturan berupa file/gambar dan mengembalikannya sebagai URL Asset.
     * Jika file tidak ada, bisa mengembalikan gambar default.
     *
     * @param string $key
     * @param string $defaultPath
     * @return string
     */
    function getSettingAsset($key, $defaultPath = 'assets/img/no-image.png')
    {
        $path = getSetting($key);

        // Jika path ditemukan di database dan filenya benar-benar ada di folder public
        if ($path && file_exists(public_path($path))) {
            return asset($path);
        }

        // Kembalikan gambar default jika tidak diatur atau file hilang
        return asset($defaultPath);
    }
}