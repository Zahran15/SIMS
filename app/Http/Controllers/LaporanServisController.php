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
        $servis_selesai = Servis::where('status_servis', 'Selesai')->count();
        $servis_proses = Servis::where('status_servis', 'Proses')->count();
        $servis_dibatalkan = Servis::where('status_servis', 'Dibatalkan')->count();

        // 2. MENGAMBIL DETAIL DATA SERVIS (Eager Loading)
        $detail_servis = Servis::with(['penugasan.user', 'booking.pelanggan'])
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        // 3. REKAP PER TEKNISI (Menggunakan Eloquent secara bersih)
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
        return view('owner.laporan.laporan_servis.index', compact(
            'total_servis',
            'servis_selesai',
            'servis_proses',
            'servis_dibatalkan',
            'detail_servis',
            'rekap_teknisi',
            'data_chart' 
        ));
    }
}