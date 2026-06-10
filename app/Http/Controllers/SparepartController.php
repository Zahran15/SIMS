<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;
use Illuminate\Support\Facades\Auth; 

class SparepartController extends Controller
{
    // 🔹 TAMPIL DATA
    public function index()
    {
        $sparepart = Sparepart::latest()->paginate(10);
        $role = Auth::user()->role; 
        if ($role == 'admin') {
            return view('admin.master_data.sparepart.index', compact('sparepart', 'role'));
        } else {
            return view('owner.master_data.sparepart.index', compact('sparepart', 'role'));
        }
    }

    // 
    public function create()
    {
        $role = Auth::user()->role;
        if ($role == 'admin') {
            return view('admin.master_data.sparepart.tambah', compact('role'));
        }
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }

    // 🔹 SIMPAN DATA
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        Sparepart::create([
            'nama_sparepart' => $request->nama_sparepart,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga_jual' => $request->harga_jual,
            'status' => $request->stok > 0 ? 'tersedia' : 'tidak tersedia',
        ]);

        return redirect()->route('admin.sparepart.index')->with('success', 'Data berhasil ditambahkan');
    }

    // 🔹 EDIT 
    public function edit($id)
    {
        $sparepart = Sparepart::where('id_sparepart', $id)->firstOrFail();
        $role = Auth::user()->role;
        if ($role == 'admin') {
            return view('admin.master_data.sparepart.edit', compact('sparepart', 'role'));
        }
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
        $data = Sparepart::where('id_sparepart', $id)->firstOrFail();
        $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $data->update([
            'nama_sparepart' => $request->nama_sparepart,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga_jual' => $request->harga_jual,
            'status' => $request->stok > 0 ? 'tersedia' : 'tidak tersedia',
        ]);
    return redirect()->route('admin.sparepart.index')->with('success', 'Data berhasil diupdate');    
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        Sparepart::where('id_sparepart', $id)->firstOrFail()->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}