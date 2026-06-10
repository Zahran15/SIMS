<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServisStatusController extends Controller
{
    /**
     * Menampilkan daftar riwayat servis milik pelanggan yang sedang login.
     */
    public function index()
    {
        // Mengambil data servis yang 'id_pelanggan'-nya cocok dengan pelanggan yang login
        $riwayatServis = Servis::with([
            'booking', 
            'penugasan.user' // Untuk melihat siapa teknisi yang menangani
        ])
        ->whereHas('booking', function ($q) {
            // Mengunci pencarian berdasarkan ID Pelanggan yang sedang login
            // Jika kamu menggunakan custom guard (misal: 'pelanggan'), ganti jadi Auth::guard('pelanggan')->id()
            $q->where('id_pelanggan', Auth::guard('pelanggan')->id()); 
        })
        ->latest()
        ->paginate(10);

        return view('pelanggan.proses.servis_status.index', compact('riwayatServis'));
    }

    /**
     * Menampilkan detail status, progres, biaya, serta catatan teknisi ke pelanggan.
     */
    public function show($id)
    {
        // Mengambil detail satu data servis tertentu, tetapi diproteksi agar tidak bisa diintip pelanggan lain
        $servis = Servis::with([
            'booking',
            'penugasan.user',
            'detailJasa.jasa',
            'detailSparepart.sparepart'
        ])
        ->whereHas('booking', function ($q) {
            // Validasi keamanan: Pastikan booking ini memang milik si pelanggan
            $q->where('id_pelanggan', Auth::guard('pelanggan')->id());
        })
        ->findOrFail($id); // Jika mencoba akses ID milik orang lain, otomatis return 404 (Not Found)

        return view('pelanggan.proses.servis_status.detail', compact('servis'));
    }
}