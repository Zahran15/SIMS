<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pelanggan;
use App\Models\Servis;
use App\Models\Pembayaran;
use App\Models\HistoriAktivitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // 🔹 TAMPIL DATA
    public function index()
    {
        // Jika yang login adalah admin lewat guard web biasa
        if (Auth::guard('web')->check() && Auth::user()->role == 'admin') { 
            $booking = Booking::with('pelanggan')->latest()->paginate(10);
            return view('admin.proses.booking.index', compact('booking'));
        } 
        // Jika yang login pelanggan lewat guard 'pelanggan'
        $id_pelanggan = Auth::guard('pelanggan')->id();
        if (!$id_pelanggan) {
            abort(401, 'Silahkan login terlebih dahulu.');
        }
        $booking = Booking::where('id_pelanggan', $id_pelanggan)->latest()->paginate(10);
        return view('pelanggan.proses.booking.index', compact('booking'));
    }

    // 🔹 FORM TAMBAH
    public function create()
    {
        $tanggal = Carbon::now()->format('Ymd');
        $count = Booking::count() + 1;
        $kode_booking = 'BK-' . $tanggal . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Cek apakah admin yang akses
        if (Auth::guard('web')->check() && Auth::user()->role == 'admin') {
            $pelanggan = Pelanggan::all();
            return view('admin.proses.booking.tambah', compact('pelanggan', 'kode_booking'));
        }
        // Kalau pelanggan yang buka
        return view('pelanggan.proses.booking.tambah', compact('kode_booking'));
    }

    // 🔹 SIMPAN DATA
    public function store(Request $request)
    {
        $rules = [
            'tgl_booking' => 'required|date',
            'merk_tipe' => 'required|string',
            'spesifikasi' => 'required|string',
            'keluhan' => 'required|string',
            'metode_pengambilan' => 'required|in:diantar,ambil sendiri',
            'kategori_servis' => 'required|in:ringan,sedang,berat',
        ];

        // ✅ PERBAIKAN: Pastikan Auth::user() tidak null sebelum mengecek role
        $isAdmin = Auth::guard('web')->check() && Auth::user() && Auth::user()->role == 'admin';

        if ($isAdmin) {
            $rules['id_pelanggan'] = 'required';
            $rules['status_deposit'] = 'required|in:belum lunas,sudah lunas';
            $rules['status_booking'] = 'required|in:pending,diterima,ditolak';
        }

        $request->validate($rules);

        // Set Variabel Berdasarkan Role
        if ($isAdmin) {
            $id_pelanggan = $request->id_pelanggan;
            $status_deposit = $request->status_deposit;
            $status_booking = $request->status_booking;
        } else {
            $id_pelanggan = Auth::guard('pelanggan')->id(); 
            $status_deposit = 'belum lunas'; 
            $status_booking = 'pending';
        }

        // Generate ulang kode booking
        $tanggal = Carbon::now()->format('Ymd');
        $count = Booking::count() + 1;
        $kode_booking = 'BK-' . $tanggal . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Simpan ke Database
        $booking = Booking::create([
            'id_pelanggan'       => $id_pelanggan,
            'kode_booking'       => $kode_booking,
            'tgl_booking'        => $request->tgl_booking,
            'merk_tipe'          => $request->merk_tipe,
            'spesifikasi'        => $request->spesifikasi,
            'keluhan'            => $request->keluhan,
            'metode_pengambilan' => $request->metode_pengambilan,
            'kelengkapan'        => $request->kelengkapan ?? '-',
            'kategori_servis'    => $request->kategori_servis,
            'status_deposit'     => $status_deposit,
            'status_booking'     => $status_booking,
        ]);

        Pembayaran::create([
            'id_booking'        => $booking->id_booking,
            'id_servis'         => null,
            'jenis_pembayaran'  => 'deposit',
            'metode_pembayaran' => 'midtrans',
            'nominal'           => 50000,
            'status_pembayaran' => 'pending'
        ]);

        if ($isAdmin) {
            return redirect()->route('admin.booking.index')->with('success', 'Booking berhasil ditambahkan');
        }

        return redirect()->route('pelanggan.booking.index')->with('success', 'Booking berhasil dibuat!');
    }

    // 🔹 FORM EDIT
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $isAdmin = Auth::guard('web')->check() && Auth::user()->role == 'admin';

        if (!$isAdmin) {
            $id_pelanggan = Auth::guard('pelanggan')->id();
            if ($booking->id_pelanggan != $id_pelanggan || $booking->status_booking != 'pending') {
                abort(403, 'Aksi tidak diizinkan.');
            }
            return view('pelanggan.proses.booking.edit', compact('booking'));
        }

        $pelanggan = Pelanggan::all();
        return view('admin.proses.booking.edit', compact('booking', 'pelanggan'));
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $isAdmin = Auth::guard('web')->check() && Auth::user()->role == 'admin';

        if ($isAdmin) {
            $booking->update([
                'id_pelanggan' => $request->id_pelanggan,
                'tgl_booking' => $request->tgl_booking,
                'merk_tipe' => $request->merk_tipe,
                'spesifikasi' => $request->spesifikasi,
                'keluhan' => $request->keluhan,
                'metode_pengambilan' => $request->metode_pengambilan,
                'kelengkapan' => $request->kelengkapan,
                'kategori_servis' => $request->kategori_servis,
                'status_deposit' => $request->status_deposit,
                'status_booking' => $request->status_booking,
            ]);

            if ($request->status_booking == 'diterima' &&
                $booking->status_deposit == 'sudah lunas') {
                $cekServis = Servis::where('id_booking', $booking->id_booking)->first();
                if (!$cekServis) {
                    $kode_servis = 'SRV-' . date('Ymd') . '-' . str_pad(Servis::count() + 1, 3, '0', STR_PAD_LEFT);
                    $newServis = Servis::create([
                    'id_booking' => $booking->id_booking,
                    'kode_servis' => $kode_servis,
                    'tgl_masuk' => Carbon::now(),
                    'perkiraan_selesai' => Carbon::now()->addDays(3),
                    'status_servis' => 'menunggu',
                    'total_biaya' => 0
                ]);

                HistoriAktivitas::create([
                    'id_user'    => Auth::id(), 
                    'id_servis'  => $newServis->id_servis,
                    'aktivitas'  => 'Penerimaan Unit',
                    'keterangan' => 'Booking diterima oleh Admin. Unit masuk antrean dengan status Menunggu.',
                    'tanggal'    => Carbon::now()
                ]);
                }
            }
            return redirect()->route('admin.booking.index')->with('success', 'Booking berhasil diupdate');
        }

        // Jika Pelanggan yang update
        $id_pelanggan = Auth::guard('pelanggan')->id();
        if ($booking->id_pelanggan != $id_pelanggan || $booking->status_booking != 'pending') {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $booking->update([
            'tgl_booking' => $request->tgl_booking,
            'merk_tipe' => $request->merk_tipe,
            'spesifikasi' => $request->spesifikasi,
            'keluhan' => $request->keluhan,
            'metode_pengambilan' => $request->metode_pengambilan,
            'kelengkapan' => $request->kelengkapan,
            'kategori_servis' => $request->kategori_servis,
        ]);

        return redirect()->route('pelanggan.booking.index')->with('success', 'Booking berhasil diperbarui');
    }

    // 🔹 DETAIL
    public function show($id)
    {
        $booking = Booking::with('pelanggan')->findOrFail($id);
        $isAdmin = Auth::guard('web')->check() && Auth::user()->role == 'admin';

        if (!$isAdmin) {
            $id_pelanggan = Auth::guard('pelanggan')->id();
            if ($booking->id_pelanggan != $id_pelanggan) {
                abort(403);
            }
            return view('pelanggan.proses.booking.detail', compact('booking'));
        }

        return view('admin.proses.booking.detail', compact('booking'));
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $isAdmin = Auth::guard('web')->check() && Auth::user()->role == 'admin';

        if (!$isAdmin) {
            $id_pelanggan = Auth::guard('pelanggan')->id();
            if ($booking->id_pelanggan != $id_pelanggan || $booking->status_booking != 'pending') {
                abort(403);
            }
            $booking->delete();
            return redirect()->route('pelanggan.booking.index')->with('success', 'Booking berhasil dibatalkan');
        }

        $booking->delete();
        return redirect()->route('admin.booking.index')->with('success', 'Booking berhasil dihapus');
    }
}