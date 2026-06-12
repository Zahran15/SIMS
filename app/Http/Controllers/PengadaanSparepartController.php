<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengadaanSparepart;
use App\Models\Sparepart;
use Illuminate\Support\Facades\Auth;

class PengadaanSparepartController extends Controller
{
    // 🔹 TAMPIL DATA 
    public function index()
    {
        $pengadaan = PengadaanSparepart::with('sparepart')->latest()->paginate(10);
        $sparepart = Sparepart::all(); 
        $role = Auth::user()->role;
        if ($role == 'admin') {
            return view('admin.pengadaan.pengadaan_sparepart.index', compact('pengadaan', 'sparepart'));
        } else {
            return view('owner.pengadaan.pengadaan_sparepart.index', compact('pengadaan', 'sparepart'));
        }
    }

    // 🔹 FORM TAMBAH 
    public function create()
    {
        $role = Auth::user()->role;
        if ($role !== 'admin') {abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');}
        $sparepart = Sparepart::all(); 
        return view('admin.pengadaan.pengadaan_sparepart.tambah', compact('sparepart'));
    }

    // 🔹 SIMPAN DATA 
    public function store(Request $request)
    {
        $role = Auth::user()->role;
        if ($role !== 'admin') {abort(403, 'Tindakan ini tidak diizinkan.');}
        $request->validate([
            'id_sparepart' => 'required|exists:sparepart,id_sparepart',
            'tgl_pesan' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'status_pengadaan' => 'required|in:dipesan,diterima,ditolak',
        ]);
        $total = $request->jumlah * $request->harga_beli;
        PengadaanSparepart::create([
            'id_sparepart' => $request->id_sparepart,
            'tgl_pesan' => $request->tgl_pesan,
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'total' => $total,
            'status_pengadaan' => $request->status_pengadaan,
        ]);
        if ($request->status_pengadaan === 'diterima') {
            $sparepart = Sparepart::where('id_sparepart', $request->id_sparepart)->firstOrFail();
            $sparepart->stok += $request->jumlah;
            $sparepart->status = $sparepart->stok > 0 ? 'tersedia' : 'tidak tersedia';
            $sparepart->save();
        }
        return redirect()->route('admin.pengadaan_sparepart.index')->with('success', 'Data pengadaan berhasil dicatat.');
    }

    // 🔹 FORM EDIT 
    public function edit($id)
    {
        $role = Auth::user()->role;
        if ($role !== 'admin') {abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');}
        $pengadaan = PengadaanSparepart::where('id_pengadaan', $id)->firstOrFail();
        $sparepart = Sparepart::all(); 
        return view('admin.pengadaan.pengadaan_sparepart.edit', compact('pengadaan', 'sparepart'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role; if ($role !== 'admin') {abort(403, 'Tindakan ini tidak diizinkan.');}
        $request->validate([
            'id_sparepart' => 'required|exists:sparepart,id_sparepart',
            'tgl_pesan' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'status_pengadaan' => 'required|in:dipesan,diterima,ditolak',
        ]);
        $pengadaan = PengadaanSparepart::where('id_pengadaan', $id)->firstOrFail();
        // KONDISI A: Jika status LAMA adalah 'diterima', kita harus tarik kembali (kurangi) stok lamanya dulu
        if ($pengadaan->status_pengadaan === 'diterima') {
            $sparepartLama = Sparepart::where('id_sparepart', $pengadaan->id_sparepart)->firstOrFail();
            $sparepartLama->stok -= $pengadaan->jumlah;
            $sparepartLama->status = $sparepartLama->stok > 0 ? 'tersedia' : 'tidak tersedia';
            $sparepartLama->save();
        }
        $totalBaru = $request->jumlah * $request->harga_beli;
        $pengadaan->update([
            'id_sparepart' => $request->id_sparepart,
            'tgl_pesan' => $request->tgl_pesan,
            'jumlah' => $request->jumlah,
            'harga_beli' => $request->harga_beli,
            'total' => $totalBaru,
            'status_pengadaan' => $request->status_pengadaan,
        ]);

        // KONDISI B: Jika status BARU yang dipilih adalah 'diterima', tambahkan stok ke sparepart baru
        if ($request->status_pengadaan === 'diterima') {
            $sparepartBaru = Sparepart::where('id_sparepart', $request->id_sparepart)->firstOrFail();
            $sparepartBaru->stok += $request->jumlah;
            $sparepartBaru->status = $sparepartBaru->stok > 0 ? 'tersedia' : 'tidak tersedia';
            $sparepartBaru->save();
        }
        return redirect()->route('admin.pengadaan_sparepart.index')->with('success', 'Data pengadaan berhasil diperbarui dan stok telah disesuaikan.');
    }

    // 🔹 DETAIL DATA
    public function detail($id)
    {
        $data = PengadaanSparepart::with('sparepart')->where('id_pengadaan', $id)->firstOrFail();
        $role = Auth::user()->role;
        if ($role == 'admin') {
            return view('admin.pengadaan.pengadaan_sparepart.detail', compact('data'));
        } else {
            return view('owner.pengadaan.pengadaan_sparepart.detail', compact('data'));
        }
    }

    // 🔹 HAPUS DATA
    public function destroy($id)
    {
        $role = Auth::user()->role;
        if ($role !== 'admin') {abort(403, 'Tindakan ini tidak diizinkan.');}
        $pengadaan = PengadaanSparepart::where('id_pengadaan', $id)->firstOrFail();
        if ($pengadaan->status_pengadaan === 'diterima') {
            $sparepart = Sparepart::where('id_sparepart', $pengadaan->id_sparepart)->firstOrFail();
            $sparepart->stok -= $pengadaan->jumlah;
            $sparepart->status = $sparepart->stok > 0 ? 'tersedia' : 'tidak tersedia';
            $sparepart->save();
        }
        $pengadaan->delete();
        return redirect()->route('admin.pengadaan_sparepart.index')->with('success', 'Data pengadaan berhasil dihapus dan penyesuaian stok telah dilakukan.');
    }
}