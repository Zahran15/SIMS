@extends('layouts.layout')

@section('title', 'Detail Penugasan Teknisi')

@section('content')

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Penugasan</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap penugasan teknisi untuk perangkat pelanggan</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.penugasan.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
            <a href="{{ route('admin.penugasan.edit', $penugasan->id_penugasan) }}"
               class="px-3 py-1.5 rounded bg-yellow-600 hover:bg-yellow-700 text-white font-medium text-xs shadow-sm transition-colors">
                Edit Penugasan
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: DATA SERVIS --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Servis</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $penugasan->servis->kode_servis }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Masuk</span>
                    <span class="text-gray-800 font-semibold">
                        {{ date('d M Y', strtotime($penugasan->servis->tgl_masuk)) }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $penugasan->servis->booking->pelanggan->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Servis</span>
                    <span class="text-blue-600 font-bold uppercase tracking-wide">{{ $penugasan->servis->status_servis }}</span>
                </div>
            </div>
        </div>

        {{-- BLOK 2: DATA TEKNISI & ESTIMASI --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Teknisi & Waktu</h3>
            </div>

            <div class="p-4 grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Nama Teknisi Penanggung Jawab</span>
                    <span class="text-gray-800 font-bold text-sm">{{ $penugasan->teknisi->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Estimasi Waktu Selesai</span>
                    <span class="text-gray-800 font-semibold">
                        {{ $penugasan->estimasi_selesai ? date('d M Y', strtotime($penugasan->estimasi_selesai)) . ' ' : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- BLOK 3: PRIORITAS, STATUS & CATATAN --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Detail Status Penugasan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block mb-1">Tingkat Prioritas</span>
                    @php
                        $prioritasClass = [
                            'rendah' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'normal' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'tinggi' => 'bg-orange-100 text-orange-800 border-orange-200',
                            'urgent' => 'bg-red-100 text-red-800 border-red-200',
                        ][$penugasan->prioritas] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-bold border uppercase {{ $prioritasClass }}">
                        {{ $penugasan->prioritas }}
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
</div>
@endsection