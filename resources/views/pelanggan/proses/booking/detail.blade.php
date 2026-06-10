@extends('layouts.layout')

@section('title', 'Detail Booking Saya')

@section('content')
<div class="p-4">

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Pengajuan Booking</h2>
            <p class="text-gray-500 text-xs">Pantau status persetujuan pengajuan servis perangkat Anda</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('pelanggan.booking.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: DATA UTAMA PENGAJUAN --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Pengajuan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Booking</span>
                    <strong class="text-blue-600 text-sm">{{ $booking->kode_booking }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Pengajuan</span>
                    <span class="text-gray-800 font-semibold">
                        {{ \Carbon\Carbon::parse($booking->tgl_booking)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Merk / Tipe Device</span>
                    <span class="text-gray-800 font-semibold">{{ $booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Estimasi Kategori</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200 uppercase inline-block mt-0.5">
                        {{ $booking->kategori_servis }}
                    </span>
                </div>
            </div>

            <div class="p-4 text-xs grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/30 border-t border-gray-100">
                <div>
                    <span class="text-gray-400 block mb-1">Spesifikasi Unit:</span>
                    <div class="bg-white border border-gray-200 rounded p-2.5 text-gray-700 min-h-[40px]">
                        {{ $booking->spesifikasi ?? '-' }}
                    </div>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Kelengkapan Perangkat:</span>
                    <div class="bg-white border border-gray-200 rounded p-2.5 text-gray-700 min-h-[40px]">
                        {{ $booking->kelengkapan ?? '-' }}
                    </div>
                </div>
                <div class="md:col-span-2">
                    <span class="text-gray-400 block mb-1">Keluhan / Kerusakan Perangkat:</span>
                    <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic font-medium min-h-[50px]">
                        "{{ $booking->keluhan }}"
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 2: STATUS PERSETUJUAN & METODE --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Status & Penyerahan</h3>
            </div>

            <div class="p-4 grid grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Metode Pengambilan</span>
                    <span class="text-gray-800 font-bold uppercase tracking-wide mt-0.5 block">
                        {{ $booking->metode_pengambilan == 'diantar' ? 'Diantar' : 'Ambil Sendiri' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Status Deposit</span>
                    @if($booking->status_deposit == 'sudah lunas')
                        <span class="px-2 py-0.5 rounded font-bold bg-green-100 text-green-800 border border-green-200 uppercase">
                            Lunas
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded font-bold bg-red-100 text-red-800 border border-red-200 uppercase">
                            Belum Lunas
                        </span>
                    @endif
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Persetujuan Admin</span>
                    @if($booking->status_booking == 'pending')
                        <span class="px-2 py-0.5 rounded font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase">
                            Pending
                        </span>
                    @elseif($booking->status_booking == 'diterima')
                        <span class="px-2 py-0.5 rounded font-bold bg-green-100 text-green-800 border border-green-200 uppercase">
                            Diterima
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded font-bold bg-red-100 text-red-800 border border-red-200 uppercase">
                            Ditolak
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ALERT INFORMASI DINAMIS --}}
        @if($booking->status_booking == 'pending')
            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800 flex items-start gap-2 shadow-sm">
                <span class="mt-0.5">⚠️</span>
                <div>
                    <strong class="block mb-0.5">Menunggu Verifikasi Admin</strong>
                    <span>Booking Anda saat ini sedang dalam antrean pengecekan oleh pihak toko. Anda akan menerima pembaruan status pengerjaan setelah data dikonfirmasi.</span>
                </div>
            </div>
        @elseif($booking->status_booking == 'diterima')
            <div class="p-3 bg-green-50 border border-green-200 rounded text-xs text-green-800 flex items-start gap-2 shadow-sm">
                <span class="mt-0.5">✅</span>
                <div>
                    <strong class="block mb-0.5">Pengajuan Booking Disetujui</strong>
                    <span>Silakan bawa unit laptop/perangkat Anda beserta kelengkapannya ke toko Seven Komputer untuk segera dilakukan pengerjaan atau pengecekan fisik oleh teknisi.</span>
                </div>
            </div>
        @elseif($booking->status_booking == 'ditolak')
            <div class="p-3 bg-red-50 border border-red-200 rounded text-xs text-red-800 flex items-start gap-2 shadow-sm">
                <span class="mt-0.5">❌</span>
                <div>
                    <strong class="block mb-0.5">Pengajuan Booking Ditolak</strong>
                    <span>Mohon maaf, pengajuan jadwal booking Anda belum dapat kami setujui saat ini. Anda dapat melakukan pengajuan ulang atau menghubungi kontak customer service kami.</span>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection