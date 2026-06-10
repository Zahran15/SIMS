@extends('layouts.layout')

@section('title', 'Nota Digital Servis')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3 print:hidden">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Nota Digital Servis</h2>
            <p class="text-gray-500 text-xs">Rincian resmi invoice penanganan dan perbaikan perangkat pelanggan</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('pelanggan.riwayat_servis.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN (INVOICE CONTAINER) --}}
    <div class="space-y-4 bg-white rounded border border-gray-200 shadow-sm p-5 print:border-none print:shadow-none print:p-0">
        
        {{-- BLOK HEADER NOTA --}}
        <div class="flex flex-col sm:flex-row sm:justify-between gap-2 border-b-2 border-gray-800 pb-3">
            <div>
                <span class="text-xs uppercase font-bold tracking-wider text-gray-400 block">Invoice / Nota Transaksi</span>
                <strong class="text-xl font-black text-gray-900 tracking-wide">{{ $riwayat->kode_servis }}</strong>
            </div>
            <div class="sm:text-right">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-green-100 text-green-800 border border-green-200 uppercase inline-block">
                    Lunas / Selesai
                </span>
                <p class="text-xs text-gray-500 mt-1">Selesai: {{ \Carbon\Carbon::parse($riwayat->tgl_selesai ?? $riwayat->updated_at)->translatedFormat('d M Y') }}</p>
            </div>
        </div>

        {{-- BLOK INFORMASI PIHAK & PERANGKAT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs border-b border-gray-100 pb-4">
            <div>
                <h4 class="font-bold text-gray-400 uppercase tracking-wide mb-1.5">Pelanggan / Pemilik:</h4>
                <div class="space-y-0.5 text-gray-800">
                    <p class="font-bold text-sm">{{ $riwayat->booking->pelanggan->nama }}</p>
                    <p>No. HP: <span class="font-medium text-gray-700">{{ $riwayat->booking->pelanggan->no_hp }}</span></p>
                    <p>Email: <span class="text-gray-600">{{ $riwayat->booking->pelanggan->email }}</span></p>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-gray-400 uppercase tracking-wide mb-1.5">Identitas Unit Perangkat:</h4>
                <div class="space-y-1 text-gray-800">
                    <p>Perangkat: <span class="font-semibold">{{ $riwayat->booking->merk_tipe }}</span></p>
                    <p>Spesifikasi: <span class="text-gray-600">{{ $riwayat->booking->spesifikasi ?? '-' }}</span></p>
                    <p>Ref. Booking: <span class="font-mono font-semibold text-gray-600">{{ $riwayat->booking->kode_booking }}</span></p>
                </div>
            </div>
        </div>

        {{-- BLOK RIWAYAT KELUHAN & KELENGKAPAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs p-3 bg-gray-50 rounded border border-gray-100">
            <div>
                <span class="text-gray-400 font-bold uppercase block mb-0.5">Keluhan Awal:</span>
                <p class="text-gray-700 italic">"{{ $riwayat->booking->keluhan }}"</p>
            </div>
            <div>
                <span class="text-gray-400 font-bold uppercase block mb-0.5">Kelengkapan Unit Bawaan:</span>
                <p class="text-gray-700 font-medium">{{ $riwayat->booking->kelengkapan ?? '-' }}</p>
            </div>
        </div>

        {{-- BLOK TABEL RINCIAN NOTA GABUNGAN (SIMETRIS) --}}
        <div class="border border-gray-200 rounded overflow-hidden">
            <table class="w-full text-xs text-left text-gray-600 table-fixed">
                <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                    <tr>
                        <th class="w-1/2 px-4 py-2">Item Deskripsi Penanganan & Komponen</th>
                        <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                        <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                        <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- DATA JASA --}}
                    @if($riwayat->detailJasa && $riwayat->detailJasa->count() > 0)
                        @foreach ($riwayat->detailJasa as $jasa)
                            <tr>
                                <td class="px-4 py-2 text-gray-800 font-medium">
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded mr-1 uppercase">Jasa</span>
                                    {{ $jasa->jasa->nama_jasa }}
                                </td>
                                <td class="px-4 py-2 text-center">1</td>
                                {{-- FIX: Menggunakan $jasa->harga dan $jasa->subtotal --}}
                                <td class="px-4 py-2 text-right">Rp {{ number_format($jasa->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
        
                    {{-- DATA SPAREPART --}}
                    @if($riwayat->detailSparepart && $riwayat->detailSparepart->count() > 0)
                        @foreach ($riwayat->detailSparepart as $sp)
                            <tr>
                                <td class="px-4 py-2 text-gray-800 font-medium">
                                    <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                    {{ $sp->sparepart->nama_sparepart }}
                                </td>
                                {{-- FIX: Menggunakan $sp->qty --}}
                                <td class="px-4 py-2 text-center">{{ $sp->qty }}</td>
                                {{-- FIX: Menggunakan $sp->harga dan $sp->subtotal --}}
                                <td class="px-4 py-2 text-right">Rp {{ number_format($sp->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($sp->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
        
                    {{-- JIKA KOSONG --}}
                    @if(($riwayat->detailJasa ? $riwayat->detailJasa->count() : 0) == 0 && ($riwayat->detailSparepart ? $riwayat->detailSparepart->count() : 0) == 0)
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/50">
                                Tidak ada komponen pengeluaran biaya servis.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        
            {{-- AKUMULASI BIAYA BAWAH --}}
            <div class="bg-gray-50 border-t border-gray-200 p-3 flex justify-end">
                <div class="w-full sm:w-64 space-y-1.5 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Total Jasa:</span>
                        {{-- FIX: Menggunakan sum('subtotal') --}}
                        <span class="font-medium text-gray-800">Rp {{ number_format($riwayat->detailJasa->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Total Suku Cadang:</span>
                        {{-- FIX: Menggunakan sum('subtotal') --}}
                        <span class="font-medium text-gray-800">Rp {{ number_format($riwayat->detailSparepart->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-1.5 border-t border-gray-200">
                        <span>Total Pembayaran:</span>
                        <span class="text-base text-blue-600 font-extrabold">Rp {{ number_format($riwayat->total_biaya, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection