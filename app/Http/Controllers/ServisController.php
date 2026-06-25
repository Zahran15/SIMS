<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servis;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\JasaServis;
use App\Models\Sparepart;
use App\Models\DetailServisJasa;
use App\Models\DetailServisSparepart;
use App\Models\Pembayaran;
use App\Models\HistoriAktivitas;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotifServisSelesai;
use Illuminate\Support\Facades\Notification;

class ServisController extends Controller
{
    public function prosesindex(Request $request)
    {
        $query = Servis::with(['booking.pelanggan', 'penugasan']);
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('booking.pelanggan', function($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%');
            });
        }
        if ($request->has('status_servis') && $request->status_servis != '') {
            $query->where('status_servis', $request->status_servis);
        }
        $servis = $query->with('booking.pelanggan', 'penugasan')->whereIn('status_servis', ['menunggu','proses'])->latest()->paginate(10);
        return view('admin.proses.servis_proses.index', compact('servis'));
    }

    public function selesaiindex(Request $request)
    {
    // Eager loading relasi utama
    $query = Servis::with(['booking.pelanggan']);
    if ($request->has('search') && $request->search != '') {
        $query->whereHas('booking.pelanggan', function($q) use ($request) {
            $q->where('nama', 'LIKE', '%' . $request->search . '%');
        });
    }
    if ($request->has('status_servis') && $request->status_servis != '') {
        $query->where('status_servis', $request->status_servis);
    }
        $servis = $query->with('booking.pelanggan')->whereIn('status_servis', ['selesai','bisa diambil','sudah diambil'])->latest()->paginate(10);
        return view('admin.proses.servis_selesai.index', compact('servis'));
    }

    // 🔹 AUTO CREATE DARI BOOKING
    public function createFromBooking($id_booking)
    {
        $booking = Booking::with('servis.penugasan')->findOrFail($id_booking);
        if ($booking->servis) {
            return back()->with('error', 'Servis sudah dibuat');
        }
        $kode = 'SRV-' . date('Ymd') . '-' . rand(100,999);
        $estimasiSelesai = $booking->servis->penugasan->estimasi_selesai ?? Carbon::now()->addDays(3); 
        $servis = Servis::create([
            'id_booking' => $booking->id_booking,
            'kode_servis' => $kode,
            'tgl_masuk' => Carbon::now(),
            'perkiraan_selesai' => $estimasiSelesai,
            'status_servis' => 'menunggu',
            'status_pelunasan' => 'belum lunas',
            'total_biaya' => 0
        ]);
        HistoriAktivitas::create([
            'id_user'    => Auth::id(),
            'id_servis'  => $servis->id_servis,
            'aktivitas'  => 'Penerimaan Unit',
            'keterangan' => 'Servis baru berhasil dibuat melalui aksi cepat data booking.',
            'tanggal'    => Carbon::now()
        ]);
        return back()->with('success', 'Servis berhasil dibuat');
    }

     // EDIT SERVIS PROSES
    public function edit($id)
    {
        $servis = Servis::with(['booking.pelanggan','detailJasa.jasa','detailSparepart.sparepart'])->findOrFail($id);
        $jasa = JasaServis::all();
        $sparepart = Sparepart::all();
        return view('admin.proses.servis_proses.edit', compact('servis','jasa','sparepart'));
    }

    public function ajaxTambahJasa(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);
        $jasa = JasaServis::findOrFail($request->id_jasa);
        DetailServisJasa::create([
            'id_servis' => $servis->id_servis,
            'id_jasa' => $jasa->id_jasa,
            'harga' => $jasa->harga,
            'subtotal' => $jasa->harga
        ]);
        // TOTAL
        $totalJasa = DetailServisJasa::where('id_servis',$servis->id_servis)->sum('subtotal');
        $totalSparepart = DetailServisSparepart::where('id_servis',$servis->id_servis)->sum('subtotal');
        $total = $totalJasa + $totalSparepart;
        $servis->update(['total_biaya' => $total]);
        return response()->json(['success' => true,'total' => number_format($total, 0, ',', '.')]);
    }

    public function ajaxTambahSparepart(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);
        $sparepart = Sparepart::findOrFail($request->id_sparepart);
        $subtotal = $sparepart->harga_jual * $request->qty;
        DetailServisSparepart::create([
            'id_servis' => $servis->id_servis,
            'id_sparepart' => $sparepart->id_sparepart,
            'qty' => $request->qty,
            'harga' => $sparepart->harga_jual,
            'subtotal' => $subtotal
        ]);
        // TOTAL
        $totalJasa = DetailServisJasa::where('id_servis',$servis->id_servis)->sum('subtotal');
        $totalSparepart = DetailServisSparepart::where('id_servis',$servis->id_servis)->sum('subtotal');
        $total = $totalJasa + $totalSparepart;
        $servis->update(['total_biaya' => $total]);
        return response()->json(['success' => true,'total' => number_format($total, 0, ',', '.')]);
    }
    // DETAIL SERVIS PROSES
    public function detailProses($id)
    {
        $servis = Servis::with([
            'booking.pelanggan',
            'penugasan.teknisi',
            'detailJasa.jasa',
            'detailSparepart.sparepart'
        ])
        ->whereIn('status_servis', ['menunggu', 'proses'])
        ->findOrFail($id);

        return view('admin.proses.servis_proses.detail', compact('servis'));
    }

    // DETAIL SERVIS SELESAI
    public function detailSelesai($id)
    {
        $servis = Servis::with([
            'booking.pelanggan',
            'penugasan.teknisi',
            'detailJasa.jasa',
            'detailSparepart.sparepart'
        ])->whereIn('status_servis', ['selesai', 'bisa diambil', 'sudah diambil'])->findOrFail($id);
        return view('admin.proses.servis_selesai.detail', compact('servis'));
    }

    // UPDATE SERVIS PROSES
    public function update(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);
        $statusLama = $servis->status_servis;
        $tanggalSelesaiFix = $request->perkiraan_selesai 
            ? \Carbon\Carbon::parse($request->perkiraan_selesai)->format('Y-m-d') 
            : $servis->perkiraan_selesai;
        // UPDATE SERVIS
        $servis->update([
            'perkiraan_selesai' => $tanggalSelesaiFix, 
            'status_servis' => $request->status_servis,
            'status_pelunasan' => $request->status_pelunasan,
        ]);
        // HAPUS DETAIL LAMA
        DetailServisJasa::where('id_servis', $servis->id_servis)->delete();
        DetailServisSparepart::where('id_servis', $servis->id_servis)->delete();
        $totalJasa = 0;
        $totalSparepart = 0;
        // SIMPAN JASA
        if($request->jasa){
            foreach($request->jasa as $id_jasa){
                if($id_jasa){
                    $jasa = JasaServis::find($id_jasa);
                    DetailServisJasa::create([
                        'id_servis' => $servis->id_servis,
                        'id_jasa' => $jasa->id_jasa,
                        'harga' => $jasa->harga,
                        'subtotal' => $jasa->harga
                    ]);
                    $totalJasa += $jasa->harga;
                }
            }
        }

        // SIMPAN SPAREPART
        if($request->sparepart){
            foreach($request->sparepart as $key => $id_sparepart){
                if($id_sparepart){
                    $sparepart = Sparepart::find($id_sparepart);
                    $qty = $request->qty[$key];
                    $subtotal =$sparepart->harga_jual * $qty;
                    DetailServisSparepart::create([
                        'id_servis' => $servis->id_servis,
                        'id_sparepart' => $sparepart->id_sparepart,
                        'qty' => $qty,
                        'harga' => $sparepart->harga_jual,
                        'subtotal' => $subtotal
                    ]);
                    $totalSparepart += $subtotal;
                }
            }
        }

        // UPDATE TOTAL
        $servis->update([
            'total_biaya' => $totalJasa + $totalSparepart
        ]);
        $namaAktivitas = 'Admin: Update Servis';
        if ($statusLama != $request->status_servis) {
        $namaAktivitas = 'Status: ' . ucwords($request->status_servis);
    }

    HistoriAktivitas::create([
        'id_user'    => Auth::id(),
        'id_servis'  => $servis->id_servis,
        'aktivitas'  => $namaAktivitas,
        'keterangan' => 'Data servis diperbarui. Status saat ini: ' . ucwords($request->status_servis) . '. Total biaya diperkirakan Rp ' . number_format($servis->total_biaya, 0, ',', '.'),
        'tanggal'    => Carbon::now()
    ]);

    if ($statusLama != $request->status_servis && in_array($request->status_servis, ['selesai', 'bisa diambil'])) {
            if ($servis->booking && $servis->booking->pelanggan) {
                $emailPelanggan = $servis->booking->pelanggan->email;
                $namaPelanggan  = $servis->booking->pelanggan->nama;
                $namaPerangkat  = $servis->booking->nama_laptop ?? 'Perangkat Laptop';
                try {
                    Notification::route('mail', $emailPelanggan)->notify(new NotifServisSelesai($namaPelanggan, $namaPerangkat));
                } catch (\Exception $e) {
                }
            }
        }

        if (in_array($servis->status_servis, ['selesai', 'bisa diambil', 'sudah diambil'])) {
            if ($statusLama != 'selesai' && $request->status_servis == 'selesai') {
                $cekPelunasan = Pembayaran::where('id_servis', $servis->id_servis)->where('jenis_pembayaran', 'pelunasan')->first();
                if (!$cekPelunasan) {
                    // 1. Hitung total DP yang SUKSES dibayar untuk booking ini
                    $totalDP = Pembayaran::where('id_booking', $servis->id_booking)
                        ->where('jenis_pembayaran', 'dp')
                        ->where('status_pembayaran', 'sukses')
                        ->sum('nominal');
        
                    // 2. Nominal pelunasan adalah total biaya dikurangi DP
                    $nominalPelunasan = $servis->total_biaya - $totalDP;
        
                    // 3. Pastikan nominal tidak minus (jika DP lebih besar dari biaya servis karena suatu hal)
                    if ($nominalPelunasan < 0) {
                        $nominalPelunasan = 0;
                    }
                    Pembayaran::create([
                        'id_booking'        => $servis->id_booking,
                        'id_servis'         => $servis->id_servis,
                        'jenis_pembayaran'  => 'pelunasan',
                        'metode_pembayaran' => 'transfer', 
                        'nominal'           => $nominalPelunasan,
                        'status_pembayaran' => 'pending'
                    ]);
                }
            }
            return redirect()->route('admin.servis_selesai.index')->with('success', 'Servis berhasil diselesaikan');
        }
            return redirect()->route('admin.servis_proses.index')->with('success', 'Servis berhasil diupdate'
        );
    }

    public function editSelesai($id)
    {
        $servis = Servis::with(['booking.pelanggan'])->where('id_servis', $id)->firstOrFail();
        return view('admin.proses.servis_selesai.edit', compact('servis'));
    }

    public function updateSelesai(Request $request, $id)
    {
        $request->validate(['status_servis' => 'required|in:selesai,bisa diambil,sudah diambil',]);
        $servis = Servis::where('id_servis', $id)->firstOrFail();
        $servis->update(['status_servis' => $request->status_servis]);
        return redirect()->route('admin.servis_selesai.index')->with('success', 'Status servis ' . $servis->kode_servis . ' berhasil diperbarui!');
    }

    // NOTA SERVIS
    public function nota($id)
    {
        $servis = Servis::with(['booking.pelanggan','penugasan.teknisi','detailJasa.jasa','detailSparepart.sparepart'])->findOrFail($id);
        return view('admin.proses.servis_proses.nota', compact('servis'));
    }

    // TANDA TERIMA SERVIS
    public function tandaTerima($id)
    {
        $servis = Servis::with([
            'booking.pelanggan',
            'penugasan.teknisi',
            'detailJasa.jasa',
            'detailSparepart.sparepart'
        ])->findOrFail($id);
        return view('admin.proses.servis_selesai.tanda_terima', compact('servis')
        );
    }

    // 🔹 TAMBAHAN HELPER STATIC METHOD UNTUK UPDATE TOTAL BIAYA DARI LUAR CONTROLLER
    public static function updateTotalBiaya($id_servis)
    {
        $servis = Servis::findOrFail($id_servis);
        $totalJasa = DetailServisJasa::where('id_servis', $id_servis)->sum('subtotal');
        $totalSparepart = DetailServisSparepart::where('id_servis', $id_servis)->sum('subtotal');
        $total = $totalJasa + $totalSparepart;
        $servis->update(['total_biaya' => $total]);
        return $total;
    }
}