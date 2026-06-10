@extends('layouts.layout')

@section('title', 'Laporan Keuangan')

@section('content')
    {{-- TITLE SECTION --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h2>
            <p class="text-gray-500 mt-1">Laporan detail tentang arus kas masuk, pengeluaran, dan pendapatan.</p>
        </div>
    </div>

{{-- STATS CARDS --}}
@php
$stats = [
    ['title' => 'Total Pendapatan', 'total' => 'Rp ' . number_format($total_pendapatan, 0, ',', '.'), 'icon' => 'fa-money-bill-wave', 'color' => 'green', 'label' => 'Total Bersih'],
    ['title' => 'Total Deposit Masuk', 'total' => 'Rp ' . number_format($total_deposit, 0, ',', '.'), 'icon' => 'fa-wallet', 'color' => 'purple', 'label' => 'Uang Muka/DP'],
    ['title' => 'Total Pelunasan', 'total' => 'Rp ' . number_format($total_pelunasan, 0, ',', '.'), 'icon' => 'fa-hand-holding-usd', 'color' => 'blue', 'label' => 'Sisa Pembayaran'],
    ['title' => 'Pembayaran Pending', 'total' => $pembayaran_pending . ' Transaksi', 'icon' => 'fa-hourglass-start', 'color' => 'amber', 'label' => 'Belum Bayar'],
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

    {{-- DETAIL TRANSAKSI PEMASUKAN --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-8">
        <div class="bg-orange-500 px-6 py-3">
            <h3 class="text-white text-md font-bold uppercase tracking-wider">
                Detail Transaksi Pemasukan
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-gray-200">
                    <tr class="text-gray-800 uppercase text-xs font-bold bg-gray-50">
                        <th class="px-6 py-3 text-center">Tanggal</th>
                        <th class="px-6 py-3 text-center">Kode Servis</th>
                        <th class="px-6 py-3 text-center">Nama Pelanggan</th>
                        <th class="px-6 py-3 text-center">Jenis Pembayaran</th>
                        <th class="px-6 py-3 text-center">Nominal</th>
                        <th class="px-6 py-3 text-center">Metode Pembayaran</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($detail_pemasukan as $dp)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center">{{ $dp->tanggal }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-900">{{ $dp->kode_servis }}</td>
                            <td class="px-6 py-4 text-center">{{ $dp->nama_pelanggan }}</td>
                            <td class="px-6 py-4 text-center uppercase font-medium">{{ $dp->jenis_pembayaran }}</td>
                            <td class="px-6 py-4 text-center font-bold">Rp {{ number_format($dp->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center uppercase">
                                <span class="px-2 py-0.5 rounded text-xs {{ $dp->metode_pembayaran == 'cash' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $dp->metode_pembayaran }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $dp->status == 'sukses' || $dp->status == 'Lunas' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $dp->status == 'pending' || $dp->status == 'Pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $dp->status == 'gagal' || $dp->status == 'Gagal' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $dp->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 italic">Tidak ada data transaksi pemasukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DETAIL PENGELUARAN --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-8">
        <div class="bg-orange-500 px-6 py-3">
            <h3 class="text-white text-md font-bold uppercase tracking-wider">
                Detail Pengeluaran
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-gray-200">
                    <tr class="text-gray-800 uppercase text-xs font-bold bg-gray-50">
                        <th class="px-6 py-3 text-center">Tanggal</th>
                        <th class="px-6 py-3 text-center">Nama Sparepart</th>
                        <th class="px-6 py-3 text-center">Jumlah</th>
                        <th class="px-6 py-3 text-center">Harga Satuan</th>
                        <th class="px-6 py-3 text-center">Total</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($detail_pengeluaran as $dk)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center">{{ $dk->tanggal }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-900">{{ $dk->nama_sparepart }}</td>
                            <td class="px-6 py-4 text-center">{{ $dk->jumlah }}</td>
                            <td class="px-6 py-4 text-center">Rp {{ number_format($dk->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center font-bold text-red-600">Rp {{ number_format($dk->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 italic">Tidak ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRAFIK JUMLAH PENDAPATAN --}}
    <div class="mt-6">
        <h3 class="text-sm font-bold text-gray-800 uppercase mb-3 tracking-wide">
            Grafik Jumlah Pendapatan
        </h3>
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="h-80 w-full">
                <canvas id="pendapatanChart" data-chart="{{ json_encode($data_chart_pendapatan, JSON_NUMERIC_CHECK) }}"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const canvasElement = document.getElementById('pendapatanChart');
    if(!canvasElement) return;

    const dataPendapatan = JSON.parse(canvasElement.getAttribute('data-chart'));
    const ctx = canvasElement.getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dataPendapatan,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#10b981',
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
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection