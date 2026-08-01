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
use App\Notifications\NotifBookingDiterima;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // 🔹 TAMPIL DATA
    public function index(Request $request)
    {
        $query = Booking::query();
        // Filter Kode Booking
        if ($request->filled('kode_booking')) {
            $query->where('kode_booking', 'LIKE', '%' . $request->kode_booking . '%');
        }

        // Filter Nama Pelanggan
        if ($request->filled('nama_pelanggan')) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama', 'LIKE', $request->nama_pelanggan . '%');
            });
        }
        // 2. Filter Berdasarkan Kategori Servis
        if ($request->has('kategori_servis') && $request->kategori_servis != '') {
            $query->where('kategori_servis', $request->kategori_servis);
        }
        // 3. Filter Berdasarkan Status Deposit
        if ($request->has('status_dp') && $request->status_dp != '') {
            $query->where('status_dp', $request->status_dp);
        }
        if ($request->has('status_booking') && $request->status_booking != '') {
            $query->where('status_booking', $request->status_booking);
        }

        if (Auth::guard('web')->check() && Auth::user()->role == 'admin') { 
            $booking = $query->with('pelanggan')->latest()->paginate(10);
            return view('admin.proses.booking.index', compact('booking'));
        } 
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
            'tgl_booking' => 'required',
            'merk_tipe' => 'required',
            'spesifikasi' => 'required',
            'keluhan' => 'required',
            'metode_pengembalian' => 'required',
            'kategori_servis' => 'required',
        ];
        $isAdmin = Auth::guard('web')->check() && Auth::user() && Auth::user()->role == 'admin';
        if ($isAdmin) {
            $rules['id_pelanggan']   = 'required';
            $rules['status_dp']      = 'required';
            $rules['status_booking'] = 'required';
        }

        $request->validate($rules);
        // Set Variabel Berdasarkan Role
        if ($isAdmin) {
            $id_pelanggan   = $request->id_pelanggan;
            $status_dp      = $request->status_dp;
            $status_booking = $request->status_booking;
        } else {
            $id_pelanggan   = Auth::guard('pelanggan')->id(); 
            $status_dp      = 'belum lunas'; 
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
            'kelengkapan'        => $request->kelengkapan,
            'kategori_servis'    => $request->kategori_servis,
            'status_dp'          => $status_dp,
            'status_booking'     => $status_booking,
        ]);

        Pembayaran::create([
            'id_booking'        => $booking->id_booking,
            'id_servis'         => null,
            'jenis_pembayaran'  => 'dp',
            'metode_pembayaran' => 'transfer',
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
                'status_dp' => $request->status_dp,
                'status_booking' => $request->status_booking,
            ]);

            if ($request->status_booking == 'diterima' &&
                $booking->status_dp == 'sudah lunas') {
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

    public function terima($id)
    {
        $booking = Booking::with('pelanggan')->findOrFail($id);
        // 1. Update status booking menjadi diterima
        $booking->update(['status_booking' => 'diterima']);
        // 2. Cek apakah data servis untuk booking ini sudah pernah dibuat/belum (biar gak double)
        $cekServis = Servis::where('id_booking', $booking->id_booking)->first();
        if (!$cekServis) {
            $kode_servis = 'SRV-' . date('Ymd') . '-' . str_pad(Servis::count() + 1, 3, '0', STR_PAD_LEFT);
            $newServis = Servis::create([
                'id_booking'        => $booking->id_booking,
                'kode_servis'       => $kode_servis,
                'tgl_masuk'         => Carbon::now(),
                'perkiraan_selesai' => Carbon::now()->addDays(3), 
                'status_servis'     => 'menunggu',                
                'total_biaya'       => 0
            ]);
            // 3. Catat ke Histori Aktivitas
            HistoriAktivitas::create([
                'id_user'    => Auth::id(), 
                'id_servis'  => $newServis->id_servis,
                'aktivitas'  => 'Penerimaan Unit',
                'keterangan' => 'Booking disetujui langsung oleh Admin. Unit masuk antrean servis dan penugasan dibuat.',
                'tanggal'    => Carbon::now()
            ]);
        }

        // 4. PROSES KIRIM NOTIFIKASI EMAIL KEPADA PELANGGAN
        if ($booking->pelanggan) {
            $emailPelanggan = $booking->pelanggan->email;
            $namaPelanggan  = $booking->pelanggan->nama;
            $kodeBooking    = $booking->kode_booking;
            $merkTipe       = $booking->merk_tipe;

            Log::info('Mencoba kirim notif terimaBooking', [
                'email'     => $emailPelanggan,
                'nama'      => $namaPelanggan,
                'booking'   => $kodeBooking,
                'perangkat' => $merkTipe,
            ]);

            try {
                Notification::route('mail', $emailPelanggan)->notify(
                    new NotifBookingDiterima($namaPelanggan, $kodeBooking, $merkTipe)
                );
                Log::info('Notif terimaBooking berhasil dikirim ke: ' . $emailPelanggan);
            } catch (\Throwable $e) {
                Log::error('Gagal kirim notif booking diterima: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.booking.index')->with('success', 'Booking berhasil disetujui dan notif email telah dikirim.');
    }
}