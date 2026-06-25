@extends('layouts.layout')

@section('title', 'Detail Request Sparepart - Pelanggan')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    {{-- TOMBOL KEMBALI --}}
    <div class="mb-6">
        <a href="{{ route('pelanggan.request_sparepart.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition-colors bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- MAIN CARD CONTAINER --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- CARD HEADER --}}
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Detail Pengajuan Sparepart</span>
                <h1 class="text-xl font-bold text-gray-800 mt-0.5">ID Request: #{{ $requestSparepart->id_request }}</h1>
            </div>
            
            {{-- BADGE STATUS INTERAKTIF --}}
            <div>
                @if($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                    <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-200 animate-pulse">
                        Menunggu Persetujuan Anda
                    </span>
                @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                    <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200">
                        Telah Anda Setujui
                    </span>
                @elseif($requestSparepart->status_request == 'disetujui')
                    <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200">
                        Selesai / Diproses Admin
                    </span>
                @else
                    <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                        Ditolak
                    </span>
                @endif
            </div>
        </div>

        {{-- CARD BODY --}}
        <div class="p-6 space-y-8">
            {{-- INFORMASI PERANGKAT --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Informasi Perangkat Anda
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400">Nama Perangkat</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $requestSparepart->penugasan->servis->booking->nama_perangkat ?? 'Perangkat' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Nomor Nota Servis</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">#{{ $requestSparepart->penugasan->servis->id_servis ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- DETAIL KOMPONEN --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Komponen & Estimasi Kuantitas
                </h3>
                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3">Nama Sparepart</th>
                                <th class="px-6 py-3 text-center w-32">Jumlah Diajukan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $requestSparepart->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-blue-600 bg-blue-50/30">
                                    {{ $requestSparepart->jumlah }} Pcs
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- JUSTIFIKASI TEKNISI --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Catatan & Alasan dari Teknisi
                </h3>
                <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100 text-sm text-gray-700 leading-relaxed italic">
                    "{{ $requestSparepart->alasan }}"
                </div>
            </div>
        </div>

        {{-- PANEL AKSI KONFIRMASI (HANYA AKTIF JIKA STATUS DIKIRIM KE PELANGGAN) --}}
        @if($requestSparepart->status_request == 'dikirim_ke_pelanggan')
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 font-medium">
                <div class="text-center sm:text-left">
                    <p class="text-sm font-semibold text-gray-800">Apakah Anda menyetujui penggantian ini?</p>
                    <p class="text-xs text-gray-400 mt-0.5">Perbaikan perangkat akan dilanjutkan setelah Anda memberikan persetujuan resmi.</p>
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{-- FORM REJECT/TOLAK --}}
                    <form action="{{ route('pelanggan.request_sparepart.reject', $requestSparepart->id_request) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENOLAK pergantian komponen ini?')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white border border-red-200 text-red-600 hover:bg-red-50 font-semibold text-sm transition-all text-center shadow-sm">
                            Tolak Pergantian
                        </button>
                    </form>

                    {{-- FORM APPROVE/SETUJU --}}
                    <form action="{{ route('pelanggan.request_sparepart.approve', $requestSparepart->id_request) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda MENYETUJUI pergantian komponen ini?')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition-all text-center shadow-md shadow-green-100">
                            Setujui & Lanjutkan
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- LOG RIWAYAT DATA TERKUNCI --}}
            <div class="p-4 bg-gray-50 border-t border-gray-100 text-center text-xs text-gray-400 font-medium">
                Pengajuan ini sudah ditinjau dan tidak memerlukan tindakan lebih lanjut dari Anda.
            </div>
        @endif
    </div>
</div>
@endsection