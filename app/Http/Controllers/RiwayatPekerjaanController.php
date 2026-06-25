<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class RiwayatPekerjaanController extends Controller
{
    public function index(Request $request)
    {
    // Mengambil query penugasan milik teknisi yang aktif login
    $query = PenugasanTeknisi::where('id_user', Auth::id())->with(['servis.booking.pelanggan']);
    // 1. Filter Pencarian Nama Pelanggan
    if ($request->has('search') && $request->search != '') {
        $query->whereHas('servis.booking.pelanggan', function($q) use ($request) {
            $q->where('nama', 'LIKE', '%' . $request->search . '%');
        });
    }
    // 2. Filter Berdasarkan Status Penugasan (Selesai / Gagal)
    if ($request->has('status_penugasan') && $request->status_penugasan != '') {
        $query->where('status_penugasan', $request->status_penugasan);
    }
        $riwayat = $query->with(['servis.booking.pelanggan'])->where('id_user', Auth::id())->where('status_penugasan', 'selesai')->latest()->paginate(10);
        return view('teknisi.proses.riwayat_pekerjaan.index', compact('riwayat')
        );
    }

    public function detail($id)
    {
        $riwayat = PenugasanTeknisi::with(['servis.booking.pelanggan', 'servis.detailJasa.jasa', 'servis.detailSparepart.sparepart'])->findOrFail($id);
        return view('teknisi.proses.riwayat_pekerjaan.detail', compact('riwayat')
        );
    }
}