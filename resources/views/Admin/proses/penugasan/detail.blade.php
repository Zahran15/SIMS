@extends('layouts.layout')

@section('title', 'Detail Penugasan Teknisi')

@section('content')

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Penugasan</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap penugasan teknisi dan detail unit booking pelanggan</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.penugasan.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: DATA SERVIS & BOOKING --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Administrasi</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $penugasan->servis->kode_servis }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Kode Booking</span>
                    <strong class="text-purple-600 text-sm">{{ $penugasan->servis->booking->kode_booking }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Booking</span>
                    <span class="text-gray-800 font-semibold">
                        {{ date('d M Y', strtotime($penugasan->servis->booking->tgl_booking)) }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Masuk Servis</span>
                    <span class="text-gray-800 font-semibold">
                        {{ date('d M Y', strtotime($penugasan->servis->tgl_masuk)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- BLOK 2: DETAIL PELANGGAN & PERANGKAT (DARI DATA BOOKING) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Detail Pelanggan & Perangkat</h3>
            </div>

            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                {{-- Data Pelanggan --}}
                <div class="space-y-2 border-b md:border-b-0 md:border-r pb-4 md:pb-0 md:pr-6 border-gray-100">
                    <h4 class="font-semibold text-gray-600 uppercase text-[10px] tracking-wider mb-2">Informasi Pelanggan</h4>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">Nama</span>
                        <span class="col-span-2 text-gray-800 font-semibold">: {{ $penugasan->servis->booking->pelanggan->nama }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">No. Telepon</span>
                        <span class="col-span-2 text-gray-800 font-semibold">: {{ $penugasan->servis->booking->pelanggan->no_hp ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">Email</span>
                        <span class="col-span-2 text-gray-800">: {{ $penugasan->servis->booking->pelanggan->email ?? '-' }}</span>
                    </div>
                </div>

                {{-- Data Perangkat Elektronik --}}
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-600 uppercase text-[10px] tracking-wider mb-2">Spesifikasi Unit</h4>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">Merk / Tipe</span>
                        <span class="col-span-2 text-gray-800 font-bold">: {{ $penugasan->servis->booking->merk_tipe }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">Kategori Servis</span>
                        <span class="col-span-2 text-gray-800 font-semibold">: 
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ $penugasan->servis->booking->kategori_servis }}
                            </span>
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">Metode Pengembalian</span>
                        <span class="col-span-2 text-gray-800 capitalize">: {{ $penugasan->servis->booking->metode_pengembalian }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        <span class="text-gray-400">Kelengkapan</span>
                        <span class="col-span-2 text-gray-700 font-medium">: {{ $penugasan->servis->booking->kelengkapan ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Kolom Spesifikasi & Keluhan Utama --}}
            <div class="px-4 pb-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block mb-1">Detail Spesifikasi Perangkat:</span>
                    <div class="bg-gray-50 border border-gray-200 rounded p-2.5 text-gray-700">
                        {{ $penugasan->servis->booking->spesifikasi }}
                    </div>
                </div>
                <div>
                    <span class="text-red-400 font-semibold block mb-1">Keluhan Pelanggan:</span>
                    <div class="bg-red-50/50 border border-red-100 rounded p-2.5 text-red-900 font-medium">
                        {{ $penugasan->servis->booking->keluhan }}
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 3: DATA TEKNISI & ESTIMASI WAKTU --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Teknisi & Waktu Pengerjaan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div class="col-span-2">
                    <span class="text-gray-400 block">Nama Teknisi Penanggung Jawab</span>
                    <span class="text-gray-800 font-bold text-sm">{{ $penugasan->teknisi->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Estimasi Waktu Selesai</span>
                    <span class="text-gray-800 font-semibold">
                        {{ $penugasan->estimasi_selesai ? date('d M Y', strtotime($penugasan->estimasi_selesai)) : '-' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Operasional Servis</span>
                    <span class="text-blue-600 font-bold uppercase tracking-wide text-sm">{{ $penugasan->servis->status_servis }}</span>
                </div>
            </div>
        </div>

        {{-- BLOK 4: PRIORITAS, STATUS & CATATAN TEKNISI --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Detail Status Penugasan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block mb-1">Tingkat Prioritas</span>
                    @php
                        $prioritasClass = [
                            'ringan' => 'bg-green-100 text-green-800 border-green-200',
                            'sedang' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'berat' => 'bg-red-100 text-red-800 border-red-200',
                        ][$penugasan->prioritas] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-bold border uppercase {{ $prioritasClass }}">
                        {{ $penugasan->prioritas ?? 'Belum Ditentukan' }}
                    </span>
                </div>

                <div>
                    <span class="text-gray-400 block mb-1">Status Pengerjaan Teknisi</span>
                    @php
                        $statusClass = [
                            'belum dikerjakan' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'sedang dikerjakan' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'menunggu sparepart' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'selesai' => 'bg-green-100 text-green-800 border-green-200',
                            'gagal' => 'bg-red-100 text-red-800 border-red-200',
                        ][$penugasan->status_penugasan] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-bold border uppercase {{ $statusClass }}">
                        {{ $penugasan->status_penugasan }}
                    </span>
                </div>
            </div>

            <div class="px-4 py-4 text-xs bg-gray-50/30">
                <span class="text-gray-400 block mb-1">Catatan dari Teknisi:</span>
                <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic min-h-[60px]">
                    "{{ $penugasan->catatan_teknisi ?? 'Tidak ada catatan khusus dari teknisi.' }}"
                </div>
            </div>
        </div>

    </div>
@endsection