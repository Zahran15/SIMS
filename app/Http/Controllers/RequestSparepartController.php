<?php

namespace App\Http\Controllers;

use App\Models\RequestSparepart;
use App\Models\PenugasanTeknisi;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailServisSparepart;
use App\Http\Controllers\ServisController;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifRequestSparepart;

class RequestSparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = RequestSparepart::query();

        // 🔹 1. FILTER PENCARIAN & STATUS (Berlaku global untuk user yang berhak mencari)
        if ($request->filled('kode_servis')) {
            $query->whereHas('penugasan.servis', function ($q) use ($request) {
                $q->where('kode_servis', 'LIKE', '%' . $request->kode_servis . '%');
            });
        }

        if ($request->filled('nama_pelanggan')) {
            $query->whereHas('penugasan.servis.booking.pelanggan', function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->nama_pelanggan . '%');
            });
        }

        if ($request->filled('status_request')) {
            $query->where('status_request', $request->status_request);
        }

        // 🔹 2. CEK GUARD PELANGGAN TERLEBIH DAHULU
        if (Auth::guard('pelanggan')->check()) {
            $id_pelanggan = Auth::guard('pelanggan')->id();
            $query->whereIn('status_request', ['dikirim_ke_pelanggan', 'disetujui_pelanggan', 'disetujui', 'ditolak'])
                  ->whereHas('penugasan.servis.booking', function ($q) use ($id_pelanggan) {
                      $q->where('id_pelanggan', $id_pelanggan); 
                  });
            $totalRequest = (clone $query)->count();
            $totalDisetujui = (clone $query)->where('status_request', 'disetujui')->count();
            $totalAntrean = (clone $query)->whereIn('status_request', ['dikirim_ke_pelanggan', 'disetujui_pelanggan'])->count();
            $requestSparepart = $query->with(['penugasan.servis.booking.pelanggan', 'sparepart'])->latest()->paginate(10);
            return view('pelanggan.proses.request_sparepart.index', compact('requestSparepart', 'totalRequest', 'totalDisetujui', 'totalAntrean'));
        }

        // 🔹 3. JIKA BUKAN PELANGGAN, GUNAKAN GUARD DEFAULT (ADMIN, TEKNISI, OWNER)
        $role = Auth::user()->role ?? null;
        if (!$role) {
            abort(401, 'Silahkan login terlebih dahulu.');
        }

        if ($role == 'teknisi') {
            $query->whereHas('penugasan', function($q) {
                $q->where('id_user', Auth::id()); 
            });
        }

        // 🔹 4. HITUNG STATISTIK KESELURUHAN (Menggunakan `clone` agar tidak merusak query utama)
        $statsQuery = clone $query;
        $totalRequest = $statsQuery->count();
        $totalDisetujui = (clone $statsQuery)->where('status_request', 'disetujui')->count();
        $totalAntrean = (clone $statsQuery)->whereIn('status_request', ['pending_admin', 'dikirim_ke_pelanggan', 'disetujui_pelanggan'])->count();
        $requestSparepart = $query->with(['penugasan.servis.booking.pelanggan', 'sparepart'])->latest()->paginate(10);

        if ($role == 'teknisi') {
            return view('teknisi.proses.request_sparepart.index', compact('requestSparepart', 'totalRequest', 'totalDisetujui', 'totalAntrean'));
        } elseif ($role == 'admin') {
            return view('admin.pengadaan.request_sparepart.index', compact('requestSparepart', 'totalRequest', 'totalDisetujui', 'totalAntrean'));
        } else {
            // Halaman Owner yang tadi Anda tanyakan
            return view('owner.pengadaan.request_sparepart.index', compact('requestSparepart', 'totalRequest', 'totalDisetujui', 'totalAntrean'));
        }
    }

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

    // 🔹 AKSI APPROVE PELANGGAN
    public function approvePelanggan($id)
    {
        if (!Auth::guard('pelanggan')->check()) abort(403);
        $requestSparepart = RequestSparepart::findOrFail($id);
        if ($requestSparepart->status_request != 'dikirim_ke_pelanggan') {
            return back()->with('error', 'Permintaan tidak butuh konfirmasi saat ini.');
        }

        $requestSparepart->update(['status_request' => 'disetujui_pelanggan']);
        return back()->with('success', 'Anda menyetujui request ini. Menunggu Admin melakukan validasi akhir dan pemotongan stok.');
    }

    // 🔹 AKSI REJECT PELANGGAN
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

    public function create()
    {
        $penugasan = PenugasanTeknisi::all();
        $sparepart = Sparepart::where('stok', '>', 0)->get();
        return view('teknisi.proses.request_sparepart.tambah', compact('penugasan', 'sparepart'));
    }

    // 🔹 BAGIAN STORE DIUBAH MENJADI ARRAY LOOPING
    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan'   => 'required',
            'id_sparepart'   => 'required|array',
            'id_sparepart.*' => 'required|exists:sparepart,id_sparepart', 
            'jumlah'         => 'required|array',
            'jumlah.*'       => 'required|integer|min:1',
            'alasan'         => 'required|string'
        ]);

        // Melakukan looping berdasarkan array input sparepart yang dikirim oleh form
        foreach ($request->id_sparepart as $index => $sparepartId) {
            RequestSparepart::create([
                'id_penugasan'   => $request->id_penugasan,
                'id_sparepart'   => $sparepartId,
                'jumlah'         => $request->jumlah[$index],
                'alasan'         => $request->alasan,
                'status_request' => 'pending_admin'
            ]);
        }
        
        return redirect()->route('teknisi.request_sparepart.index')->with('success', 'Semua request sparepart berhasil diajukan ke Admin.');
    }

    // 🔹 AKSI KIRIM KE PELANGGAN
    public function kirimKePelanggan($id)
    {
        $requestSparepart = RequestSparepart::with(['penugasan.servis.booking.pelanggan', 'sparepart'])->findOrFail($id);
        
        if ($requestSparepart->status_request != 'pending_admin') {
            return back()->with('error', 'Request tidak dapat dikirim ke pelanggan.');
        }
        $requestSparepart->update(['status_request' => 'dikirim_ke_pelanggan']);
        if ($requestSparepart->penugasan->servis->booking->pelanggan) {
            $pelanggan = $requestSparepart->penugasan->servis->booking->pelanggan;
            
            $emailPelanggan = $pelanggan->email;
            $namaPelanggan  = $pelanggan->nama;
            $namaLaptop     = $requestSparepart->penugasan->servis->booking->merk_tipe;
            $namaSparepart  = $requestSparepart->sparepart->nama_sparepart ?? 'Komponen';
            $jumlah         = $requestSparepart->jumlah;
            $harga          = $requestSparepart->sparepart->harga_jual;

            try {
                Notification::route('mail', $emailPelanggan)
                    ->notify(new NotifRequestSparepart($namaPelanggan, $namaLaptop, $namaSparepart, $jumlah, $harga));
            } catch (\Exception $e) {
                return redirect()->route('admin.request_sparepart.index')->with('success', 'Request berhasil diteruskan ke pelanggan, namun gagal mengirim notifikasi email.');
            }
        }
        return redirect()->route('admin.request_sparepart.index')->with('success', 'Request sparepart berhasil diteruskan ke Pelanggan dan notifikasi email telah dikirim.');
    }

    // 🔹 APPROVE FINAL DAN KONEKSI KE NOTA DETAIL SERVIS
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
        $id_servis = $requestSparepart->penugasan->servis->id_servis;
        if ($id_servis) {
            DetailServisSparepart::create([
                'id_servis'    => $id_servis,
                'id_sparepart' => $requestSparepart->id_sparepart,
                'qty'          => $requestSparepart->jumlah,
                'harga'        => $sparepart->harga_jual,
                'subtotal'     => $sparepart->harga_jual * $requestSparepart->jumlah,
            ]);

            // 4. HITUNG ULANG TOTAL BIAYA SERVIS
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