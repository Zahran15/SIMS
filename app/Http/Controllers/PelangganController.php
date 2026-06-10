<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::latest()->paginate(10);
        $tanggal = Carbon::now()->format('Ymd');
        $count = Pelanggan::whereDate('created_at', Carbon::today())->count();
        $kode = 'PLG-' . $tanggal . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.master_data.pelanggan.index', compact('pelanggan', 'kode'));    
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
        return redirect()->route('admin.pelanggan')->with('success', 'Pelanggan baru berhasil ditambahkan dengan kode: '. $kode);
    }

    // Ambil data untuk Edit/Detail
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
        return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil diperbarui');    
    }

    // Hapus Data
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus');
    }
}