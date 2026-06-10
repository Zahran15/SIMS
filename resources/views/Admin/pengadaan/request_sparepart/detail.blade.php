@extends('layouts.layout')

@section('title', 'Proses Request Sparepart - Admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.request_sparepart.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Verifikasi Request Sparepart</h2>
            <p class="text-gray-500 mt-1">Periksa ketersediaan stok sebelum melakukan persetujuan barang.</p>
        </div>

        {{-- BADGE STATUS --}}
        <div>
            @if($requestSparepart->status_request == 'pending')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 animate-pulse">
                    ⏱️ Butuh Tindakan
                </span>
            @elseif($requestSparepart->status_request == 'disetujui')
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                    ✅ Sudah Disetujui
                </span>
            @else
                <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-red-100 text-red-700 border border-red-200">
                    ❌ Sudah Ditolak
                </span>
            @endif
        </div>
    </div>

    {{-- NOTIFIKASI SUKSES / GAGAL --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-100 text-red-700 font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- KONTEN UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-medium">
        
        {{-- CARD KIRI: RINCIAN REQUEST --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Detail Permintaan Barang</h3>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Kode Servis / Pelanggan</p>
                        <p class="text-base font-bold text-gray-800 mt-0.5">
                            {{ $requestSparepart->penugasan->servis->kode_servis ?? '-' }} 
                            <span class="text-gray-400 font-normal">({{ $requestSparepart->penugasan->servis->nama_pelanggan ?? 'Umum' }})</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Nama Sparepart</p>
                        <p class="text-base font-bold text-blue-600 mt-0.5">
                            {{ $requestSparepart->sparepart->nama_sparepart ?? 'Terhapus' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Jumlah Diminta Teknisi</p>
                        <p class="text-base font-bold text-gray-800 mt-0.5">
                            {{ $requestSparepart->jumlah }} Pcs
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Sisa Stok Fisik Gudang Saat Ini</p>
                        <p class="text-base font-bold mt-0.5 {{ ($requestSparepart->sparepart->stok ?? 0) < $requestSparepart->jumlah ? 'text-red-600' : 'text-green-600' }}">
                            {{ $requestSparepart->sparepart->stok ?? 0 }} Pcs
                        </p>
                    </div>
                </div>
            </div>

            {{-- CARD ALASAN DARI TEKNISI --}}
            <div class="bg-white rounded-2xl shadow border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 pb-2 border-b">Alasan Kerusakan / Penggantian</h3>
                <p class="text-gray-700 text-sm leading-relaxed bg-gray-50 p-4 rounded-xl border italic">
                    "{{ $requestSparepart->alasan }}"
                </p>
            </div>
        </div>

        {{-- CARD KANAN: PANEL AKSI APPROVE / REJECT (HANYA MUNCUL JIKA PENDING) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow border p-6 text-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Panel Otorisasi Admin</h3>
                
                @if($requestSparepart->status_request == 'pending')
                    <p class="text-gray-500 mb-4 leading-relaxed">
                        Silakan lakukan verifikasi fisik barang. Klik <strong>Approve</strong> jika barang siap diserahkan ke teknisi (stok otomatis terpotong).
                    </p>

                    <div class="space-y-3">
                        {{-- TOMBOL APPROVE --}}
                        <form action="{{ route('request.approve', $requestSparepart->id_request) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold transition-all shadow-md shadow-green-100 flex items-center justify-center gap-1"
                                    {{ ($requestSparepart->sparepart->stok ?? 0) < $requestSparepart->jumlah ? 'disabled' : '' }}
                                    onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI permintaan ini? Stok gudang akan otomatis berkurang.')">
                                ✅ Approve & Serahkan
                            </button>
                            @if(($requestSparepart->sparepart->stok ?? 0) < $requestSparepart->jumlah)
                                <p class="text-xs text-red-500 text-center mt-1 font-semibold">❌ Tidak bisa di-approve, stok gudang habis/kurang!</p>
                            @endif
                        </form>

                        {{-- TOMBOL REJECT --}}
                        <form action="{{ route('request.reject', $requestSparepart->id_request) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full py-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-semibold transition-all border border-red-200 flex items-center justify-center gap-1"
                                    onclick="return confirm('Apakah Anda yakin ingin MENOLAK permintaan ini?')">
                                ❌ Tolak Request
                            </button>
                        </form>
                    </div>
                @else
                    {{-- JIKA SUDAH DIPROSES --}}
                    <div class="p-4 bg-gray-50 rounded-xl border text-center text-gray-500">
                        <p class="font-semibold">Selesai Diproses</p>
                        <p class="text-xs mt-1">
                            Data ini sudah dikunci pada {{ $requestSparepart->updated_at->translatedFormat('d F Y - H:i') }} WIB dan tidak dapat diubah lagi.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection