<?php

namespace App\Http\Controllers;

use App\Models\JasaServis;
use Illuminate\Http\Request;

class JasaServisController extends Controller
{
    public function index()
    {
        $jasa = JasaServis::paginate(10);

        return view('admin.master_data.jasa_servis.index', compact('jasa'));
    }

    public function create()
    {
        return view('admin.master_data.jasa_servis.tambah');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_jasa' => 'required',
            'harga' => 'required|numeric'
        ]);

        JasaServis::create($data);

        return redirect()
            ->route('admin.jasa_servis')
            ->with('success', 'Data jasa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jasa = JasaServis::findOrFail($id);

        return view(
            'admin.master_data.jasa_servis.edit',
            compact('jasa')
        );
    }

    public function update(Request $request, $id)
    {
        $jasa = JasaServis::findOrFail($id);

        $data = $request->validate([
            'nama_jasa' => 'required',
            'harga' => 'required|numeric'
        ]);

        $jasa->update($data);

        return redirect()
            ->route('admin.jasa_servis')
            ->with('success', 'Data jasa berhasil diupdate');
    }

    public function destroy($id)
    {
        $jasa = JasaServis::findOrFail($id);
        $jasa->delete();

        return redirect()
            ->route('admin.jasa_servis')
            ->with('success', 'Data jasa berhasil dihapus');
    }
}