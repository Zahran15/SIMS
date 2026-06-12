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
        $id_pelanggan = Auth::guard('pelanggan')->id();
        if (!$id_pelanggan) {abort(401, 'Silahkan login terlebih dahulu.');
        }
        $riwayat = Servis::with(['booking'])->whereHas('booking', function ($query) use ($id_pelanggan) {$query->where('id_pelanggan', $id_pelanggan);
            })->where('status_servis', 'selesai') ->latest()->paginate(10);
        return view('pelanggan.proses.riwayat_servis.index', compact('riwayat'));
    }

    // 🔹 DETAIL NOTA / RINCIAN BIAYA SERVIS PELANGGAN
    public function show($id)
    {
        $id_pelanggan = Auth::guard('pelanggan')->id();
        $riwayat = Servis::with([
                'booking.pelanggan',
                'detailJasa.jasa',
                'detailSparepart.sparepart'
            ])->findOrFail($id);
        if ($riwayat->booking->id_pelanggan != $id_pelanggan) {abort(403, 'Anda tidak memiliki hak akses untuk melihat data ini.');
        }
        return view('pelanggan.proses.riwayat_servis.detail', compact('riwayat'));
    }
}