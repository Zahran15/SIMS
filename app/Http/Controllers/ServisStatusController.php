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
        $riwayatServis = Servis::with(['booking', 'penugasan.user' ])
        ->whereHas('booking', function ($q) {$q->where('id_pelanggan', Auth::guard('pelanggan')->id());
        })->latest()->paginate(10);
        return view('pelanggan.proses.servis_status.index', compact('riwayatServis'));
    }

    /**
     * Menampilkan detail status, progres, biaya, serta catatan teknisi ke pelanggan.
     */
    public function show($id)
    {
        $servis = Servis::with(['booking', 'penugasan.user', 'detailJasa.jasa', 'detailSparepart.sparepart'])
        ->whereHas('booking', function ($q) {$q->where('id_pelanggan', Auth::guard('pelanggan')->id());
        })->findOrFail($id); 
        return view('pelanggan.proses.servis_status.detail', compact('servis'));
    }
}