@extends('layouts.layout')

@section('title', 'Detail Request Sparepart')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Request Sparepart</h2>
            <p class="text-gray-500 text-xs">Informasi pengelolaan data dan alur persetujuan komponen sparepart.</p>
        </div>
        
        <div class="flex items-center gap-2 flex-wrap">
            {{-- 1. JIKA STATUS PENDING ADMIN: TOMBOL KIRIM KE PELANGGAN --}}
            @if($requestSparepart->status_request == 'pending_admin')
                <form id="form-kirim-{{ $requestSparepart->id_request }}" action="{{ route('admin.request_sparepart.kirim_pelanggan', $requestSparepart->id_request) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" 
                            class="px-3 py-1.5 rounded bg-orange-600 hover:bg-orange-700 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1"
                            onclick="confirmKirimPelanggan('{{ $requestSparepart->id_request }}')">
                        Kirim ke Pelanggan
                    </button>
                </form>
            @endif

            {{-- 2. JIKA STATUS ACC PELANGGAN: TOMBOL SETUJUI AKHIR --}}
            @if($requestSparepart->status_request == 'disetujui_pelanggan')
                <form id="form-approve-final-{{ $requestSparepart->id_request }}" action="{{ route('admin.request_sparepart.approve_final', $requestSparepart->id_request) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" 
                            class="px-3 py-1.5 rounded bg-green-600 hover:bg-green-700 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1"
                            onclick="confirmApproveFinal('{{ $requestSparepart->id_request }}')">
                        Setujui & Potong Stok
                    </button>
                </form>
            @endif

            {{-- TOMBOL KEMBALI --}}
            <a href="{{ route('admin.request_sparepart.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1">
                Kembali
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI SUKSES / GAGAL --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded text-xs bg-green-100 text-green-700 font-medium border border-green-200 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded text-xs bg-red-100 text-red-700 font-medium border border-red-200 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: INFORMASI UTAMA, SERVIS & STATUS --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Tugas & Status Request</h3>
                
                {{-- BADGE STATUS REQUEST SPAREPART --}}
                <div>
                    @if($requestSparepart->status_request == 'pending_admin')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-yellow-100 text-yellow-700 border border-yellow-200">Pending Admin</span>
                    @elseif($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">Di Pelanggan</span>
                    @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-blue-100 text-blue-700 border border-blue-200">ACC Pelanggan</span>
                    @elseif($requestSparepart->status_request == 'disetujui')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-green-100 text-green-700 border border-green-200">Request Disetujui</span>
                    @else
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-red-100 text-red-700 border border-red-200">Request Ditolak</span>
                    @endif
                </div>
            </div>

            {{-- Info Utama Grid (Baris 1) --}}
            <div class="p-4 grid grid-cols-2 md:grid-cols-5 gap-4 text-xs border-b border-gray-100">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $requestSparepart->penugasan->servis->kode_servis ?? '-' }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $requestSparepart->penugasan->servis->booking->pelanggan->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Device</span>
                    <span class="text-gray-800 font-semibold">{{ $requestSparepart->penugasan->servis->booking->merk_tipe ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Penugasan</span>
                    <span class="text-blue-600 font-bold uppercase tracking-wide mt-0.5 inline-block">
                        {{ $requestSparepart->penugasan->status_penugasan ?? '-' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status Servis</span>
                    <span class="text-indigo-600 font-bold uppercase tracking-wide mt-0.5 inline-block">
                        {{ $requestSparepart->penugasan->servis->status_servis ?? '-' }}
                    </span>
                </div>
            </div>

            {{-- Info Administratif, Kontak & Keluhan (Baris 2) --}}
            <div class="px-4 py-3 bg-gray-50/50 grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="space-y-2">
                    <div>
                        <span class="text-gray-400 block">Kategori Servis</span>
                        <span class="text-indigo-700 font-bold uppercase text-[10px] bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 inline-block mt-0.5">
                            {{ $requestSparepart->penugasan->servis->booking->kategori_servis ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Kontak Pelanggan</span>
                        <span class="text-gray-700 block font-medium">{{ $requestSparepart->penugasan->servis->booking->pelanggan->email ?? '-' }}</span>
                        <span class="text-blue-600 font-semibold block">{{ $requestSparepart->penugasan->servis->booking->pelanggan->no_hp ?? '-' }}</span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <span class="text-red-400 font-bold block mb-1">Keluhan / Deskripsi Masalah Pelanggan:</span>
                    <div class="bg-red-50/40 border border-red-100 rounded p-2 text-red-900 font-medium">
                        {{ $requestSparepart->penugasan->servis->keluhan ?? $requestSparepart->penugasan->servis->booking->keluhan ?? 'Tidak ada catatan keluhan khusus.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOK 2: TABEL RINCIAN KOMPONEN & FINANCIAL VALIDASI (UPDATE: TAMBAH HARGA) --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Detail Komponen & Informasi Harga</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-4/12 px-4 py-2">Nama Sparepart / Komponen</th>
                            <th class="w-2/12 px-4 py-2">Stok Gudang</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-gray-800 font-medium">
                                <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                {{ $requestSparepart->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                                <span class="block text-[10px] text-gray-400 font-normal mt-0.5">Kategori: {{ $requestSparepart->sparepart->kategori ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-2 font-bold">
                                <span class="{{ ($requestSparepart->sparepart->stok ?? 0) < $requestSparepart->jumlah ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50' }} px-2 py-0.5 rounded border text-[11px]">
                                    {{ $requestSparepart->sparepart->stok ?? 0 }} Pcs
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right text-gray-700 font-medium">
                                Rp {{ number_format($requestSparepart->sparepart->harga_jual ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-center font-bold text-gray-800">
                                {{ $requestSparepart->jumlah }} Pcs
                            </td>
                            <td class="px-4 py-2 text-right font-bold text-blue-600 bg-blue-50/20">
                                Rp {{ number_format(($requestSparepart->sparepart->harga_jual ?? 0) * $requestSparepart->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BLOK 3: ALASAN PERMINTAAN & LOG AKTIVITAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            {{-- Alasan Permintaan Dari Teknisi --}}
            <div class="md:col-span-2 bg-white rounded border border-gray-200 shadow-sm">
                <div class="px-4 py-2 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Alasan Permintaan Barang</h3>
                </div>
                <div class="p-4 text-xs bg-gray-50/30">
                    <span class="text-gray-400 block mb-1">Catatan keperluan teknisi:</span>
                    <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic min-h-[60px]">
                        "{{ $requestSparepart->alasan }}"
                    </div>
                </div>
            </div>

            {{-- Log Aktivitas / Timeline mini --}}
            <div class="bg-white rounded border border-gray-200 shadow-sm">
                <div class="px-4 py-2 border-b bg-gray-50">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Log Aktivitas Request</h3>
                </div>
                <div class="p-4 text-xs space-y-3">
                    <div class="flex gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-1 ring-2 ring-blue-100 shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-800">Diajukan oleh Teknisi</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $requestSparepart->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2 border-t pt-2">
                        @if($requestSparepart->status_request == 'pending_admin')
                            <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 mt-1 ring-2 ring-yellow-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-gray-800">Menunggu Validasi Admin</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Review internal pengadaan gudang</p>
                            </div>
                        @elseif($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                            <div class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-1 ring-2 ring-orange-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-gray-800">Menunggu Respon Pelanggan</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Diteruskan untuk persetujuan biaya</p>
                            </div>
                        @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1 ring-2 ring-blue-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-gray-800">Telah Di-ACC Pelanggan</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Menunggu verifikasi & potong stok admin</p>
                            </div>
                        @elseif($requestSparepart->status_request == 'disetujui')
                            <div class="w-1.5 h-1.5 rounded-full bg-green-600 mt-1 ring-2 ring-green-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-green-700">Request Disetujui Penuh</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Selesai pada {{ $requestSparepart->updated_at->translatedFormat('d F Y') }}</p>
                            </div>
                        @else
                            <div class="w-1.5 h-1.5 rounded-full bg-red-600 mt-1 ring-2 ring-red-100 shrink-0"></div>
                            <div>
                                <p class="font-semibold text-red-600">Request Ditolak / Dibatalkan</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Diputus pada {{ $requestSparepart->updated_at->translatedFormat('d F Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tambahan library dan script konfirmasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Konfirmasi Kirim ke Pelanggan
        function confirmKirimPelanggan(id) {
            Swal.fire({
                title: 'Kirim ke Pelanggan?',
                text: "Permintaan sparepart akan diteruskan ke pelanggan untuk mendapatkan persetujuan biaya.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ea580c', // Orange
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-kirim-' + id).submit();
                }
            });
        }

        // Konfirmasi Setuju Akhir (Potong Stok)
        function confirmApproveFinal(id) {
            Swal.fire({
                title: 'Setujui Permintaan Akhir?',
                text: "Sistem akan menyetujui permintaan ini dan memotong stok gudang secara otomatis.",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // Hijau
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui & Potong Stok',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-approve-final-' + id).submit();
                }
            });
        }
    </script>
@endsection