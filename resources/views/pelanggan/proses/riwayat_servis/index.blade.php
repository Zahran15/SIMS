@extends('layouts.layout')

@section('title', 'Riwayat Servis Saya')

@section('content')

    {{-- HEADER --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Riwayat Servis</h2>
            <p class="text-gray-500 mt-1">Daftar seluruh perangkat Anda yang telah selesai diperbaiki</p>
        </div>
    </div>

    {{-- SESSION NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2 text-sm shadow-sm animate-fade-in">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-600 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">No</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">Nota / Booking</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">Perangkat</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">Tanggal Selesai</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">Total Biaya</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-white tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($riwayat as $index => $row)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $riwayat->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="font-bold text-gray-800">{{ $row->kode_servis }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $row->booking->kode_booking }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-semibold text-gray-700">{{ $row->booking->merk_tipe }}</div>
                                <div class="text-xs text-gray-400 line-clamp-1 truncate max-w-[200px]">{{ $row->booking->keluhan }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ \Carbon\Carbon::parse($row->tgl_selesai ?? $row->updated_at)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900">Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 border border-green-200 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('pelanggan.riwayat_servis.show', $row->id_servis) }}" 
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <p class="text-gray-500 font-medium">Belum ada riwayat servis yang selesai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($riwayat->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>
</div>
@endsection