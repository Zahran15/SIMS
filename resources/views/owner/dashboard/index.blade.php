@extends('layouts.layout')

@section('title', 'Owner Dashboard')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
            Owner <span class="text-blue-600">Executive</span>
        </h2>
        <p class="text-gray-500 text-sm">Ringkasan performa finansial dan operasional Seven Komputer.</p>
    </div>
        
    <div class="mt-4 md:mt-0 flex flex-col items-end gap-2">
        <!-- Tanggal -->
        <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-100 text-xs font-bold uppercase tracking-widest block w-full text-center md:w-auto">
            <i class="fas fa-calendar-alt mr-2"></i> {{ date('d M Y') }}
        </span>

        <!-- Jam (Real-time) -->
        <span x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
              x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)"
              class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-100 text-xs font-bold uppercase tracking-widest">
            <i class="fas fa-clock mr-2"></i> <span x-text="time"></span> WIB
        </span>
    </div>
</div>

@php
$stats = [
    ['title' => 'Total Pendapatan', 'total' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'), 'icon' => 'fa-wallet', 'text_color' => 'text-emerald-600', 'bg_color' => 'bg-emerald-50', 'label' => 'Gross Revenue'],
    ['title' => 'Deposit Masuk', 'total' => 'Rp ' . number_format($depositMasuk, 0, ',', '.'), 'icon' => 'fa-hand-holding-usd', 'text_color' => 'text-purple-600', 'bg_color' => 'bg-purple-50', 'label' => 'Uang Muka'],
    ['title' => 'Total Pelunasan', 'total' => 'Rp ' . number_format($totalPelunasan, 0, ',', '.'), 'icon' => 'fa-money-check-alt', 'text_color' => 'text-cyan-600', 'bg_color' => 'bg-cyan-50', 'label' => 'Tagihan Selesai'],
    ['title' => 'Pembayaran Pending', 'total' => $pembayaranPending . ' Transaksi', 'icon' => 'fa-clock', 'text_color' => 'text-blue-600', 'bg_color' => 'bg-blue-50', 'label' => 'Piutang Berjalan'],
];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($stats as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all group">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">
                    {{ $stat['title'] }}
                </p>
                <h3 class="text-2xl font-black text-gray-800 group-hover:{{ $stat['text_color'] }} transition-colors">
                    {{ $stat['total'] }}
                </h3>
                <span class="text-[9px] font-bold {{ $stat['text_color'] }} {{ $stat['bg_color'] }} px-2 py-0.5 rounded uppercase mt-2 block w-max">
                    {{ $stat['label'] }}
                </span>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center group-hover:{{ $stat['bg_color'] }} transition-colors">
                <i class="fas {{ $stat['icon'] }} {{ $stat['text_color'] }} text-xl"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- TREN PENDAPATAN BULANAN --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4 flex justify-between items-center">
                <h5 class="font-bold uppercase text-xs text-white tracking-widest flex items-center">
                    Tren Pendapatan Bulanan
                </h5>
                <span class="text-[10px] text-gray-400 font-bold bg-gray-900 bg-opacity-30 px-2 py-1 rounded">Tahun {{ date('Y') }}</span>
            </div>
            <div class="p-6">
                <div class="h-72 w-full">
                    {{-- Canvas Chart.js --}}
                    <canvas id="ownerPendapatanChart" data-chart="{{ json_encode($data_chart_owner, JSON_NUMERIC_CHECK) }}"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- AKTIVITAS TERBARU (PEMBAYARAN MASUK) --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h5 class="font-bold uppercase text-xs text-gray-600 tracking-widest flex items-center">
                    <i class="fas fa-history mr-2 text-gray-400"></i> Aktivitas Pembayaran Masuk
                </h5>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <div class="p-6 flex-1 overflow-y-auto max-h-[19.5rem]">
                <div class="space-y-4">
                    @forelse($pembayaran_terbaru as $pembayaran)
                        <div class="flex items-start justify-between p-3 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                            <div class="flex items-center space-x-3">
                                {{-- Icon Indikator Jenis Pembayaran --}}
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                                    {{ $pembayaran->jenis_pembayaran == 'deposit' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                    <i class="fas {{ $pembayaran->jenis_pembayaran == 'deposit' ? 'fa-wallet' : 'fa-hand-holding-usd' }} text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-xs font-bold text-gray-800">
                                        {{ $pembayaran->booking->pelanggan->nama ?? 'Pelanggan' }}
                                    </h6>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        {{ $pembayaran->servis->kode_servis ?? $pembayaran->booking->kode_booking ?? '-' }} • 
                                        <span class="uppercase text-[9px] font-bold tracking-wider px-1 bg-gray-100 rounded text-gray-600">
                                            {{ $pembayaran->metode_pembayaran }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-extrabold text-emerald-600 block">
                                    +Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}
                                </span>
                                <span class="text-[9px] text-gray-400">
                                    {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->diffForHumans() : $pembayaran->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center h-full">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-money-bill-wave text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Belum ada transaksi masuk</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT GRAPH ENGINE --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const canvasElement = document.getElementById('ownerPendapatanChart');
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
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
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