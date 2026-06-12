<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Auth;

class RiwayatPekerjaanController extends Controller
{
    public function index()
    {
        $riwayat = PenugasanTeknisi::with(['servis.booking.pelanggan'])->where('id_user', Auth::id())->where('status_penugasan', 'selesai')->latest()->paginate(10);
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