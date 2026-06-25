@extends('layouts.layout')

@section('title', 'Detail Request Sparepart')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <a href="{{ route('teknisi.request_sparepart.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Detail Request Sparepart</h2>
            <p class="text-gray-500 mt-1">Informasi lengkap mengenai status pengajuan komponen perbaikan perangkat.</p>
        </div>

        {{-- BADGE STATUS DI KANAN ATAS --}}
        <div>
            @if($requestSparepart->status_request == 'pending_admin')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-yellow-100 text-yellow-700 shadow-sm border border-yellow-200">Pending Admin</span>
            @elseif($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-orange-100 text-orange-700 shadow-sm border border-orange-200 animate-pulse">Di Pelanggan</span>
            @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-blue-100 text-blue-700 shadow-sm border border-blue-200">ACC Pelanggan</span>
            @elseif($requestSparepart->status_request == 'disetujui')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-green-100 text-green-700 shadow-sm border border-green-200">Request Disetujui</span>
            @else
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-red-100 text-red-700 shadow-sm border border-red-200">Request Ditolak</span>
            @endif
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- CARD KIRI: DATA SERVIS & KOMPONEN --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Informasi Komponen & Tugas</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 font-medium">Kode Servis</p>
                        <p class="text-base font-bold text-blue-600 mt-1">
                            {{ $requestSparepart->penugasan->servis->kode_servis ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Nama Pelanggan</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ $requestSparepart->penugasan->servis->booking->pelanggan->nama ?? '-' }}
                        </p>
                    </div>
                    <div class="sm:col-span-2 pt-2">
                        <p class="text-gray-400 font-medium">Nama Sparepart / Komponen</p>
                        <p class="text-base font-semibold text-gray-800 mt-1">
                            {{ $requestSparepart->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Kategori</p>
                        <p class="text-base font-medium text-gray-700 mt-1">
                            {{ $requestSparepart->sparepart->kategori ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Jumlah yang Diminta</p>
                        <p class="text-base font-bold text-gray-800 mt-1">
                            {{ $requestSparepart->jumlah }} Pcs
                        </p>
                    </div>
                </div>
            </div>

            {{-- CARD ALASAN --}}
            <div class="bg-white rounded-2xl shadow border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 pb-2 border-b">Alasan Permintaan Barang</h3>
                <p class="text-gray-700 text-sm leading-relaxed bg-gray-50 p-4 rounded-xl border italic">
                    "{{ $requestSparepart->alasan }}"
                </p>
            </div>
        </div>

        {{-- CARD KANAN: TRACKING WAKTU / TIMELINE LENGKAP --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow border p-6 text-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Log Aktivitas Request</h3>
                
                <div class="space-y-5">
                    {{-- TAHAP 1: AJUAN TEKNISI --}}
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 ring-4 ring-blue-100 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-800">Diajukan oleh Anda (Teknisi)</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $requestSparepart->created_at->translatedFormat('d F Y - H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    {{-- TAHAP 2: PROSES VALIDASI BERTINGKAT --}}
                    <div class="flex gap-3 border-t pt-3">
                        @if($requestSparepart->status_request == 'pending_admin')
                            <div class="w-2 h-2 rounded-full bg-yellow-500 mt-1.5 ring-4 ring-yellow-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-gray-800">Menunggu Validasi Admin</p>
                                <p class="text-xs text-gray-400 mt-0.5">Menunggu review internal pengadaan gudang</p>
                            </div>
                        @elseif($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                            <div class="w-2 h-2 rounded-full bg-orange-500 mt-1.5 ring-4 ring-orange-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-gray-800">Menunggu Respon Pelanggan</p>
                                <p class="text-xs text-gray-400 mt-0.5">Rincian diteruskan ke akun konsumen untuk persetujuan biaya</p>
                            </div>
                        @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 ring-4 ring-blue-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-gray-800">Telah Di-ACC Pelanggan</p>
                                <p class="text-xs text-gray-400 mt-0.5">Menunggu verifikasi akhir dan pemotongan stok oleh Admin</p>
                            </div>
                        @elseif($requestSparepart->status_request == 'disetujui')
                            <div class="w-2 h-2 rounded-full bg-green-600 mt-1.5 ring-4 ring-green-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-green-700">Request Disetujui Penuh</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Selesai diverifikasi pada {{ $requestSparepart->updated_at->translatedFormat('d F Y - H:i') }} WIB. Suku cadang dapat diambil.
                                </p>
                            </div>
                        @else
                            <div class="w-2 h-2 rounded-full bg-red-600 mt-1.5 ring-4 ring-red-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-red-600">Request Ditolak / Dibatalkan</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Diputus pada {{ $requestSparepart->updated_at->translatedFormat('d F Y - H:i') }} WIB.
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection