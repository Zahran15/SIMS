<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

public function login(Request $request)
{
    $email = $request->email;
    $password = $request->password;
    $user = User::where('email', $email)->first();
    if ($user && $user->status == 'aktif' && Hash::check($password, $user->password)) {
        Session::put('login', true);
        Session::put('role', $user->role);
        Session::put('nama', $user->nama);
        Session::put('id_user', $user->id_user);
        if ($user->role == 'admin') return redirect('/admin/dashboard');
        if ($user->role == 'owner') return redirect('/owner/dashboard');
        if ($user->role == 'teknisi') return redirect('/teknisi/dashboard');
    }

$pelanggan = Pelanggan::where('email', $email)->first();
    if ($pelanggan && Hash::check($password, $pelanggan->password)) {
        if ($pelanggan->status == 'aktif') {
            Session::put('login', true);
            Session::put('role', 'pelanggan'); // Kita isi manual 'pelanggan' karena di tabel tidak ada kolom role
            Session::put('nama', $pelanggan->nama);
            Session::put('id_pelanggan', $pelanggan->id_pelanggan);
            return redirect('/pelanggan/dashboard');
        } else {
            return back()->with('error', 'Akun Anda nonaktif.');
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
    // ... kode generate kode_pelanggan tetap sama ...
    $tanggal = Carbon::now()->format('Ymd');
    $last = Pelanggan::whereDate('created_at', Carbon::today())->count();
    $kode = 'PLG' . $tanggal . '' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);

    Pelanggan::create([
        'kode_pelanggan' => $kode,
        'nama' => $request->nama,
        'alamat' => $request->alamat,
        'no_hp' => $request->no_hp,
        'email' => $request->email,
        'password' => $request->password, // JANGAN di-Hash::make di sini karena di Model sudah ada Mutator!
        'status' => 'aktif'
    ]);
    
    return redirect('/login')->with('success', 'Registrasi berhasil, silakan login');
}

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}