<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenugasanTeknisi;
use App\Models\Servis;
use App\Models\User;

class PenugasanTeknisiController extends Controller
{
    public function index(Request $request)
{
    $query = Servis::query();
    if ($request->filled('kode_servis')) {
        $query->whereHas('penugasan.servis', function ($q) use ($request) {
            $q->where('kode_servis', 'LIKE', '%' . $request->kode_servis . '%');
        });
    }
    // Filter Nama Pelanggan
    if ($request->filled('nama_pelanggan')) {
        $query->whereHas('penugasan.servis.booking.pelanggan', function ($q) use ($request) {
            $q->where('nama', 'LIKE', '%' . $request->nama_pelanggan . '%');
        });
    }
    if ($request->has('id_teknisi') && $request->id_teknisi != '') {
        $query->whereHas('penugasan', function($q) use ($request) {
            $q->where('id_user', $request->id_teknisi); 
        });
    }
    if ($request->has('status_penugasan') && $request->status_penugasan != '') {
        $query->whereHas('penugasan', function($q) use ($request) {
            $q->where('status_penugasan', $request->status_penugasan);
        });
    }
    if ($request->has('prioritas') && $request->prioritas != '') {
        $query->whereHas('penugasan', function($q) use ($request) {
            $q->where('prioritas', $request->prioritas);
        });
    }
    $servis = $query->with(['booking.pelanggan', 'penugasan.teknisi'])->latest()->paginate(10);
    $list_teknisi = User::where('role', 'teknisi')->get();
    return view('admin.proses.penugasan.index', compact('servis', 'list_teknisi'));
    }

    public function create($id_servis)
    {
        $servis = Servis::findOrFail($id_servis);
        $teknisi = User::where('role', 'teknisi')->get();
        return view('admin.proses.penugasan.tambah', compact('servis', 'teknisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_servis' => 'required',
            'id_user' => 'required',
            'status_penugasan' => 'required',
            'estimasi_selesai' => 'nullable|date',
        ]);

        PenugasanTeknisi::create([
            'id_servis' => $request->id_servis,
            'id_user' => $request->id_user,
            'prioritas' => null,
            'estimasi_selesai' => $request->estimasi_selesai,
            'status_penugasan' => $request->status_penugasan,
            'catatan_teknisi' => $request->catatan_teknisi
        ]);

        Servis::where('id_servis', $request->id_servis)->update(['status_servis' => 'proses']);
        return redirect()->route('admin.penugasan.index')->with('success', 'Teknisi berhasil ditugaskan');
    }

    public function edit($id)
    {
        $penugasan = PenugasanTeknisi::findOrFail($id);
        $teknisi = User::where('role', 'teknisi')->get();
        return view('admin.proses.penugasan.edit', compact('penugasan', 'teknisi'));
    }

    public function update(Request $request, $id)
    {
        $penugasan = PenugasanTeknisi::findOrFail($id);
        $penugasan->update([
            'id_user' => $request->id_user,
            'prioritas' => $request->prioritas,
            'estimasi_selesai' => $request->estimasi_selesai,
            'status_penugasan' => $request->status_penugasan,
            'catatan_teknisi' => $request->catatan_teknisi
        ]);
        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil diupdate');    
    }

    public function show($id)
    {
        $penugasan = PenugasanTeknisi::with('servis.booking', 'teknisi')->findOrFail($id);
        return view('admin.proses.penugasan.detail', compact('penugasan'));
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        PenugasanTeknisi::findOrFail($id)->delete();
        return back()->with('success', 'Penugasan dihapus');
    }
}