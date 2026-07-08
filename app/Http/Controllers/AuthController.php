<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendResetCodeMail;

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
        $kode = 'PLG-' . $tanggal . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
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

    // 1. Tampilkan Halaman Lupa Sandi
    public function showResetForm()
    {
        return view('auth.forgot_password');
    }

    // 2. Kirim Kode OTP via AJAX (Cek Multi-Table)
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $userType = null;

        // Cek apakah email ada di tabel users (Staff)
        $cekUser = User::where('email', $email)->first();
        // Cek apakah email ada di tabel pelanggan
        $cekPelanggan = Pelanggan::where('email', $email)->first();

        if ($cekUser) {
            $userType = 'staff';
        } elseif ($cekPelanggan) {
            $userType = 'pelanggan';
        } else {
            return response()->json(['errors' => true, 'message' => 'Email tidak terdaftar di sistem kami.'], 404);
        }
        $token = rand(100000, 999999);
        // Bersihkan token lama jika ada, simpan yang baru
        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'user_type' => $userType,
            'created_at' => Carbon::now()
        ]);
        Mail::to($email)->send(new SendResetCodeMail($token));
        return response()->json(['message' => 'Kode verifikasi telah dikirim ke email Anda!']);
    }

    // 3. Proses Reset Kata Sandi Baru ke Table yang Sesuai (Opsi A - Menghindari Double Hashing)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal berisi 8 karakter.'
        ]);

        // Validasi Token dan Waktu Expired (15 Menit)
        $resetData = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetData || Carbon::parse($resetData->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['token' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        if ($resetData->user_type === 'staff') {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->password = $request->password; 
                $user->save();
            }
        } else {
            $pelanggan = Pelanggan::where('email', $request->email)->first();
            if ($pelanggan) {
                $pelanggan->password = $request->password; 
                $pelanggan->save();
            }
        }

        // Hapus token setelah digunakan
        DB::table('password_resets')->where('email', $request->email)->delete();
        return redirect('/login')->with('success', 'Kata sandi berhasil diperbarui. Silakan masuk.');
    }
}