@extends('layouts.layout')

@section('title', 'Detail Riwayat Pekerjaan')

@section('content')
<div class="p-4">

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Riwayat Pekerjaan</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap dokumentasi servis perangkat yang telah selesai dikerjakan</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('teknisi.riwayat_pekerjaan.index') }}" 
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: DATA UTAMA SERVIS --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Perangkat & Pelanggan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $riwayat->servis->kode_servis }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $riwayat->servis->booking->pelanggan->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Merk / Tipe Device</span>
                    <span class="text-gray-800 font-semibold">{{ $riwayat->servis->booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Masuk</span>
                    <span class="text-gray-800 font-semibold">{{ date('d M Y', strtotime($riwayat->servis->tgl_masuk)) }}</span>
                </div>
            </div>
        </div>

        {{-- BLOK 2: DATA RINGKASAN STATUS PENUGASAN --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Status Akhir Pengerjaan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block mb-1">Tingkat Prioritas</span>
                    <span class="px-2 py-0.5 rounded font-bold border bg-gray-100 text-gray-800 border-gray-200 uppercase">
                        {{ $riwayat->prioritas }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Status Penugasan</span>
                    <span class="px-2 py-0.5 rounded font-bold border bg-green-100 text-green-800 border-green-200 uppercase">
                        {{ $riwayat->status_penugasan }}
                    </span>
                </div>
            </div>
        </div>

        {{-- BLOK 3: RINCIAN NOTA BIAYA (TABEL GABUNGAN) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Tindakan Jasa & Suku Cadang</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Item Pekerjaan / Komponen</th>
                            <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- DATA JASA --}}
                        @if($riwayat->servis->detailJasa && $riwayat->servis->detailJasa->count() > 0)
                            @foreach($riwayat->servis->detailJasa as $jasa)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded mr-1 uppercase">Jasa</span>
                                        {{ $jasa->jasa->nama_jasa }}
                                    </td>
                                    <td class="px-4 py-2 text-center">1</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- DATA SPAREPART --}}
                        @if($riwayat->servis->detailSparepart && $riwayat->servis->detailSparepart->count() > 0)
                            @foreach($riwayat->servis->detailSparepart as $sp)
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
                        @if(($riwayat->servis->detailJasa ? $riwayat->servis->detailJasa->count() : 0) == 0 && ($riwayat->servis->detailSparepart ? $riwayat->servis->detailSparepart->count() : 0) == 0)
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/50">
                                    Tidak ada rincian jasa ataupun penggunaan sparepart pada riwayat ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- TOTAL AKHIR BIAYA --}}
            <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Total Biaya Keseluruhan:</span>
                <strong class="text-xl font-bold text-green-600">
                    Rp {{ number_format($riwayat->servis->total_biaya, 0, ',', '.') }}
                </strong>
            </div>
        </div>

        {{-- BLOK 4: CATATAN DOKUMENTASI TEKNISI --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Arsip Catatan Teknisi</h3>
            </div>
            <div class="p-4 text-xs bg-gray-50/30">
                <span class="text-gray-400 block mb-1">Catatan penyelesaian pekerjaan yang Anda input sebelumnya:</span>
                <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic min-h-[60px]">
                    "{{ $riwayat->catatan_teknisi ?? 'Tidak ada catatan pengerjaan yang tersimpan.' }}"
                </div>
            </div>
        </div>

    </div>
</div>
@endsection