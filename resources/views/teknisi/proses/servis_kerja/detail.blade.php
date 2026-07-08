@extends('layouts.layout')

@section('title', 'Detail Servis')

@section('content')

{{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Pengerjaan Servis</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap status dan rincian perbaikan perangkat (ID: #{{ $servis->kode_servis }})</p>
        </div>
        
        <div class="flex items-center gap-2">
            {{-- 🆕 TOMBOL EDIT PENUGASAN/STATUS --}}
            <a href="{{ route('teknisi.servis_kerja.edit', $penugasan->id_penugasan) }}" 
               class="px-3 py-1.5 rounded bg-amber-500 hover:bg-amber-600 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1">
                Edit Pengerjaan
            </a>

            <a href="{{ route('teknisi.servis_kerja.index') }}" 
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: INFORMASI UTAMA SERVIS & DETAIL DATA BOOKING --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Perangkat & Status</h3>
            </div>

            {{-- Info Utama --}}
            <div class="p-4 grid grid-cols-2 md:grid-cols-5 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $servis->kode_servis }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->pelanggan->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Device</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Prioritas Tugas</span>
                    <span class="mt-0.5 inline-block px-2.5 py-0.5 text-[11px] rounded-full font-bold uppercase
                        {{ $penugasan->prioritas == 'berat' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $penugasan->prioritas == 'sedang' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $penugasan->prioritas == 'ringan' ? 'bg-green-100 text-green-700' : '' }}">
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Tugas</span>
                    <span class="text-blue-600 font-bold uppercase tracking-wide mt-0.5 inline-block">
                        {{ $penugasan->status_penugasan }}
                    </span>
                </div>
            </div>

            {{-- 🆕 TAMBAHAN FIELD BARU DARI BOOKING (Grid Administratif & Fisik) --}}
            <div class="px-4 py-3 bg-gray-50/50 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block">Kode Booking Asal</span>
                    <span class="text-gray-700 font-medium">{{ $servis->booking->kode_booking }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Booking Masuk</span>
                    <span class="text-gray-700 font-medium">{{ date('d M Y', strtotime($servis->booking->tgl_booking)) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Kategori Kendala (User)</span>
                    <span class="text-indigo-700 font-bold uppercase text-[10px] bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 inline-block mt-0.5">
                        {{ $servis->booking->kategori_servis }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Metode Pengembalian Unit</span>
                    <span class="text-gray-700 font-medium capitalize">{{ $servis->booking->metode_pengembalian }}</span>
                </div>
            </div>

            {{-- 🆕 TAMBAHAN FIELD BARU DARI BOOKING (Fisik Perangkat & Keluhan Lengkap) --}}
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block mb-1">Kelengkapan Unit Bawaan:</span>
                    <div class="bg-white border rounded p-2 text-gray-700 font-medium">
                        {{ $servis->booking->kelengkapan ?? 'Tidak ada kelengkapan tambahan.' }}
                    </div>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Spesifikasi Detail Perangkat:</span>
                    <div class="bg-white border rounded p-2 text-gray-600">
                        {{ $servis->booking->spesifikasi ?? 'Tidak ada detail spesifikasi.' }}
                    </div>
                </div>
                <div>
                    <span class="text-red-400 font-bold block mb-1">Keluhan / Deskripsi Masalah Pelanggan:</span>
                    <div class="bg-red-50/40 border border-red-100 rounded p-2 text-red-900 font-medium">
                        {{ $servis->keluhan ?? $servis->booking->keluhan ?? 'Tidak ada catatan keluhan khusus.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 2: TABEL RINCIAN NOTA (JASA & SPAREPART) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Tindakan Jasa & Penggunaan Suku Cadang</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Deskripsi Tindakan / Sparepart</th>
                            <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- LOOP DATA JASA --}}
                        @if(isset($servis->detailJasa) && $servis->detailJasa->count() > 0)
                            @foreach($servis->detailJasa as $j)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded mr-1 uppercase">Jasa</span>
                                        {{ $j->jasa->nama_jasa }}
                                    </td>
                                    <td class="px-4 py-2 text-center">1</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($j->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($j->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- LOOP DATA SPAREPART --}}
                        @if(isset($servis->detailSparepart) && $servis->detailSparepart->count() > 0)
                            @foreach($servis->detailSparepart as $sp)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                        {{ $sp->sparepart->nama_sparepart }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $sp->qty }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($sp->sparepart->harga_jual ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($sp->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- JIKA KOSONG --}}
                        @if((!isset($servis->detailJasa) || $servis->detailJasa->count() == 0) && (!isset($servis->detailSparepart) || $servis->detailSparepart->count() == 0))
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/50">
                                    Belum ada rincian tindakan jasa atau sparepart yang diinput.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- FOOTER TOTAL --}}
            <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Total Akumulasi Biaya:</span>
                <strong class="text-xl font-bold text-blue-600">
                    Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                </strong>
            </div>
        </div>

        {{-- BLOK 3: CATATAN TEKNISI --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Riwayat Catatan Pengerjaan</h3>
            </div>
            <div class="p-4 text-xs bg-gray-50/30">
                <span class="text-gray-400 block mb-1">Catatan internal Anda untuk unit ini:</span>
                <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic min-h-[60px]">
                    "{{ $penugasan->catatan_teknisi ?? 'Anda belum memberikan catatan pengerjaan.' }}"
                </div>
            </div>
        </div>
    </div>
@endsection