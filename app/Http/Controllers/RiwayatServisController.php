<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatServisController extends Controller
{
    // 🔹 TAMPILKAN DAFTAR RIWAYAT SERVIS PELANGGAN
    public function index()
    {
        // Ambil ID Pelanggan yang sedang login lewat guard 'pelanggan'
        $id_pelanggan = Auth::guard('pelanggan')->id();

        if (!$id_pelanggan) {
            abort(401, 'Silahkan login terlebih dahulu.');
        }

        // Ambil data servis yang statusnya 'selesai' dan miliknya sendiri
        $riwayat = Servis::with(['booking'])
            ->whereHas('booking', function ($query) use ($id_pelanggan) {
                $query->where('id_pelanggan', $id_pelanggan);
            })
            ->where('status_servis', 'selesai') // Memastikan hanya tampil yang sudah selesai
            ->latest()
            ->paginate(10);

        return view('pelanggan.proses.riwayat_servis.index', compact('riwayat'));
    }

    // 🔹 DETAIL NOTA / RINCIAN BIAYA SERVIS PELANGGAN
    public function show($id)
    {
        $id_pelanggan = Auth::guard('pelanggan')->id();

        // Cari data servis beserta rincian jasa, sparepart, dan data booking-nya
        $riwayat = Servis::with([
                'booking.pelanggan',
                'detailJasa.jasa',
                'detailSparepart.sparepart'
            ])->findOrFail($id);

        // 🛑 SECURITY CHECK: Pastikan data servis ini benar-benar milik pelanggan yang login
        if ($riwayat->booking->id_pelanggan != $id_pelanggan) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat data ini.');
        }

        return view('pelanggan.proses.riwayat_servis.detail', compact('riwayat'));
    }
}