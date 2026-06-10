<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriAktivitas;

class HistoriAktivitasController extends Controller
{
    // 🔹 LIST HISTORI
    public function index(Request $request)
    {
        $query = HistoriAktivitas::with([
            'user',
            'servis.booking.pelanggan'
        ]);
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }
        $histori = $query->latest('id_histori')->paginate(10);
        return view('admin.proses.histori.index', compact('histori'));
    }

    // 🔹 DETAIL HISTORI
    public function detail($id)
    {
        $histori = HistoriAktivitas::with([
                'user',
                'servis.booking.pelanggan',
                'servis.detailJasa.jasa',
                'servis.detailSparepart.sparepart'
            ])
            ->findOrFail($id);

        return view('admin.proses.histori.detail', compact('histori'));
    }
}