@extends('layouts.layout')

@section('title', 'Detail Laporan Request Sparepart - Owner')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('owner.request_sparepart.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Detail Laporan Penggunaan</h2>
            <p class="text-gray-500 mt-1">Rincian data pengajuan komponen dan riwayat validasi sistem.</p>
        </div>

        {{-- BADGE STATUS --}}
        <div>
            @if($requestSparepart->status_request == 'pending')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                    ⏱️ Sedang Diproses Admin
                </span>
            @elseif($requestSparepart->status_request == 'disetujui')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                    ✅ Disetujui & Keluar Gudang
                </span>
            @else
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-red-100 text-red-700 border border-red-200">
                    ❌ Ditolak Oleh Admin
                </span>
            @endif
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-medium">
        
        {{-- CARD KIRI: DATA SERVIS & KOMPONEN --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Informasi Komponen & Tugas</h3>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Kode Servis / Nota</p>
                        <p class="text-base font-bold text-blue-600 mt-0.5">
                            {{ $requestSparepart->penugasan->servis->kode_servis ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Nama Pelanggan</p>
                        <p class="text-base font-semibold text-gray-800 mt-0.5">
                            {{ $requestSparepart->penugasan->servis->nama_pelanggan ?? 'Umum' }}
                        </p>
                    </div>
                    <div class="col-span-2 pt-2">
                        <p class="text-gray-400">Nama Sparepart / Komponen</p>
                        <p class="text-base font-bold text-gray-800 mt-0.5">
                            {{ $requestSparepart->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Jumlah Diambil</p>
                        <p class="text-base font-bold text-gray-800 mt-0.5">
                            {{ $requestSparepart->jumlah }} Pcs
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Sisa Stok Gudang Saat Ini</p>
                        <p class="text-base font-semibold text-gray-600 mt-0.5">
                            {{ $requestSparepart->sparepart->stok ?? 0 }} Pcs
                        </p>
                    </div>
                </div>
            </div>

            {{-- CARD ALASAN / JUSTIFIKASI TEKNISI --}}
            <div class="bg-white rounded-2xl shadow border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 pb-2 border-b">Justifikasi / Keterangan Teknisi</h3>
                <p class="text-gray-700 text-sm leading-relaxed bg-gray-50 p-4 rounded-xl border italic">
                    "{{ $requestSparepart->alasan }}"
                </p>
            </div>
        </div>

        {{-- CARD KANAN: LOG & TIMELINE TRANSAKSI --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow border p-6 text-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Audit System Trail</h3>
                
                <div class="space-y-5">
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 ring-4 ring-blue-100"></div>
                        <div>
                            <p class="font-bold text-gray-800">Diajukan Oleh Teknisi</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $requestSparepart->created_at->translatedFormat('d F Y - H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        @if($requestSparepart->status_request != 'pending')
                            <div class="w-2 h-2 rounded-full mt-1.5 ring-4 {{ $requestSparepart->status_request == 'disetujui' ? 'bg-green-600 ring-green-100' : 'bg-red-600 ring-red-100' }}"></div>
                            <div>
                                <p class="font-bold text-gray-800">
                                    Diverifikasi Admin ({{ ucfirst($requestSparepart->status_request) }})
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $requestSparepart->updated_at->translatedFormat('d F Y - H:i') }} WIB
                                </p>
                            </div>
                        @else
                            <div class="w-2 h-2 rounded-full bg-gray-300 mt-1.5 ring-4 ring-gray-100"></div>
                            <div>
                                <p class="font-bold text-gray-400">Menunggu Tindakan Admin</p>
                                <p class="text-xs text-gray-300 mt-0.5">Belum dieksekusi di gudang</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- FOOTER NOTE --}}
                <div class="mt-6 pt-4 border-t text-xs text-gray-400 leading-relaxed text-center">
                    Data ini bersifat permanen untuk keperluan pelaporan stok berkala Owner.
                </div>
            </div>
        </div>

    </div>
</div>
@endsection