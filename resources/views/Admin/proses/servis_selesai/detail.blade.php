@extends('layouts.layout')

@section('title', 'Detail Servis Selesai')

@section('content')

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Servis Selesai</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap data servis pelanggan (ID: #{{ $servis->kode_servis }})</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.servis_selesai.index') }}"
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
                    <span class="text-gray-400 block">Nama Lengkap</span>
                    <strong class="text-gray-800 font-semibold">{{ $servis->booking->pelanggan->nama }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">No. Handphone</span>
                    <span class="text-blue-600 font-semibold">{{ $servis->booking->pelanggan->no_hp }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Merk / Tipe Device</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Spesifikasi</span>
                    <span class="text-gray-800">{{ $servis->booking->spesifikasi ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- BLOK 2: DATA PERANGKAT & DEVICE (INFORMASI SERVIS) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Servis</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $servis->kode_servis }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Masuk</span>
                    <span class="text-gray-800 font-semibold">{{ date('d M Y', strtotime($servis->tgl_masuk)) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Perkiraan Selesai</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->perkiraan_selesai ? date('d M Y', strtotime($servis->perkiraan_selesai)) : '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Servis</span>
                    @php
                        $statusClasses = [
                            'selesai' => 'bg-green-100 text-green-800 border border-green-200',
                            'bisa diambil' => 'bg-blue-100 text-blue-800 border border-blue-200',
                            'sudah diambil' => 'bg-gray-100 text-gray-800 border border-gray-200'
                        ];
                        $currentClass = $statusClasses[$servis->status_servis] ?? 'bg-gray-100 text-gray-600 border border-gray-200';
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase inline-block mt-0.5 {{ $currentClass }}">
                        {{ $servis->status_servis }}
                    </span>
                </div>
            </div>

            <div class="p-4 text-xs grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/30">
                <div class="md:col-span-2">
                    <span class="text-gray-400 block mb-1">Keluhan Pelanggan:</span>
                    <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic font-medium min-h-[50px]">
                        "{{ $servis->booking->keluhan }}"
                    </div>
                </div>
                <div class="md:col-span-2">
                    <span class="text-gray-400 block mb-1">Total Biaya Servis:</span>
                    <div class="bg-white border border-gray-200 rounded p-3 text-xl font-bold text-green-600 min-h-[50px] flex items-center">
                        Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 3: DETAIL JASA & SPAREPART (GRID 2 KOLOM SEJAJAR DI BAWAH) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            
            {{-- JASA SERVIS --}}
            <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 py-2 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Jasa Servis</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 text-gray-700 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama Jasa</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servis->detailJasa as $jasa)
                                <tr class="border-b">
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $jasa->jasa->nama_jasa }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-green-600">
                                        Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-6 text-center text-gray-500 italic">
                                        Belum ada jasa servis
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SPAREPART --}}
            <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 py-2 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Sparepart</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 text-gray-700 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Sparepart</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servis->detailSparepart as $sp)
                                <tr class="border-b">
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $sp->sparepart->nama_sparepart }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-600">
                                        {{ $sp->qty }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-green-600">
                                        Rp {{ number_format($sp->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 italic">
                                        Belum ada sparepart
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection