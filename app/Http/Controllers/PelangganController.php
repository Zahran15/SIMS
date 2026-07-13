<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();
        if ($request->filled('kode_pelanggan')) {
            $query->where('kode_pelanggan', 'LIKE', '%' . $request->kode_pelanggan . '%');
        }

        // Filter Nama Pelanggan
        if ($request->filled('nama_pelanggan')) {
            $query->where('nama', 'LIKE', $request->nama_pelanggan . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        $pelanggan = $query->latest()->paginate(10);
        $total_pelanggan = Pelanggan::count();
        $pelanggan_aktif = Pelanggan::where('status', 'aktif')->count();
        $pelanggan_nonaktif = Pelanggan::where('status', 'nonaktif')->count();
        $tanggal = Carbon::now()->format('Ymd');
        $count = Pelanggan::whereDate('created_at', Carbon::today())->count();
        $kode = 'PLG-' . $tanggal . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.master_data.pelanggan.index', compact('pelanggan', 'kode', 'total_pelanggan', 'pelanggan_aktif', 'pelanggan_nonaktif'));    
    }

    public function create()
    {
        $tanggal = Carbon::now()->format('Ymd');
        $count = Pelanggan::whereDate('created_at', Carbon::today())->count();
        $kode = 'PLG-' . $tanggal . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.master_data.pelanggan.tambah', compact('kode'));
    }

    public function store(Request $request)
    {
        $tanggal = Carbon::now()->format('Ymd');
        $count = Pelanggan::whereDate('created_at', Carbon::today())->count();
        $kode = 'PLG-' . $tanggal . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        Pelanggan::create([
            'kode_pelanggan' => $kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'aktif'
        ]);
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan baru berhasil ditambahkan dengan kode: '. $kode);
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.master_data.pelanggan.detail',compact('pelanggan'));    
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.master_data.pelanggan.edit',compact('pelanggan'));    
    }
    // Update Data
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'status' => $request->status,
        ];
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }
        $pelanggan->update($data);
        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui');    
    }

    // Hapus Data
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus');
    }
}