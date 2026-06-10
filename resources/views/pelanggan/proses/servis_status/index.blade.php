@extends('layouts.layout')

@section('title', 'Status Servis Saya')

@section('content')
    {{-- HEADER --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Status Servis</h2>
            <p class="text-gray-500 mt-1">Pantau progres perbaikan perangkat Anda secara real-time</p>
        </div>
    </div>

    {{-- TABLE / LIST --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-indigo-600 text-white text-sm">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Kode Servis</th>
                        <th class="px-5 py-4 text-center">Perangkat</th>
                        <th class="px-5 py-4 text-center">Teknisi</th>
                        <th class="px-5 py-4 text-center">Status Servis</th>
                        <th class="px-5 py-4 text-center">Total Biaya</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-center">
                    @forelse($riwayatServis as $index => $s)
                    @php
                        $tugasTeknisi = $s->penugasan ? $s->penugasan->first() : null;
                    @endphp
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-center">{{ $riwayatServis->firstItem() + $index }}</td>
                        <td class="px-5 py-4 text-center">
                            <div class="font-bold text-indigo-600">{{ $s->kode_servis }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Booking: {{ $s->booking->kode_booking ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-center font-semibold text-gray-800">{{ $s->booking->merk_tipe ?? '-' }}</td>
                        <td class="px-5 py-4 text-center text-gray-600">{{ $s->penugasan && $s->penugasan->user ? $s->penugasan->user->nama : 'Menunggu antrean...' }}</td>
                        <td class="px-5 py-4 text-center">
                            <div class="mb-1.5">
                                @if($s->status_servis == 'menunggu')
                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold border border-yellow-200">Menunggu Antrean</span>
                                @elseif($s->status_servis == 'proses')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold border border-blue-200">Sedang Diproses</span>
                                @elseif($s->status_servis == 'selesai')
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold border border-indigo-200">Perbaikan Selesai</span>
                                @elseif($s->status_servis == 'bisa diambil')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold border border-green-200">Siap Diambil</span>
                                @elseif($s->status_servis == 'sudah diambil')
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold border border-gray-200">Sudah Diambil</span>
                                @elseif($s->status_servis == 'dibatalkan')
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold border border-red-200">Dibatalkan</span>
                                @endif
                            </div>
                        
                            {{-- Ganti pengecekan $tugasTeknisi dengan langsung mengecek $s->penugasan --}}
                            @if($s->penugasan && $s->status_servis == 'proses')
                                <div class="text-[11px] text-gray-500 font-medium mt-1 bg-gray-50 p-1 rounded border border-dashed border-gray-200 inline-block">
                                    @if($s->penugasan->status_penugasan == 'belum dikerjakan')
                                        <span class="text-gray-500">Belum disentuh teknisi</span>
                                    @elseif($s->penugasan->status_penugasan == 'sedang dikerjakan')
                                        <span class="text-blue-600 animate-pulse">Sedang dikerjakan</span>
                                    @elseif($s->penugasan->status_penugasan == 'menunggu sparepart')
                                        <span class="text-orange-600 font-semibold">Menunggu sparepart datang</span>
                                    @elseif($s->penugasan->status_penugasan == 'selesai')
                                        <span class="text-green-600">Selesai dikerjakan</span>
                                    @elseif($s->penugasan->status_penugasan == 'gagal')
                                        <span class="text-red-600">Unit tidak dapat diperbaiki</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center font-bold text-gray-800">Rp {{ number_format($s->total_biaya, 0, ',', '.') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex justify-center">
                                <a href="{{ route('pelanggan.servis_status.detail', $s->id_servis) }}" 
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
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            Anda belum memiliki riwayat atau pengajuan servis perangkat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $riwayatServis->links() }}
    </div>
@endsection