<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showWelcome() { return view('welcome'); }
    public function showLogin() { return view('auth.login'); }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. COBA LOGIN SEBAGAI USER (Admin, Owner, Teknisi) - Guard default 'web'
        $user = User::where('email', $request->email)->first();
        if ($user && $user->status == 'aktif') {
            if (Auth::guard('web')->attempt($credentials)) {
                $request->session()->regenerate(); // Mengamankan session ID
                // Redirect sesuai role
                if ($user->role == 'admin') return redirect()->intended('/admin/dashboard');
                if ($user->role == 'owner') return redirect()->intended('/owner/dashboard');
                if ($user->role == 'teknisi') return redirect()->intended('/teknisi/dashboard');
            }
        }

        // 2. COBA LOGIN SEBAGAI PELANGGAN - Guard 'pelanggan'
        $pelanggan = Pelanggan::where('email', $request->email)->first();
        if ($pelanggan) {
            if ($pelanggan->status != 'aktif') {
                return back()->with('error', 'Akun Anda nonaktif.');
            }
            if (Auth::guard('pelanggan')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('/pelanggan/dashboard');
            }
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function showRegister()
    {
        $tanggal = Carbon::now()->format('Ymd');
        $count = Pelanggan::whereDate('created_at', Carbon::today())->count();
        $kode = 'PLG' . $tanggal . '' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return view('auth.register', compact('kode'));
    }

    public function registerPelanggan(Request $request)
    {
        $tanggal = Carbon::now()->format('Ymd');
        $last = Pelanggan::whereDate('created_at', Carbon::today())->count();
        $kode = 'PLG' . $tanggal . '' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);

        Pelanggan::create([
            'kode_pelanggan' => $kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' =>$request->password,
            'status' => 'aktif'
        ]);
        
        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login');
    }

    public function logout(Request $request)
    {
        // Logout dari guard web maupun pelanggan
        Auth::guard('web')->logout();
        Auth::guard('pelanggan')->logout();
        return redirect('/login');
    }
}