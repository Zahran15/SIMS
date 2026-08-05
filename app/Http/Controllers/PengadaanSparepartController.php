<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengadaanSparepart;
use App\Models\Sparepart;
use App\Models\User; // <-- Import Model User
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotifInternal; // <-- Import NotifInternal

class PengadaanSparepartController extends Controller
{
    // 🔹 TAMPIL DATA 
    public function index(Request $request)
    {
        $query = PengadaanSparepart::query();
        if ($request->has('status_pengadaan') && $request->status_pengadaan != '') {
            $query->where('status_pengadaan', $request->status_pengadaan);
        }
        $pengadaan = $query->with('sparepart')->latest()->paginate(10);
        $totalNota = PengadaanSparepart::count();
        $totalModal = PengadaanSparepart::where('status_pengadaan', 'diterima')->sum('total');
        $totalDiterima = PengadaanSparepart::where('status_pengadaan', 'diterima')->count();
        $stats = ['total_nota' => $totalNota,'total_modal' => $totalModal,'total_diterima' => $totalDiterima];
        $sparepart = Sparepart::all(); 
        $role = Auth::user()->role;
        if ($role == 'admin') {
            return view('admin.pengadaan.pengadaan_sparepart.index', compact('pengadaan', 'sparepart', 'stats'));
        } else {
            return view('owner.pengadaan.pengadaan_sparepart.index', compact('pengadaan', 'sparepart', 'stats'));
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
        if ($role !== 'admin') { abort(403, 'Tindakan ini tidak diizinkan.'); }
        
        $request->validate([
            'id_sparepart' => 'required',
            'tgl_pesan'     => 'required',
            'jumlah'        => 'required',
            'harga_beli'    => 'required',
        ]);

        $total = $request->jumlah * $request->harga_beli;
        $pengadaan = PengadaanSparepart::create([
            'id_sparepart'     => $request->id_sparepart,
            'tgl_pesan'        => $request->tgl_pesan,
            'jumlah'           => $request->jumlah,
            'harga_beli'       => $request->harga_beli,
            'total'            => $total,
            'status_pengadaan' => 'diajukan',
        ]);

        // 🔔 NOTIFIKASI LONCENG KE OWNER
        $sparepartItem = Sparepart::find($request->id_sparepart);
        $namaSparepart = $sparepartItem->nama_sparepart ?? 'Sparepart';

        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            $owner->notify(new NotifInternal(
                'Pengajuan Pengadaan Sparepart',
                'Admin mengajukan pengadaan ' . $namaSparepart . ' sejumlah ' . $request->jumlah . ' unit (Total: Rp ' . number_format($total, 0, ',', '.') . ').',
                'fa-solid fa-cart-flatbed'
            ));
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
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Tindakan ini tidak diizinkan.');
        }

        $pengadaan = PengadaanSparepart::where('id_pengadaan', $id)->firstOrFail();
        // Hanya boleh diedit jika masih diajukan
        if ($pengadaan->status_pengadaan !== 'diajukan') {
            return redirect()->route('admin.pengadaan_sparepart.index')->with('error', 'Pengadaan yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'id_sparepart' => 'required',
            'tgl_pesan' => 'required',
            'jumlah' => 'required',
            'harga_beli' => 'required',
        ]);

        $pengadaan->update([
            'id_sparepart'      => $request->id_sparepart,
            'tgl_pesan'         => $request->tgl_pesan,
            'jumlah'            => $request->jumlah,
            'harga_beli'        => $request->harga_beli,
            'total'             => $request->jumlah * $request->harga_beli,
            'status_pengadaan'  => 'diajukan',
        ]);

        return redirect()->route('admin.pengadaan_sparepart.index')
            ->with('success', 'Data pengadaan berhasil diperbarui.');
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
        return redirect()->route('admin.pengadaan_sparepart.index')->with('success', 'Data pengadaan berhasil dihapus');
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }
        $pengadaan = PengadaanSparepart::with('sparepart')->findOrFail($id);
        if ($pengadaan->status_pengadaan != 'diajukan') {
            return back()->with('error', 'Pengadaan tidak dapat disetujui.');
        }
        $pengadaan->update(['status_pengadaan' => 'disetujui']);

        // 🔔 NOTIFIKASI LONCENG KE ADMIN
        $namaSparepart = $pengadaan->sparepart->nama_sparepart ?? 'Sparepart';
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifInternal(
                'Pengadaan Disetujui Owner',
                'Pengadaan ' . $namaSparepart . ' (' . $pengadaan->jumlah . ' unit) telah disetujui Owner.',
                'fa-solid fa-square-check'
            ));
        }

        return back()->with('success', 'Pengadaan berhasil disetujui.');
    }

    public function reject($id)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }
        $pengadaan = PengadaanSparepart::with('sparepart')->findOrFail($id);
        if ($pengadaan->status_pengadaan != 'diajukan') {
            return back()->with('error', 'Pengadaan tidak dapat ditolak.');
        }
        $pengadaan->update(['status_pengadaan' => 'ditolak']);

        // 🔔 NOTIFIKASI LONCENG KE ADMIN
        $namaSparepart = $pengadaan->sparepart->nama_sparepart ?? 'Sparepart';
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifInternal(
                'Pengadaan Ditolak Owner',
                'Pengajuan pengadaan ' . $namaSparepart . ' ditolak oleh Owner.',
                'fa-solid fa-rectangle-xmark'
            ));
        }

        return back()->with('success', 'Pengadaan berhasil ditolak.');
    }

    public function terima($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }
        $pengadaan = PengadaanSparepart::with('sparepart')->findOrFail($id);
        if ($pengadaan->status_pengadaan != 'disetujui') {
            return back()->with('error', 'Barang belum dapat diterima.');
        }

        $sparepart = Sparepart::findOrFail($pengadaan->id_sparepart);
        $sparepart->stok += $pengadaan->jumlah;
        $sparepart->harga_jual = $pengadaan->harga_beli;
        $sparepart->status = $sparepart->stok > 0 ? 'tersedia' : 'tidak tersedia';
        $sparepart->save();

        $pengadaan->update(['status_pengadaan' => 'diterima']);

        // 🔔 NOTIFIKASI LONCENG KE OWNER (Konfirmasi Stok Masuk)
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            $owner->notify(new NotifInternal(
                'Barang Pengadaan Diterima',
                'Fisik barang ' . $sparepart->nama_sparepart . ' (' . $pengadaan->jumlah . ' unit) telah diterima Admin dan stok gudang otomatis bertambah.',
                'fa-solid fa-boxes-stacked'
            ));
        }

        return back()->with('success', 'Barang berhasil diterima dan stok telah diperbarui.');
    }
}