@extends('layouts.layout')

@section('title', 'Detail Booking')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Booking Servis</h2>
            <p class="text-gray-500 text-xs">Informasi data pengajuan booking dari pelanggan (ID: #{{ $booking->kode_booking }})</p>
        </div>
        
        <div class="flex items-center gap-2">
            {{-- Tombol Setuju (Hanya muncul jika pending DAN sudah lunas) --}}
            @if($booking->status_booking == 'pending' && $booking->status_dp == 'sudah lunas')
                <form action="{{ route('admin.booking.terima', $booking->id_booking) }}" 
                      method="POST" 
                      id="form-setuju-detail"
                      class="inline-block m-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-3 py-1.5 rounded bg-green-600 hover:bg-green-700 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Setujui Booking
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.booking.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: DATA PELANGGAN --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Pelanggan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Pelanggan</span>
                    <strong class="text-gray-800">{{ $booking->pelanggan->kode_pelanggan }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Lengkap</span>
                    <span class="text-gray-800 font-semibold">{{ $booking->pelanggan->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Alamat Email</span>
                    <span class="text-gray-800">{{ $booking->pelanggan->email }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">No. Handphone</span>
                    <span class="text-blue-600 font-semibold">{{ $booking->pelanggan->no_hp }}</span>
                </div>
            </div>
        </div>

        {{-- BLOK 2: DATA PERANGKAT & DEVICE --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Perangkat / Device</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block">Kode Booking</span>
                    <strong class="text-blue-600 text-sm">{{ $booking->kode_booking }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Booking</span>
                    <span class="text-gray-800 font-semibold">{{ date('d M Y', strtotime($booking->tgl_booking)) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Merk / Tipe Device</span>
                    <span class="text-gray-800 font-semibold">{{ $booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Kategori Servis</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200 uppercase inline-block mt-0.5">
                        {{ $booking->kategori_servis }}
                    </span>
                </div>
            </div>

            <div class="p-4 text-xs grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/30">
                <div>
                    <span class="text-gray-400 block mb-1">Spesifikasi Unit:</span>
                    <div class="bg-white border border-gray-200 rounded p-2.5 text-gray-700 min-h-[40px]">
                        {{ $booking->spesifikasi ?? '-' }}
                    </div>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Kelengkapan Bawaan:</span>
                    <div class="bg-white border border-gray-200 rounded p-2.5 text-gray-700 min-h-[40px]">
                        {{ $booking->kelengkapan ?? '-' }}
                    </div>
                </div>
                <div class="md:col-span-2">
                    <span class="text-gray-400 block mb-1">Keluhan Kerusakan / Masalah:</span>
                    <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic font-medium min-h-[50px]">
                        "{{ $booking->keluhan }}"
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 3: METODE & STATUS PENGAJUAN --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Status Pengambilan & Dp</h3>
            </div>

            <div class="p-4 grid grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Metode Pengembalian Unit</span>
                    <span class="text-gray-800 font-bold uppercase tracking-wide text-sm block mt-0.5">
                        {{ $booking->metode_pengembalian }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Status Dp</span>
                    @if($booking->status_dp == 'sudah lunas')
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
                    <span class="text-gray-400 block mb-1">Status Booking</span>
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
    </div>

    {{-- SWEETALERT2 SCRIPT UNTUK HALAMAN DETAIL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formSetujuDetail = document.getElementById('form-setuju-detail');
            if (formSetujuDetail) {
                formSetujuDetail.addEventListener('submit', function (e) {
                    e.preventDefault(); 
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Status booking akan diubah menjadi 'Diterima'!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#6b7280',  
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formSetujuDetail.submit();
                        }
                    });
                });
            }
        });
    </script>
@endsection