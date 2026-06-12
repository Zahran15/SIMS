<?php

namespace App\Http\Controllers;

use App\Models\User;

class MonitoringTeknisiController extends Controller
{
    public function index()
    {
        $monitoring = User::where('role', 'teknisi')->withCount([
                // TOTAL SERVIS
                'penugasan as total_servis_ditangani',

                // SERVIS SELESAI
                'penugasan as servis_selesai' => function ($query) {
                    $query->where('status_penugasan', 'selesai');
                },

                // SERVIS PENDING
                'penugasan as servis_pending' => function ($query) {
                    $query->whereIn('status_penugasan', [
                        'belum dikerjakan',
                        'sedang dikerjakan',
                        'menunggu sparepart'
                    ]);
                },

                // SERVIS DIBATALKAN / GAGAL
                'penugasan as servis_dibatalkan' => function ($query) {
                    $query->where('status_penugasan', 'gagal');
                }
            ])
            ->latest()
            ->get();

        // --- AMBIL DATA UNTUK GRAFIK ---
        $nama_teknisi = $monitoring->pluck('nama')->toArray();
        $data_selesai = $monitoring->pluck('servis_selesai')->toArray();
        $data_pending = $monitoring->pluck('servis_pending')->toArray();
        return view('owner.monitoring.index', compact('monitoring', 'nama_teknisi', 'data_selesai', 'data_pending'));
    }
}