@extends('layouts.layout')

@section('title', 'Detail Laporan Pengadaan')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Nota Pengadaan</h2>
            <p class="text-gray-500 text-xs">Laporan internal pembiayaan asset inventori gudang (ID Register: #PGD-{{ str_pad($data->id_pengadaan, 5, '0', STR_PAD_LEFT) }})</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pengadaan_sparepart.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: STATUS & INFORMASI NOTA --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Transaksi Pemesanan</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Tanggal Transaksi Masuk</span>
                    <strong class="text-gray-800 text-sm">{{ \Carbon\Carbon::parse($data->tgl_pesan)->translatedFormat('d M Y') }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">ID / Nomor Register</span>
                    <span class="text-gray-800 font-semibold">#PGD-{{ str_pad($data->id_pengadaan, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Pengadaan</span>
                    <div class="mt-0.5">
                        @if($data->status_pengadaan == 'dipesan')
                            <span class="px-2 py-0.5 rounded text-xs bg-yellow-100 text-yellow-800 font-bold border border-yellow-200 uppercase">{{ $data->status_pengadaan }}</span>
                        @elseif($data->status_pengadaan == 'diterima')
                            <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 font-bold border border-green-200 uppercase">{{ $data->status_pengadaan }}</span>
                        @elseif($data->status_pengadaan == 'diajukan')
                            <span class="px-2 py-0.5 rounded text-xs bg-yellow-100 text-yellow-800 font-bold border border-yellow-200 uppercase">{{ $data->status_pengadaan }}</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800 font-bold border border-red-200 uppercase">{{ $data->status_pengadaan }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 2: TABEL RINCIAN PENGADAAN BARANG --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Item Suku Cadang</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Nama Suku Cadang</th>
                            <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Beli Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-gray-800 font-medium">
                                <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                {{ $data->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                            </td>
                            <td class="px-4 py-2 text-center text-gray-800">{{ $data->jumlah }}</td>
                            <td class="px-4 py-2 text-right text-gray-800">Rp {{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($data->total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- FOOTER TOTAL --}}
            <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Total Arus Kas Keluar (Modal):</span>
                <strong class="text-xl font-bold text-green-600">
                    Rp {{ number_format($data->total, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>
@endsection