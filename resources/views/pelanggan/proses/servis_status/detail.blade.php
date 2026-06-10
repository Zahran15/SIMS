@extends('layouts.layout')

@section('title', 'Detail Status Servis')

@section('content')

<div class="p-4">

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Progres Servis</h2>
            <p class="text-gray-500 text-xs">Pantau status pengerjaan unit perangkat Anda secara real-time</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('pelanggan.servis_status.index') }}"
                class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

    {{-- BLOK 1: DATA UTAMA SERVIS --}}
    <div class="bg-white rounded border border-gray-200 shadow-sm">
        <div class="px-4 py-2 border-b bg-gray-50">
            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Data Registrasi Servis</h3>
        </div>

        <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-gray-400 block">Kode Servis</span>
                <strong class="text-blue-600 text-sm">{{ $servis->kode_servis }}</strong>
            </div>
            <div>
                <span class="text-gray-400 block">Tanggal Masuk</span>
                <span class="text-gray-800 font-semibold">
                    {{ $servis->tgl_masuk ? \Carbon\Carbon::parse($servis->tgl_masuk)->translatedFormat('d F Y') : '-' }}
                </span>
            </div>
            <div>
                <span class="text-gray-400 block">Merk / Tipe Device</span>
                <span class="text-gray-800 font-semibold">{{ $servis->booking->merk_tipe ?? '-' }}</span>
            </div>
            <div>
                <span class="text-gray-400 block">Estimasi Selesai</span>
                <span class="text-gray-800 font-semibold">
                    {{ $servis->perkiraan_selesai ? \Carbon\Carbon::parse($servis->perkiraan_selesai)->translatedFormat('d F Y') : '-' }}
                </span>
            </div>
        </div>

        <div class="p-4 text-xs bg-gray-50/30 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="text-gray-400 block mb-1">Teknisi Penanggung Jawab:</span>
                <span class="text-gray-800 font-bold text-sm block">
                    {{-- Menggunakan relasi langsung dari $servis untuk keamanan --}}
                    {{ $servis->penugasan && $servis->penugasan->user ? $servis->penugasan->user->nama : 'Menunggu Antrean...' }}
                </span>
            </div>
            <div class="md:col-span-2">
                <span class="text-gray-400 block mb-1">Catatan / Progres Pembaruan Teknisi:</span>
                <div class="bg-white border border-gray-200 rounded p-2.5 text-gray-700 italic">
                    "{{ $servis->penugasan->catatan_teknisi ?? 'Belum ada catatan penanganan dari teknisi.' }}"
                </div>
            </div>
        </div>
    </div>

        {{-- BLOK 2: STATUS PENGERJAAN & TOKO --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Status Alur Kerja</h3>
            </div>

            <div class="p-4 grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block mb-1">Status Servis (Toko)</span>
                    <span class="px-2 py-0.5 rounded font-bold border uppercase inline-block
                        {{ $servis->status_servis == 'menunggu' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                        {{ $servis->status_servis == 'proses' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}
                        {{ $servis->status_servis == 'selesai' || $servis->status_servis == 'bisa diambil' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                        {{ $servis->status_servis == 'sudah diambil' ? 'bg-gray-100 text-gray-800 border-gray-200' : '' }}
                        {{ $servis->status_servis == 'dibatalkan' ? 'bg-red-100 text-red-800 border-red-200' : '' }}">
                        {{ $servis->status_servis }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Status Kerja Teknisi</span>
                    <span class="px-2 py-0.5 rounded font-bold border uppercase inline-block
                        @if($servis)
                            {{ $servis->status_penugasan == 'sedang dikerjakan' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}
                            {{ $servis->status_penugasan == 'menunggu sparepart' ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                            {{ $servis->status_penugasan == 'selesai' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                            {{ $servis->status_penugasan == 'gagal' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                            {{ $servis->status_penugasan == 'belum dikerjakan' ? 'bg-gray-100 text-gray-800 border-gray-200' : '' }}
                        @else
                            bg-gray-100 text-gray-800 border-gray-200
                        @endif">
                        {{ $servis ? $servis->status_penugasan : 'Belum Ditugaskan' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- BLOK 3: RINCIAN BIAYA (TABEL GABUNGAN SIMETRIS) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Tindakan Jasa & Suku Cadang</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Item Deskripsi Tindakan / Komponen</th>
                            <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- DATA JASA --}}
                        @if($servis->detailJasa && $servis->detailJasa->count() > 0)
                            @foreach($servis->detailJasa as $j)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded mr-1 uppercase">Jasa</span>
                                        {{ $j->jasa->nama_jasa ?? 'Jasa Perbaikan' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">1</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($j->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($j->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- DATA SPAREPART --}}
                        @if($servis->detailSparepart && $servis->detailSparepart->count() > 0)
                            @foreach($servis->detailSparepart as $sp)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                        {{ $sp->sparepart->nama_sparepart ?? 'Komponen' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $sp->qty }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($sp->sparepart->harga_jual ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($sp->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- JIKA KOSONG --}}
                        @if(($servis->detailJasa ? $servis->detailJasa->count() : 0) == 0 && ($servis->detailSparepart ? $servis->detailSparepart->count() : 0) == 0)
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/50">
                                    Belum ada rincian tindakan jasa ataupun suku cadang yang diinput oleh teknisi.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- TOTAL BIAYA AKHIR --}}
            <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Total Estimasi Biaya Perbaikan:</span>
                <strong class="text-xl font-bold text-indigo-600">
                    Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                </strong>
            </div>
        </div>

        {{-- ALERT DINAMIS BERDASARKAN STATUS TOKO --}}
        @if($servis->status_servis == 'proses')
            <div class="p-3 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800 flex items-start gap-2 shadow-sm">
                <span class="mt-0.5"></span>
                <div>
                    <strong class="block mb-0.5">Sedang Dalam Penanganan</strong>
                    <span>Perangkat Anda saat ini sedang ditangani secara intensif oleh tim teknisi kami. Item rincian biaya di atas akan terus diperbarui secara berkala mengikuti progres komponen yang diperbaiki.</span>
                </div>
            </div>
        @elseif($servis->status_servis == 'bisa diambil' || $servis->status_servis == 'selesai')
            <div class="p-3 bg-green-50 border border-green-200 rounded text-xs text-green-800 flex items-start gap-2 shadow-sm">
                <span class="mt-0.5"></span>
                <div>
                    <strong class="block mb-0.5">Perbaikan Selesai & Siap Diambil</strong>
                    <span>Kabar baik! Proses perbaikan laptop/perangkat Anda telah selesai diuji coba. Silakan datang ke gerai toko Seven Komputer untuk melakukan pengetesan unit fisik dan pelunasan sisa pembayaran nota.</span>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection