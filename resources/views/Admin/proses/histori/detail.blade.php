@extends('layouts.layout')

@section('title', 'Detail Histori Aktivitas')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Log Histori</h2>
            <p class="text-gray-500 text-xs">Mencatat jejak perubahan sistem secara realtime (ID Log: #{{ $histori->id_histori }})</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.histori.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: STATUS & INFORMASI LOG --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Log Aktivitas</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Waktu Log</span>
                    <strong class="text-gray-800 text-sm">{{ date('d M Y', strtotime($histori->tanggal)) }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Operator / Aktor</span>
                    <span class="text-blue-600 font-semibold text-sm">{{ $histori->user->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Role Operator</span>
                    <span class="text-gray-800 font-semibold">{{ $histori->user->role ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Aktivitas Terdeteksi</span>
                    <div class="mt-0.5">
                        @if($histori->aktivitas == 'Penerimaan Unit')
                            <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-800 font-bold border border-purple-200 uppercase">{{ $histori->aktivitas }}</span>
                        @elseif(str_contains($histori->aktivitas, 'Selesai') || str_contains($histori->aktivitas, 'Ambil'))
                            <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 font-bold border border-green-200 uppercase">{{ $histori->aktivitas }}</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800 font-bold border border-blue-200 uppercase">{{ $histori->aktivitas }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-4 pb-4 text-xs">
                <span class="text-gray-400 block mb-1">Keterangan Catatan Sistem:</span>
                <div class="bg-gray-50 border border-gray-200 rounded p-3 text-gray-700 italic">
                    "{{ $histori->keterangan ?? 'Tidak ada catatan tambahan.' }}"
                </div>
            </div>
        </div>

        {{-- BLOK 2: DATA PELANGGAN & PERANGKAT --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Pelanggan & Perangkat</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $histori->servis->kode_servis ?? '-' }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $histori->servis->booking->pelanggan->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Merk / Tipe Unit</span>
                    <span class="text-gray-800 font-semibold">{{ $histori->servis->booking->merk_tipe ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Kategori Servis</span>
                    <span class="text-gray-800 font-bold capitalize">{{ $histori->servis->booking->kategori_servis ?? '-' }}</span>
                </div>
            </div>

            <div class="px-4 pb-4 text-xs">
                <span class="text-gray-400 block mb-1">Keluhan Awal Pelanggan:</span>
                <div class="bg-gray-50 border border-gray-200 rounded p-3 text-gray-700">
                    {{ $histori->servis->booking->keluhan ?? '-' }}
                </div>
            </div>
        </div>

        {{-- BLOK 3: TABEL RINCIAN NOTA (SNAPSHOT) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Tindakan & Suku Cadang (Snapshot Saat Ini)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Deskripsi Tindakan / Suku Cadang</th>
                            <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- LOOP DATA JASA --}}
                        @if(isset($histori->servis->detailJasa) && $histori->servis->detailJasa->count() > 0)
                            @foreach($histori->servis->detailJasa as $jasa)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded mr-1 uppercase">Jasa</span>
                                        {{ $jasa->jasa->nama_jasa ?? 'Nama Jasa Terhapus' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">1</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($jasa->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- LOOP DATA SPAREPART --}}
                        @if(isset($histori->servis->detailSparepart) && $histori->servis->detailSparepart->count() > 0)
                            @foreach($histori->servis->detailSparepart as $sparepart)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                        {{ $sparepart->sparepart->nama_sparepart ?? 'Sparepart Terhapus' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $sparepart->qty }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($sparepart->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($sparepart->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- KOSONG --}}
                        @if((!isset($histori->servis->detailJasa) || $histori->servis->detailJasa->count() == 0) && (!isset($histori->servis->detailSparepart) || $histori->servis->detailSparepart->count() == 0))
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/50">
                                    Tidak ada data jasa atau sparepart pada nota ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- FOOTER TOTAL --}}
            <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Total Biaya Akumulasi:</span>
                <strong class="text-xl font-bold text-blue-600">
                    Rp {{ number_format($histori->servis->total_biaya ?? 0, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>
@endsection