@extends('layouts.layout')

@section('title', 'Technician Dashboard')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
            Technician <span class="text-blue-600">Workstation</span>
        </h2>
        <p class="text-gray-500 text-sm">Monitor antrean servis dan update progres perbaikan Anda.</p>
    </div>
        
    <div class="mt-4 md:mt-0 flex flex-col items-end gap-2">
        <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-100 text-xs font-bold uppercase tracking-widest block w-full text-center md:w-auto">
            <i class="fas fa-calendar-alt mr-2"></i> {{ date('d M Y') }}
        </span>

        <span x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
              x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)"
              class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-100 text-xs font-bold uppercase tracking-widest">
            <i class="fas fa-clock mr-2"></i> <span x-text="time"></span> WIB
        </span>
    </div>
</div>

@php
$stats = [
    ['title' => 'Sedang Dikerjakan', 'total' => $sedang_dikerjakan, 'icon' => 'fa-laptop-code', 'color' => 'emerald', 'label' => 'On Progress'],
    ['title' => 'Menunggu Sparepart', 'total' => $menunggu_sparepart, 'icon' => 'fa-boxes', 'color' => 'purple', 'label' => 'Pending Part'],
    ['title' => 'Selesai Bulan Ini', 'total' => $selesai_bulan_ini, 'icon' => 'fa-check-double', 'color' => 'cyan', 'label' => 'Completed'],
    ['title' => 'Total Servis', 'total' => $total_servis, 'icon' => 'fa-history', 'color' => 'blue', 'label' => 'All Time Total'],
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
    <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4 flex justify-between items-center border-b border-orange-500 border-b-2">
        <h5 class="font-bold uppercase text-xs text-white tracking-widest flex items-center">
            Tugas Prioritas Utama
        </h5>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-gray-50 text-gray-500 uppercase font-black text-[10px] tracking-widest border-b border-gray-100">
                <tr>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-center">Kategori</th>
                    <th class="px-6 py-5 text-center">Estimasi Selesai</th>
                    <th class="px-6 py-5 text-center">Kode Nota</th>
                    <th class="px-6 py-5 text-center">Merk/Tipe</th>
                    <th class="px-6 py-5 text-center">Keluhan</th>
                    <th class="px-6 py-5 text-center">Pelanggan</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">
                @forelse($tugas_prioritas as $tugas)
                    <tr class="hover:bg-gray-50/50 transition-colors text-gray-700">
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $tugas->status_penugasan == 'sedang dikerjakan' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-purple-50 text-purple-600 border border-purple-100' }}">
                                {{ $tugas->status_penugasan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded font-semibold border border-blue-100 uppercase text-[10px]">{{ $tugas->servis->booking->kategori_servis }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-gray-600">
                            {{ \Carbon\Carbon::parse($tugas->estimasi_selesai)->translatedFormat('d M Y') }}
                            @if(\Carbon\Carbon::parse($tugas->estimasi_selesai)->isToday())
                                <span class="text-[9px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-bold uppercase ml-1">Hari Ini!</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $tugas->servis->kode_servis }}</td>
                        <td class="px-6 py-4 text-center font-medium">{{ $tugas->servis->booking->merk_tipe }}</td>
                        <td class="px-6 py-4 text-center max-w-xs truncate text-gray-500">{{ $tugas->servis->booking->keluhan }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-semibold text-gray-800">{{ $tugas->servis->booking->pelanggan->nama }}</div>
                            <div class="text-[10px] text-gray-400">{{ $tugas->servis->booking->pelanggan->no_hp }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('teknisi.servis_kerja.detail', $tugas->id_penugasan) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-lg transition-all shadow-sm">
                                <i class="fas fa-wrench text-[10px]"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-24">
                            <div class="flex flex-col items-center justify-center text-center">
                                <h3 class="text-gray-500 font-bold uppercase tracking-widest text-[11px]">
                                    Semua tugas telah diselesaikan
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