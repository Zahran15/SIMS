<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tools;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ToolsController extends Controller
{
    // 🔹 TAMPIL DATA
    public function index()
    {
        $tools = Tools::with('user')->latest()->paginate(10);
        $role = Auth::user()->role; 

        if ($role == 'admin') {
            return view('admin.pengadaan.pengadaan_tools.index', compact('tools', 'role'));
        } else {
            return view('owner.pengadaan.pengadaan_tools.index', compact('tools', 'role'));
        }
    }

    // 🔹 HALAMAN TAMBAH DATA 
    public function create()
    {
        $teknisi = User::where('role', 'teknisi')->get();
        $role = Auth::user()->role;
        return view('admin.pengadaan.pengadaan_tools.tambah', compact('teknisi', 'role'));
    }

    // 🔹 SIMPAN DATA 
    public function store(Request $request)
    {
        $request->validate([
            'id_user'    => 'required|exists:users,id', 
            'nama_tools' => 'required',
            'jumlah'     => 'required|integer|min:0',
        ]);

        Tools::create([
            'id_user'    => $request->id_user,
            'nama_tools' => $request->nama_tools,
            'jumlah'     => $request->jumlah,
            'status'     => $request->jumlah > 0 ? 'tersedia' : 'tidak tersedia',
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Data tools berhasil ditambahkan');
    }

    // 🔹 EDIT DATA 
    public function edit($id)
    {
        $tool = Tools::findOrFail($id);
        $teknisi = User::where('role', 'teknisi')->get();
        $role = Auth::user()->role;
        return view('admin.pengadaan.pengadaan_tools.edit', compact('tool', 'teknisi', 'role'));
    }

    // 🔹 UPDATE DATA 
    public function update(Request $request, $id)
    {
        $data = Tools::findOrFail($id);

        $request->validate([
            'id_user'    => 'required|exists:users,id',
            'nama_tools' => 'required',
            'jumlah'     => 'required|integer|min:0',
        ]);

        $data->update([
            'id_user'    => $request->id_user,
            'nama_tools' => $request->nama_tools,
            'jumlah'     => $request->jumlah,
            'status'     => $request->jumlah > 0 ? 'tersedia' : 'tidak tersedia',
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Data tools berhasil diupdate');
    }

    // 🔹 DELETE 
    public function destroy($id)
    {
        Tools::findOrFail($id)->delete();
        return back()->with('success', 'Data tools berhasil dihapus');
    }
}