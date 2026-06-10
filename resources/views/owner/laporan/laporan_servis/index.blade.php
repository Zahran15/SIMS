@extends('layouts.layout')

@section('title', 'Laporan Servis')

@section('content')
    {{-- TITLE SECTION --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Servis</h2>
            <p class="text-gray-500 mt-1">Laporan detail tentang aktivitas servis dan rekap teknisi.</p>
        </div>
    </div>

{{-- STATS CARDS --}}
@php
$stats = [
    ['title' => 'Total Servis', 'total' => $total_servis . ' Transaksi', 'icon' => 'fa-laptop-medical', 'color' => 'orange', 'label' => 'Semua Riwayat'],
    ['title' => 'Servis Selesai', 'total' => $servis_selesai . ' Selesai', 'icon' => 'fa-check-circle', 'color' => 'emerald', 'label' => 'Sudah Selesai'],
    ['title' => 'Servis Proses', 'total' => $servis_proses . ' Proses', 'icon' => 'fa-hourglass-half', 'color' => 'amber', 'label' => 'Sedang Proses'],
    ['title' => 'Servis Dibatalkan', 'total' => $servis_dibatalkan . ' Batal', 'icon' => 'fa-times-circle', 'color' => 'red', 'label' => 'Gagal / Batal'],
];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    @foreach($stats as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all group duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">
                    {{ $stat['title'] }}
                </p>
                <h3 class="text-xl font-black text-gray-800 group-hover:text-{{ $stat['color'] }}-600 transition-colors">
                    {{ $stat['total'] }}
                </h3>
                <span class="text-[9px] font-bold text-{{ $stat['color'] }}-600 bg-{{ $stat['color'] }}-50 px-2 py-0.5 rounded uppercase mt-2 block w-max tracking-wide">
                    {{ $stat['label'] }}
                </span>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center group-hover:bg-{{ $stat['color'] }}-50 transition-colors duration-300">
                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-xl group-hover:scale-110 transition-transform"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

    {{-- DETAIL DATA SERVIS --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-8">
        <div class="bg-orange-500 px-6 py-3">
            <h3 class="text-white text-md font-bold uppercase tracking-wider">
                Detail Data Servis
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-gray-200">
                    <tr class="text-gray-800 uppercase text-xs font-bold bg-gray-50">
                        <th class="px-6 py-3 text-center">Tgl. Masuk</th>
                        <th class="px-6 py-3 text-center">Kode Servis</th>
                        <th class="px-6 py-3 text-center">Nama Pelanggan</th>
                        <th class="px-6 py-3 text-center">Keluhan</th>
                        <th class="px-6 py-3 text-center">Teknisi</th>
                        <th class="px-6 py-3 text-center">Tgl. Selesai</th>
                        <th class="px-6 py-3 text-center">Total Biaya</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($detail_servis as $ds)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center">{{ $ds->tgl_masuk }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-900">{{ $ds->kode_servis }}</td>
                            <td class="px-6 py-4 text-center">{{ $ds->booking->pelanggan->nama ?? $ds->booking->pelanggan->nama_pelanggan ?? '-' }}</td>                            
                            <td class="px-6 py-4 text-center max-w-xs truncate">{{ $ds->booking->keluhan ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ $ds->penugasan->user->nama ?? 'Belum Ditugaskan' }}</td>
                            <td class="px-6 py-4 text-center">{{ $ds->perkiraan_selesai ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-800">Rp {{ number_format($ds->total_biaya, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $ds->status_servis == 'Selesai' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $ds->status_servis == 'Proses' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $ds->status_servis == 'Dibatalkan' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $ds->status_servis }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500 italic">Tidak ada data servis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- REKAP PER TEKNISI --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-8">
        <div class="bg-orange-500 px-6 py-3">
            <h3 class="text-white text-md font-bold uppercase tracking-wider">
                Rekap Per Teknisi
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-gray-200">
                    <tr class="text-gray-800 uppercase text-xs font-bold bg-gray-50">
                        <th class="px-6 py-3 text-center">Nama Teknisi</th>
                        <th class="px-6 py-3 text-center">Jumlah Servis</th>
                        <th class="px-6 py-3 text-center">Jumlah Selesai</th>
                        <th class="px-6 py-3 text-center">Jumlah Proses</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($rekap_teknisi as $rt)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center font-semibold text-gray-900">{{ $rt->nama }}</td>
                            <td class="px-6 py-4 text-center font-medium">{{ $rt->total_servis_ditangani }}</td>
                            <td class="px-6 py-4 text-center text-green-600 font-bold">{{ $rt->servis_selesai ?? 0 }}</td>
                            <td class="px-6 py-4 text-center text-yellow-600 font-bold">{{ $rt->servis_proses ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500 italic">Tidak ada data rekap teknisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRAFIK JUMLAH SERVIS --}}
    <div class="mt-6">
        <h3 class="text-sm font-bold text-gray-800 uppercase mb-3 tracking-wide">
            Grafik Jumlah Servis
        </h3>
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="h-80 w-full">
                <canvas id="servisChart" data-chart="{{ json_encode($data_chart, JSON_NUMERIC_CHECK) }}"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const canvasElement = document.getElementById('servisChart');
    if(!canvasElement) return;

    const dataServis = JSON.parse(canvasElement.getAttribute('data-chart'));
    const ctx = canvasElement.getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Servis',
                data: dataServis,
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#f97316',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) { if (value % 1 === 0) { return value; } }
                    }
                }
            }
        }
    });
});
</script>
@endsection