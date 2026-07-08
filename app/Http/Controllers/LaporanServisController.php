<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LaporanServisController extends Controller
{
    public function index()
    {
        // 1. MENGAMBIL DATA UNTUK STATS CARDS
        $total_servis = Servis::count();
        $servis_selesai = Servis::whereIn('status_servis', ['selesai', 'bisa diambil', 'sudah diambil'])->count();
        $servis_proses = Servis::whereIn('status_servis', ['proses', 'menunggu'])->count();
        $servis_dibatalkan = Servis::where('status_servis', 'dibatalkan')->count();

        // 2. MENGAMBIL DETAIL DATA SERVIS (Ubah ->get() menjadi ->paginate(5))
        $detail_servis_paginated = Servis::with(['penugasan.user', 'booking.pelanggan'])->orderBy('tgl_masuk', 'desc')->paginate(5, ['*'], 'page_servis');
        // Melakukan mapping data didalam collection paginator sekaligus memformat tanggal selesai
        $mapped_servis = $detail_servis_paginated->getCollection()->map(function ($item) {            
            $tanggal_selesai_raw = $item->penugasan->estimasi_selesai ?? $item->perkiraan_selesai;
            return (object) [
                'tgl_masuk' => $item->tgl_masuk,
                'kode_servis' => $item->kode_servis,
                'nama_pelanggan' => $item->booking->pelanggan->nama ?? $item->booking->pelanggan->nama_pelanggan,
                'keluhan' => $item->booking->keluhan,
                'teknisi' => $item->penugasan->user->nama ?? 'Belum Ditugaskan',
                'perkiraan_selesai' => $tanggal_selesai_raw ? \Carbon\Carbon::parse($tanggal_selesai_raw)->format('d M Y') : '-',                
                'total_biaya' => $item->total_biaya,
                'status_servis' => $item->status_servis
            ];
        });
        $detail_servis_paginated->setCollection($mapped_servis);

        // 3. REKAP PER TEKNISI
        $rekap_teknisi = User::where('role', 'teknisi')
            ->leftJoin('penugasan_teknisi', 'users.id_user', '=', 'penugasan_teknisi.id_user')
            ->leftJoin('servis as s', 'penugasan_teknisi.id_servis', '=', 's.id_servis')
            ->select('users.id_user','users.nama',
                DB::raw('COUNT(s.id_servis) as total_servis_ditangani'),
                DB::raw("SUM(CASE WHEN s.status_servis = 'Selesai' THEN 1 ELSE 0 END) as servis_selesai"),
                DB::raw("SUM(CASE WHEN s.status_servis = 'Proses' THEN 1 ELSE 0 END) as servis_proses")
            )
            ->groupBy('users.id_user', 'users.nama')
            ->get();

        // 4. AMBIL DATA UNTUK GRAFIK
        $tahun_ini = date('Y');
        $grafik_data = Servis::select(
                DB::raw('MONTH(tgl_masuk) as bulan'),
                DB::raw('COUNT(id_servis) as jumlah')
            )
            ->whereYear('tgl_masuk', $tahun_ini)
            ->groupBy(DB::raw('MONTH(tgl_masuk)'))
            ->orderBy('bulan', 'asc')
            ->get();

        $bulanan = array_fill(1, 12, 0);
        foreach ($grafik_data as $data) {
            $bulanan[$data->bulan] = $data->jumlah;
        }

        $data_chart = array_values($bulanan);

        // 5. KIRIM DATA KE VIEW (Kirim variabel $detail_servis_paginated)
        return view('owner.laporan.laporan_servis.index', compact(
            'total_servis',
            'servis_selesai',
            'servis_proses',
            'servis_dibatalkan',
            'detail_servis_paginated',
            'rekap_teknisi',
            'data_chart' 
        ));
    }
}