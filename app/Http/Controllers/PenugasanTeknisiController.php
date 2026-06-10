<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenugasanTeknisi;
use App\Models\Servis;
use App\Models\User;

class PenugasanTeknisiController extends Controller
{
    // 🔹 LIST SERVIS YANG BELUM ADA TEKNISI
    public function index()
    {
        $servis = Servis::with('booking', 'penugasan')
            ->latest()
            ->paginate(10);

        return view('admin.proses.penugasan.index', compact('servis'));
    }

    // 🔹 FORM PILIH TEKNISI
    public function create($id_servis)
    {
        $servis = Servis::findOrFail($id_servis);

        // Ambil user role teknisi
        $teknisi = User::where('role', 'teknisi')->get();
        return view('admin.proses.penugasan.tambah', compact('servis', 'teknisi'));
    }

    // 🔹 SIMPAN PENUGASAN
    public function store(Request $request)
    {
        $request->validate([
            'id_servis' => 'required',
            'id_user' => 'required',
            'prioritas' => 'required',
            'status_penugasan' => 'required'
        ]);

        PenugasanTeknisi::create([
            'id_servis' => $request->id_servis,
            'id_user' => $request->id_user,
            'prioritas' => $request->prioritas,
            'estimasi_selesai' => $request->estimasi_selesai,
            'status_penugasan' => $request->status_penugasan,
            'catatan_teknisi' => $request->catatan_teknisi
        ]);

        // 🔥 otomatis ubah status servis jadi proses
        Servis::where('id_servis', $request->id_servis)
            ->update(['status_servis' => 'proses']);

        return redirect()->route('admin.penugasan.index')
            ->with('success', 'Teknisi berhasil ditugaskan');
    }

    // 🔹 EDIT PENUGASAN
    public function edit($id)
    {
        $penugasan = PenugasanTeknisi::findOrFail($id);
        $teknisi = User::where('role', 'teknisi')->get();

        return view('admin.proses.penugasan.edit', compact('penugasan', 'teknisi'));
    }

    // 🔹 UPDATE
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
        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan berhasil diupdate');    }

    // 🔹 DETAIL
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