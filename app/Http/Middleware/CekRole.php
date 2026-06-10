<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Ambil user yang login dari guard 'web' (users)
        $user = Auth::guard('web')->user();

        // 2. Cek apakah user ada dan rolenya masuk dalam list yang diizinkan
        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}