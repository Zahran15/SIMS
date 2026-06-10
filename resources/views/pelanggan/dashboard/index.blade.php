@extends('layouts.layout')

@section('title', 'Customer Dashboard')

@section('content')

{{-- BANNER HERO --}}
<div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-8 mb-8 shadow-xl text-white">
    <div class="relative z-10 md:flex justify-between items-center">
        <div class="max-w-lg">
            <h1 class="text-3xl font-black mb-3 uppercase tracking-tight">Halo, {{ Auth::guard('pelanggan')->user()->nama }}</h1>
            <p class="text-blue-100 opacity-90 leading-relaxed mb-6">
                Pantau perbaikan perangkat Anda secara transparan. Seven Komputer berkomitmen memberikan servis terbaik untuk Anda.
            </p>
            <div class="flex flex-wrap gap-3">
                {{-- AKTIFKAN LINK TOMBOL BOOKING --}}
                <a href="{{ route('pelanggan.booking.create') }}" class="bg-white text-blue-700 px-6 py-3 rounded-2xl font-bold text-sm shadow-lg hover:shadow-white/20 transition-all hover:-translate-y-1 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Buat Booking Baru
                </a>
            </div>
        </div>
        
        <div class="hidden lg:block">
            <i class="fas fa-laptop-medical text-[120px] text-white/10 rotate-12"></i>
        </div>
    </div>
    
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full"></div>
    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-indigo-400 opacity-10 rounded-full"></div>
</div>

{{-- TABEL STATUS SERVIS --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-orange-500 to-orange-400 px-6 py-5 flex justify-between items-center">
        <h5 class="font-black uppercase text-xs text-white tracking-widest flex items-center">
            Status Servis Perangkat Anda
        </h5>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b">
                <tr>
                    <th class="px-6 py-5 text-center">Nota / Kode</th>
                    <th class="px-6 py-5 text-center">Perangkat</th>
                    <th class="px-6 py-5 text-center">Keluhan</th>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-center">Estimasi Biaya</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($servis_aktif as $row)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-gray-800">
                            <div>{{ $row->kode_servis }}</div>
                            <div class="text-[10px] text-gray-400 font-normal mt-0.5">{{ $row->booking->kode_booking }}</div>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $row->booking->merk_tipe }}</td>
                        <td class="px-6 py-4 text-center text-gray-500 max-w-xs truncate">{{ $row->booking->keluhan }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $row->status_servis == 'proses' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                {{ $row->status_servis == 'antrean' ? 'bg-yellow-50 text-yellow-600 border border-yellow-100' : '' }}
                                {{ $row->status_servis == 'batal' ? 'bg-red-50 text-red-600 border border-red-100' : '' }}">
                                {{ $row->status_servis }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-900">Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20">
                            <div class="flex flex-col items-center justify-center text-center">
                                <h3 class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Belum Ada Servis Aktif</h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection