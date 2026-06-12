<?php

namespace App\Http\Controllers;

use App\Models\RequestSparepart;
use App\Models\PenugasanTeknisi;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestSparepartController extends Controller
{
    // 🔹 LIST REQUEST
    public function index()
    {
        $role = Auth::user()->role;
        $requestSparepart = RequestSparepart::with(['penugasan.servis', 'sparepart'])->latest()->paginate(10);
        if ($role == 'teknisi') {
            return view('teknisi.proses.request_sparepart.index', compact('requestSparepart'));
        } elseif ($role == 'admin') {
            return view('admin.pengadaan.request_sparepart.index', compact('requestSparepart'));
        } else {
            return view('owner.pengadaan.request_sparepart.index', compact('requestSparepart'));
        }
    }

    // 🔹 FORM TAMBAH
    public function create()
    {
        $penugasan = PenugasanTeknisi::all();
        $sparepart = Sparepart::where('stok', '>', 0)->get();
        return view('teknisi.proses.request_sparepart.tambah', compact('penugasan', 'sparepart'));
    }

    // 🔹 SIMPAN REQUEST (Teknisi)
    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan' => 'required',
            'id_sparepart' => 'required|exists:sparepart,id_sparepart', // 💡 Sesuai nama tabel & kolom migration
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string'
        ]);

        $sparepart = Sparepart::where('id_sparepart', $request->id_sparepart)->firstOrFail();
        if ($sparepart->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi! Stok saat ini: ' . $sparepart->stok])->withInput();
        }

        RequestSparepart::create([
            'id_penugasan' => $request->id_penugasan,
            'id_sparepart' => $request->id_sparepart,
            'jumlah' => $request->jumlah,
            'alasan' => $request->alasan,
            'status_request' => 'pending'
        ]);
        return redirect()->route('teknisi.request_sparepart.index')->with('success', 'Request berhasil dikirim');
    }

    // 🔹 DETAIL REQUEST
    public function detail($id)
    {
        $requestSparepart = RequestSparepart::with(['penugasan.servis', 'sparepart'])->where('id_request', $id)->firstOrFail();
        $role = Auth::user()->role;
        if ($role == 'teknisi') {
            return view('teknisi.proses.request_sparepart.detail', compact('requestSparepart'));
        } elseif ($role == 'admin') {
            return view('admin.pengadaan.request_sparepart.detail', compact('requestSparepart'));
        } else {
            return view('owner.pengadaan.request_sparepart.detail', compact('requestSparepart'));
        }
    }

    // 🔹 APPROVE 
    public function approve(Request $request, $id)
    {
        $requestSparepart = RequestSparepart::where('id_request', $id)->firstOrFail();
        if ($requestSparepart->status_request == 'disetujui') {
            return back()->with('error', 'Request ini sudah disetujui sebelumnya.');
        }
        $sparepart = Sparepart::where('id_sparepart', $requestSparepart->id_sparepart)->firstOrFail();
        if ($sparepart->stok < $requestSparepart->jumlah) {
            return back()->with('error', 'Stok gudang tidak mencukupi. Sisa stok: ' . $sparepart->stok);
        }
        $sparepart->stok -= $requestSparepart->jumlah;
        $sparepart->status = $sparepart->stok > 0 ? 'tersedia' : 'tidak tersedia';
        $sparepart->save();
        $requestSparepart->update(['status_request' => 'disetujui']);
        return back()->with('success', 'Request disetujui dan stok berhasil dipotong.');
    }

    // 🔹 REJECT 
    public function reject(Request $request, $id)
    {
        $requestSparepart = RequestSparepart::where('id_request', $id)->firstOrFail();
        if ($requestSparepart->status_request != 'pending') {
            return back()->with('error', 'Hanya request berstatus pending yang bisa ditolak.');
        }
        $requestSparepart->update(['status_request' => 'ditolak']);
        return back()->with('success', 'Request sparepart telah ditolak.');
    }
}