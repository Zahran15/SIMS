<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\PengadaanSparepart;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        // 1. STATS CARDS (PEMASUKAN & PENDING)
        $total_pendapatan = Pembayaran::where('status_pembayaran', 'sukses')->sum('nominal');
        $total_deposit = Pembayaran::where('status_pembayaran', 'sukses')->where('jenis_pembayaran', 'deposit')->sum('nominal');
        $total_pelunasan = Pembayaran::where('status_pembayaran', 'sukses')->where('jenis_pembayaran', 'pelunasan')->sum('nominal');
        $pembayaran_pending = Pembayaran::where('status_pembayaran', 'pending')->count();
        
        // 2. DETAIL TRANSAKSI PEMASUKAN
        $pembayaran_raw = Pembayaran::with(['booking.pelanggan', 'servis'])->latest()->get();
        $detail_pemasukan = $pembayaran_raw->map(function ($item) {
            return (object) [
                'tanggal' => $item->tanggal_bayar ? $item->tanggal_bayar->format('Y-m-d') : $item->created_at->format('Y-m-d'),
                'kode_servis' => $item->servis->kode_servis ?? $item->booking->kode_booking ?? '-',
                'nama_pelanggan' => $item->booking->pelanggan->nama ?? '-',
                'jenis_pembayaran' => $item->jenis_pembayaran,
                'nominal' => $item->nominal,
                'metode_pembayaran' => $item->metode_pembayaran,
                'status' => $item->status_pembayaran
            ];
        });

        // 3. DETAIL TRANSAKSI PENGELUARAN (PENGADAAN SPAREPART)
        $pengadaan_raw = PengadaanSparepart::with('sparepart')->where('status_pengadaan', 'diterima')->latest()->get();
        $detail_pengeluaran = $pengadaan_raw->map(function ($item) {
            return (object) [
                'tanggal' => $item->tgl_pesan,
                'nama_sparepart' => $item->sparepart->nama_sparepart ?? '-',
                'jumlah' => $item->jumlah,
                'harga_satuan' => $item->harga_beli,
                'total' => $item->total
            ];
        });


        // 4. DATA GRAFIK LINE CHART (PENDAPATAN BULANAN TAHUN INI)
        $tahun_ini = date('Y');
        $pendapatan_per_bulan = Pembayaran::where('status_pembayaran', 'sukses')
            ->where(function($query) use ($tahun_ini) {
                $query->whereYear('tanggal_bayar', $tahun_ini)
                      ->orWhere(function($q) use ($tahun_ini) {
                          $q->whereNull('tanggal_bayar')
                            ->whereYear('created_at', $tahun_ini);
                      });
            })
            ->select(
                DB::raw('MONTH(COALESCE(tanggal_bayar, created_at)) as bulan'),
                DB::raw('SUM(nominal) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
                ->toArray();
        $data_chart_pendapatan = [];
        for ($i = 1; $i <= 12; $i++) {
            // Paksa konversi ke tipe float/int agar terbaca sebagai angka di Chart.js
            $data_chart_pendapatan[] = isset($pendapatan_per_bulan[$i]) ? (float)$pendapatan_per_bulan[$i] : 0;
        }

        // 5. KIRIM DATA KE VIEW
        return view('owner.laporan.laporan_keuangan.index', compact(
            'total_pendapatan',
            'total_deposit',
            'total_pelunasan',
            'pembayaran_pending',
            'detail_pemasukan',
            'detail_pengeluaran',
            'data_chart_pendapatan'
        ));
    }
}