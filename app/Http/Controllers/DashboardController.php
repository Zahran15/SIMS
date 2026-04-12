<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login
        if (!Session::has('login')) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $role = Session::get('role');

        // Arahkan ke view masing-masing berdasarkan folder yang kamu punya
        if ($role == 'admin') {
            return view('Admin.dashboard.index'); // Sesuaikan dengan struktur folder kamu
        } elseif ($role == 'owner') {
            return view('Owner.dashboard.index');
        } elseif ($role == 'teknisi') {
            return view('Teknisi.dashboard.index');
        } elseif ($role == 'pelanggan') {
            return view('Pelanggan.dashboard.index');
        }

        return redirect('/login');
    }
}