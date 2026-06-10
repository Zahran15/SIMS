@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
            Admin <span class="text-blue-600">Control Panel</span>
        </h2>
        <p class="text-gray-500 text-sm">Selamat datang kembali, berikut ringkasan operasional hari ini.</p>
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
    ['title'=>'Booking Baru', 'total' => $totalBookingBaru, 'icon'=>'fa-clipboard-list', 'color'=>'emerald', 'label' => 'Perlu Konfirmasi'],
    ['title'=>'Verifikasi Deposit', 'total' => $totalVerifikasiDeposit, 'icon'=>'fa-receipt', 'color'=>'blue', 'label' => 'Cek Pembayaran'],
    ['title'=>'Dalam Proses', 'total' => $totalDalamProses, 'icon'=>'fa-tools', 'color'=>'amber', 'label' => 'Sedang Dikerjakan'],
    ['title'=>'Siap Diambil', 'total' => $totalSiapDiambil, 'icon'=>'fa-box-open', 'color'=>'indigo', 'label' => 'Selesai Servis'],
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
                <h3 class="text-3xl font-black text-gray-800 group-hover:text-{{ $stat['color'] }}-600 transition-colors">
                    {{ $stat['total'] }}
                </h3>
                <span class="text-[9px] font-bold text-{{ $stat['color'] }}-500 bg-{{ $stat['color'] }}-50 px-2 py-0.5 rounded uppercase mt-2 block w-max">
                    {{ $stat['label'] }}
                </span>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center group-hover:bg-{{ $stat['color'] }}-50 transition-colors">
                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-xl"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4 flex justify-between items-center">
        <h5 class="font-bold uppercase text-xs text-white tracking-widest flex items-center">
            Antrean Booking Terbaru
        </h5>
        <a href="{{ route('admin.booking.index') }}" class="text-[10px] font-black text-gray-300 hover:text-white uppercase transition">Lihat Semua Data</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4 text-center">No</th>
                    <th class="px-6 py-4 text-center">Nama Pelanggan</th>
                    <th class="px-6 py-4 text-center">Tanggal</th>
                    <th class="px-6 py-4 text-center">Keluhan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $index => $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-center font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-center text-gray-700">{{ $booking->pelanggan->nama ?? 'Pelanggan #' . $booking->id_pelanggan }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ \Carbon\Carbon::parse($booking->tgl_booking)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center text-gray-600 max-w-xs truncate">{{ $booking->keluhan }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-bold rounded 
                                {{ $booking->status_booking === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                {{ $booking->status_booking === 'diterima' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                {{ $booking->status_booking === 'ditolak' ? 'bg-rose-50 text-rose-700' : '' }}
                                uppercase">
                                {{ $booking->status_booking }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.booking.index', ['id' => $booking->id_booking]) }}" 
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>                            
                            </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-20">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-dashed border-gray-200">
                                    <i class="fas fa-folder-open text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-gray-500 font-bold text-xs uppercase tracking-widest">
                                    Tidak ada antrean saat ini
                                </h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection