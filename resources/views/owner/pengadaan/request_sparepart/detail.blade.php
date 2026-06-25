@extends('layouts.layout')

@section('title', 'Detail Laporan Request Sparepart')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Laporan Penggunaan</h2>
            <p class="text-gray-500 text-xs">Rincian data pengajuan komponen dan riwayat validasi sistem (ID Request: #{{ $requestSparepart->id_request ?? 'Sistem' }})</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('owner.request_sparepart.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: STATUS & INFORMASI UTAMA REQUEST --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Pengajuan & Tugas</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Servis / Nota</span>
                    <strong class="text-blue-600 text-sm">{{ $requestSparepart->penugasan->servis->kode_servis ?? '-' }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $requestSparepart->penugasan->servis->booking->pelanggan->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Sisa Stok Gudang</span>
                    <span class="text-gray-800 font-semibold">{{ $requestSparepart->sparepart->stok ?? 0 }} Pcs</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Request</span>
                    <div class="mt-0.5">
                        @if($requestSparepart->status_request == 'pending_admin')
                            <span class="px-2 py-0.5 rounded text-xs bg-yellow-100 text-yellow-800 font-bold border border-yellow-200 uppercase">Pending Admin</span>
                        @elseif($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                            <span class="px-2 py-0.5 rounded text-xs bg-orange-100 text-orange-800 font-bold border border-orange-200 uppercase">Di Pelanggan</span>
                        @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                            <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800 font-bold border border-blue-200 uppercase">ACC Pelanggan</span>
                        @elseif($requestSparepart->status_request == 'disetujui')
                            <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 font-bold border border-green-200 uppercase">Disetujui</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800 font-bold border border-red-200 uppercase">Ditolak</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-4 pb-4 text-xs">
                <span class="text-gray-400 block mb-1">Justifikasi / Keterangan Teknisi:</span>
                <div class="bg-gray-50 border border-gray-200 rounded p-3 text-gray-700 italic">
                    "{{ $requestSparepart->alasan ?? 'Tidak ada alasan tambahan.' }}"
                </div>
            </div>
        </div>

        {{-- BLOK 2: TABEL RINCIAN ITEM YANG DIMINTA --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Komponen Suku Cadang Diambil</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-3/4 px-4 py-2">Deskripsi Komponen</th>
                            <th class="w-1/4 px-4 py-2 text-center">Jumlah Diambil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-gray-800 font-medium">
                                <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                {{ $requestSparepart->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                            </td>
                            <td class="px-4 py-2 text-center font-bold text-gray-800">{{ $requestSparepart->jumlah }} Pcs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BLOK 3: AUDIT SYSTEM TRAIL (TIMELINE VERIFIKASI BERTINGKAT) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Audit System Trail</h3>
            </div>
            
            <div class="p-4 space-y-4 text-xs">
                {{-- TAHAP 1: PENGAJUAN TEKNISI --}}
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1 ring-4 ring-blue-100 shrink-0"></div>
                    <div>
                        <span class="text-gray-400 block text-[10px]">TAHAP 1: PENGAJUAN TEKNISI</span>
                        <strong class="text-gray-800">Sistem merekam permintaan komponen oleh teknisi</strong>
                        <p class="text-gray-500 mt-0.5">{{ $requestSparepart->created_at->translatedFormat('d M Y - H:i') }} WIB</p>
                    </div>
                </div>

                {{-- TAHAP 2: VALIDASI ADMIN KE PELANGGAN --}}
                <div class="flex items-start gap-3 border-t pt-3">
                    @if($requestSparepart->status_request != 'pending_admin')
                        <div class="w-2 h-2 rounded-full bg-orange-500 mt-1 ring-4 ring-orange-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 2: VALIDASI HARGA & BIAYA</span>
                            <strong class="text-gray-800">Admin meneruskan rincian komponen ke halaman persetujuan pelanggan</strong>
                        </div>
                    @else
                        <div class="w-2 h-2 rounded-full bg-gray-300 mt-1 ring-4 ring-gray-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 2: VALIDASI HARGA & BIAYA</span>
                            <strong class="text-gray-400 italic">Menunggu review Admin sebelum dilempar ke pelanggan</strong>
                        </div>
                    @endif
                </div>

                {{-- TAHAP 3: KEPUTUSAN PELANGGAN --}}
                <div class="flex items-start gap-3 border-t pt-3">
                    @if(in_array($requestSparepart->status_request, ['disetujui_pelanggan', 'disetujui']))
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-1 ring-4 ring-blue-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 3: KONFIRMASI KONSUMEN</span>
                            <strong class="text-gray-800">Pelanggan memberikan persetujuan resmi (ACC) pergantian suku cadang</strong>
                        </div>
                    @elseif($requestSparepart->status_request == 'ditolak')
                        <div class="w-2 h-2 rounded-full bg-red-500 mt-1 ring-4 ring-red-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 3: KONFIRMASI KONSUMEN / INTERNAL</span>
                            <strong class="text-red-600">Request dibatalkan / ditolak di dalam sistem</strong>
                            <p class="text-gray-500 mt-0.5">{{ $requestSparepart->updated_at->translatedFormat('d M Y - H:i') }} WIB</p>
                        </div>
                    @else
                        <div class="w-2 h-2 rounded-full bg-gray-300 mt-1 ring-4 ring-gray-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 3: KONFIRMASI KONSUMEN</span>
                            <strong class="text-gray-400 italic">Menunggu keputusan/tindakan dari pelanggan</strong>
                        </div>
                    @endif
                </div>

                {{-- TAHAP 4: VERIFIKASI AKHIR ADMIN --}}
                <div class="flex items-start gap-3 border-t pt-3">
                    @if($requestSparepart->status_request == 'disetujui')
                        <div class="w-2 h-2 rounded-full bg-green-500 mt-1 ring-4 ring-green-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 4: VERIFIKASI FINAL GUDANG</span>
                            <strong class="text-green-700">Admin memberikan validasi akhir. Suku cadang keluar & stok gudang resmi dipotong</strong>
                            <p class="text-gray-500 mt-0.5">{{ $requestSparepart->updated_at->translatedFormat('d M Y - H:i') }} WIB</p>
                        </div>
                    @else
                        <div class="w-2 h-2 rounded-full bg-gray-300 mt-1 ring-4 ring-gray-100 shrink-0"></div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">TAHAP 4: VERIFIKASI FINAL GUDANG</span>
                            <strong class="text-gray-400 italic">Belum diserahkan / menanti penyelesaian transaksi bertingkat</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 px-4 py-2.5 border-t border-gray-200 text-center text-[11px] text-gray-400 italic">
                Data ini bersifat permanen untuk keperluan pelaporan stok berkala Owner.
            </div>
        </div>

    </div>
@endsection