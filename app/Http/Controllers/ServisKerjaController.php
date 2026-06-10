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
    public function index()
    {
        $penugasan = PenugasanTeknisi::with([
            'servis.booking.pelanggan'
        ])
        ->where('id_user', Auth::id())
        ->latest()
        ->paginate(10);

        return view(
            'teknisi.proses.servis_kerja.index',
            compact('penugasan')
        );
    }

    // DETAIL SERVIS
    public function show($id)
    {
        // Ubah 'id' menjadi 'id_penugasan'
        $penugasan = PenugasanTeknisi::where('id_user', Auth::id())
            ->where('id_penugasan', $id) 
            ->firstOrFail();

        $servis = Servis::with([
            'booking.pelanggan',
            'penugasan.user',
            'detailJasa.jasa',
            'detailSparepart.sparepart',
            'histori'
        ])->findOrFail($penugasan->id_servis);

        return view('teknisi.proses.servis_kerja.detail', compact('servis', 'penugasan'));
    }

    // FORM EDIT
    public function edit($id)
    {
        // Ubah 'id' menjadi 'id_penugasan'
        $penugasan = PenugasanTeknisi::with(['servis.booking.pelanggan'])
            ->where('id_user', Auth::id())
            ->where('id_penugasan', $id) 
            ->firstOrFail();

        $servis = $penugasan->servis;

        return view('teknisi.proses.servis_kerja.edit', compact('servis', 'penugasan'));
    }

    // UPDATE STATUS TEKNISI
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_penugasan' => 'required',
            'catatan_teknisi'  => 'nullable|string'
        ]);

        // Ubah 'id' menjadi 'id_penugasan'
        $penugasan = PenugasanTeknisi::where('id_user', Auth::id())
            ->where('id_penugasan', $id) 
            ->firstOrFail();

        $penugasan->update([
            'catatan_teknisi'  => $request->catatan_teknisi, 
            'status_penugasan' => $request->status_penugasan 
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