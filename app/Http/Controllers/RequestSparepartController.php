<?php

namespace App\Http\Controllers;

use App\Models\RequestSparepart;
use App\Models\PenugasanTeknisi;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailServisSparepart;
use App\Http\Controllers\ServisController;

class RequestSparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = RequestSparepart::query();
        
        if ($request->has('status_request') && $request->status_request != '') {
            $query->where('status_request', $request->status_request);
        }

        // 1. CEK GUARD PELANGGAN TERLEBIH DAHULU
        if (Auth::guard('pelanggan')->check()) {
            $id_pelanggan = Auth::guard('pelanggan')->id();

            // Pelanggan hanya melihat data miliknya yang statusnya dikirim oleh admin, disetujui, atau ditolak
            $query->whereIn('status_request', ['dikirim_ke_pelanggan', 'disetujui_pelanggan', 'disetujui', 'ditolak'])
                  ->whereHas('penugasan.servis.booking', function ($q) use ($id_pelanggan) {
                      $q->where('id_pelanggan', $id_pelanggan); 
                  });

            $requestSparepart = $query->with(['penugasan.servis.booking.pelanggan', 'sparepart'])->latest()->paginate(10);
            
            return view('pelanggan.proses.request_sparepart.index', compact('requestSparepart'));
        }

        // 2. JIKA BUKAN PELANGGAN, GUNAKAN GUARD DEFAULT (ADMIN, TEKNISI, OWNER)
        $role = Auth::user()->role ?? null;

        if (!$role) {
            abort(401, 'Silahkan login terlebih dahulu.');
        }

        $requestSparepart = $query->with(['penugasan.servis.booking.pelanggan', 'sparepart'])->latest()->paginate(10);

        if ($role == 'teknisi') {
            return view('teknisi.proses.request_sparepart.index', compact('requestSparepart'));
        } elseif ($role == 'admin') {
            return view('admin.pengadaan.request_sparepart.index', compact('requestSparepart'));
        } else {
            return view('owner.pengadaan.request_sparepart.index', compact('requestSparepart'));
        }
    }

    // 🔹 DETAIL REQUEST SPAREPART (Sesuaikan juga pengecekan Guard-nya)
    public function detail($id)
    {
        $requestSparepart = RequestSparepart::with(['penugasan.servis.booking.pelanggan', 'sparepart'])->findOrFail($id);
        
        // Cek jika yang akses adalah pelanggan
        if (Auth::guard('pelanggan')->check()) {
            $id_pelanggan = Auth::guard('pelanggan')->id();
            
            // Validasi hak akses kepemilikan data pelanggan
            if ($requestSparepart->penugasan->servis->booking->id_pelanggan != $id_pelanggan) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat data ini.');
            }
            
            return view('pelanggan.proses.request_sparepart.detail', compact('requestSparepart'));
        }

        // Jika staff internal (teknisi/admin/owner)
        $role = Auth::user()->role ?? null;
        if (!$role) abort(401);

        if ($role == 'teknisi') {
            return view('teknisi.proses.request_sparepart.detail', compact('requestSparepart'));
        } elseif ($role == 'admin') {
            return view('admin.pengadaan.request_sparepart.detail', compact('requestSparepart'));
        } else {
            return view('owner.pengadaan.request_sparepart.detail', compact('requestSparepart'));
        }
    }

    // 🔹 AKSI APPROVE PELANGGAN (Gunakan guard pelanggan)
    public function approvePelanggan($id)
    {
        if (!Auth::guard('pelanggan')->check()) abort(403);

        $requestSparepart = RequestSparepart::findOrFail($id);
        if ($requestSparepart->status_request != 'dikirim_ke_pelanggan') {
            return back()->with('error', 'Permintaan tidak butuh konfirmasi saat ini.');
        }

        $requestSparepart->update(['status_request' => 'disetujui_pelanggan']);
        return back()->with('success', 'Anda menyetujui request ini. Menunggu Admin melakukan validasi akhir & pemotongan stok.');
    }

    // 🔹 AKSI REJECT PELANGGAN (Gunakan guard pelanggan)
    public function rejectPelanggan($id)
    {
        if (!Auth::guard('pelanggan')->check()) abort(403);

        $requestSparepart = RequestSparepart::findOrFail($id);
        if ($requestSparepart->status_request != 'dikirim_ke_pelanggan') {
            return back()->with('error', 'Permintaan tidak bisa ditolak saat ini.');
        }

        $requestSparepart->update(['status_request' => 'ditolak']);
        return back()->with('success', 'Anda menolak pergantian sparepart ini.');
    }

    // 🔹 SISA FUNGSI DIBAWAH TETAP SAMA (Milik Admin/Teknisi)
    public function create()
    {
        $penugasan = PenugasanTeknisi::all();
        $sparepart = Sparepart::where('stok', '>', 0)->get();
        return view('teknisi.proses.request_sparepart.tambah', compact('penugasan', 'sparepart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan' => 'required',
            'id_sparepart' => 'required|exists:sparepart,id_sparepart', 
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string'
        ]);

        RequestSparepart::create([
            'id_penugasan' => $request->id_penugasan,
            'id_sparepart' => $request->id_sparepart,
            'jumlah' => $request->jumlah,
            'alasan' => $request->alasan,
            'status_request' => 'pending_admin'
        ]);
        
        return redirect()->route('teknisi.request_sparepart.index')->with('success', 'Request sparepart berhasil diajukan ke Admin.');
    }

    public function kirimKePelanggan($id)
    {
        $requestSparepart = RequestSparepart::findOrFail($id);
        if ($requestSparepart->status_request != 'pending_admin') {
            return back()->with('error', 'Request tidak dapat dikirim ke pelanggan.');
        }

        $requestSparepart->update(['status_request' => 'dikirim_ke_pelanggan']);
        return back()->with('success', 'Request sparepart berhasil diteruskan ke Pelanggan.');
    }

    public function approveFinal($id)
    {
        $requestSparepart = RequestSparepart::with('penugasan.servis')->findOrFail($id);

        if ($requestSparepart->status_request != 'disetujui_pelanggan') {
            return back()->with('error', 'Pelanggan belum menyetujui request ini.');
        }

        $sparepart = Sparepart::findOrFail($requestSparepart->id_sparepart);
        if ($sparepart->stok < $requestSparepart->jumlah) {
            return back()->with('error', 'Stok gudang kurang!');
        }

        // 1. Potong Stok Gudang
        $sparepart->stok -= $requestSparepart->jumlah;
        $sparepart->status = $sparepart->stok > 0 ? 'tersedia' : 'tidak tersedia';
        $sparepart->save();

        // 2. Update Status Request Sparepart
        $requestSparepart->update(['status_request' => 'disetujui']);

        // 3. OTOMATIS MASUKKAN KE DATA DETAIL SERVIS PELANGGAN
        // Ambil id_servis melalui relasi request_spareparts -> penugasan -> servis
        $id_servis = $requestSparepart->penugasan->servis->id_servis;
        if ($id_servis) {
            DetailServisSparepart::create([
                'id_servis'    => $id_servis,
                'id_sparepart' => $requestSparepart->id_sparepart,
                'qty'          => $requestSparepart->jumlah,
                'harga'        => $sparepart->harga_jual,
                'subtotal'     => $sparepart->harga_jual * $requestSparepart->jumlah,
            ]);

            // 4. HITUNG ULANG TOTAL BIAYA SERVIS (Memanggil static helper di ServisController)
            ServisController::updateTotalBiaya($id_servis);
        }

        return back()->with('success', 'Request disetujui! Stok dipotong dan otomatis ditambahkan ke nota servis pelanggan.');
    }

    public function rejectAdmin($id)
    {
        $requestSparepart = RequestSparepart::findOrFail($id);
        if (!in_array($requestSparepart->status_request, ['pending_admin', 'disetujui_pelanggan'])) {
            return back()->with('error', 'Request tidak bisa ditolak pada tahap ini.');
        }

        $requestSparepart->update(['status_request' => 'ditolak']);
        return back()->with('success', 'Request sparepart ditolak oleh Admin.');
    }
}