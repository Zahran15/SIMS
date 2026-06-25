<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\PenugasanTeknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoriAktivitas;
use Carbon\Carbon;

class ServisKerjaController extends Controller
{
    public function index(Request $request)
    {
    $query = PenugasanTeknisi::where('id_user', Auth::id())->with(['servis.booking.pelanggan']);
    // 1. Filter Pencarian Nama Pelanggan (Melalui nested relation)
    if ($request->has('search') && $request->search != '') {
        $query->whereHas('servis.booking.pelanggan', function($q) use ($request) {
            $q->where('nama', 'LIKE', '%' . $request->search . '%');
        });
    }
    // 2. Filter Berdasarkan Tingkat Prioritas
    if ($request->has('prioritas') && $request->prioritas != '') {
        $query->where('prioritas', $request->prioritas);
    }
    // 3. Filter Berdasarkan Status Penugasan Teknisi
    if ($request->has('status_penugasan') && $request->status_penugasan != '') {
        $query->where('status_penugasan', $request->status_penugasan);
    }
        $penugasan = $query->with(['servis.booking.pelanggan'])->where('id_user', Auth::id())->latest()->paginate(10);
        return view('teknisi.proses.servis_kerja.index', compact('penugasan'));
    }

    // DETAIL SERVIS
    public function show($id)
    {
        $penugasan = PenugasanTeknisi::where('id_user', Auth::id())->where('id_penugasan', $id)->firstOrFail();
        $servis = Servis::with([
            'booking.pelanggan',
            'detailJasa.jasa',
            'detailSparepart.sparepart',
            'histori'
        ])->findOrFail($penugasan->id_servis);
        return view('teknisi.proses.servis_kerja.detail', compact('servis', 'penugasan'));
    }

    // FORM EDIT
    public function edit($id)
    {
        $penugasan = PenugasanTeknisi::with(['servis.booking.pelanggan'])->where('id_user', Auth::id())->where('id_penugasan', $id) ->firstOrFail();
        $servis = $penugasan->servis;
        return view('teknisi.proses.servis_kerja.edit', compact('servis', 'penugasan'));
    }

    // UPDATE STATUS TEKNISI
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_penugasan' => 'required', 
            'catatan_teknisi'  => 'required',
            'estimasi_selesai' => 'required|date'
            ]);
        $penugasan = PenugasanTeknisi::where('id_user', Auth::id())->where('id_penugasan', $id) ->firstOrFail();
        $estimasi = $request->estimasi_selesai ? Carbon::parse($request->estimasi_selesai)->format('Y-m-d') : $penugasan->estimasi_selesai;
        $penugasan->update([
            'catatan_teknisi'  => $request->catatan_teknisi, 
            'status_penugasan' => $request->status_penugasan, 
            'estimasi_selesai' => $estimasi
            ]);
        HistoriAktivitas::create([
            'id_user'    => Auth::id(),
            'id_servis'  => $penugasan->id_servis,
            'aktivitas'  => 'Teknisi: Update Pengerjaan',
            'keterangan' => 'Teknisi mengubah status tugas menjadi ('.$request->status_penugasan.'). Catatan: ' . ($request->catatan_teknisi ?? '-'),
            'tanggal'    => Carbon::now()
        ]);
        return redirect()->route('teknisi.servis_kerja.index')->with('success', 'Laporan pengerjaan berhasil diperbarui');
    }
}