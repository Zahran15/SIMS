@extends('layouts.layout')

@section('title', 'Tanda Terima Pengambilan - Arsip Toko')

@section('content')

<style>
    /* Reset & Optimasi Cetak Khusus A4 Landscape */
    @media print {
        /* 1. Paksa browser untuk langsung memilih kertas A4 posisi Landscape */
        @page {
            size: A4 landscape;
            margin: 6mm 10mm 6mm 10mm !important; /* Perkecil margin kertas atas-bawah */
        }

        /* 2. Sembunyikan elemen navigasi, tombol, sidebar, atau kelas .no-print */
        .no-print, header, footer, nav, aside, .sidebar, .navbar, button, a {
            display: none !important;
            height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* 3. Bersihkan wrapper utama template agar tidak mengunci lebar/tinggi */
        html, body, main, .main-content, .content-wrapper {
            background: #fff !important;
            color: #000 !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            box-shadow: none !important;
        }

        /* 4. Sesuaikan area nota agar pas dengan kertas landscape */
        .print-area {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            min-height: auto !important;
            height: auto !important;
            display: block !important;
        }

        /* Optimasi Spasi Komponen Khusus Cetak agar Muat 1 Halaman */
        .print-area .p-8 {
            padding: 1rem !important; /* Perkecil semua padding utama dari p-8 ke p-4 */
        }
        .print-area .mb-6 {
            margin-bottom: 0.75rem !important;
        }
        .print-area .mb-8 {
            margin-bottom: 0.5rem !important;
        }
        .print-area text-sm {
            font-size: 0.75rem !important;
        }

        /* Kembalikan fungsi Grid dan Flex milik Tailwind agar susunannya tidak hancur */
        .print-area .grid {
            display: grid !important;
        }
        .print-area .flex {
            display: flex !important;
        }
        .print-area table {
            display: table !important;
            width: 100% !important;
        }
        .print-area th, .print-area td {
            display: table-cell !important;
        }

        /* Memaksa background warna tetap tercetak */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

    {{-- Tombol Navigasi --}}
    <div class="mb-6 flex items-center justify-between no-print">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Bukti Penyerahan Unit (Arsip Admin)</h2>
            <p class="text-gray-500 mt-1">Cetak dokumen ini sebagai bukti otentik pelanggan telah mengambil unit dan melunasi biaya</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.servis_selesai.index') }}"
               class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                Kembali
            </a>

            <button onclick="window.print()"
                    class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm transition-all">
                Cetak Arsip Pengembalian
            </button>
        </div>
    </div>

    {{-- AREA NOTA --}}
    <div class="max-w-5xl mx-auto bg-white border border-gray-200 shadow-lg rounded-2xl overflow-hidden print-area print:text-xs">

        {{-- Header Nota --}}
        <div class="p-8 border-b border-dashed border-gray-300 bg-gray-50/50 print:py-4">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 tracking-wide print:text-xl">SEVEN COMPUTER</h1>
                    <p class="text-sm text-gray-500 mt-1 print:text-xs">Jasa Servis Laptop & Komputer</p>
                    <div class="mt-2 text-sm text-gray-600 leading-normal print:text-[11px]">
                        Jl. Pudang No.53, Pasiran, Tegalreja, Kec. Cilacap Sel.<br>
                        Telp: 0858-7900-0070
                    </div>
                </div>

                <div class="text-right">
                    <h2 class="text-2xl font-bold uppercase text-emerald-600 print:text-lg">Bukti Pengembalian Unit</h2>
                    <p class="mt-1 text-sm text-gray-500 print:text-xs">Kode Servis: <span class="font-bold text-gray-800">{{ $servis->kode_servis }}</span></p>
                </div>
            </div>
        </div>

        {{-- Konten Utama Nota --}}
        <div class="p-8 print:py-3">
            {{-- Bagian Info Pelanggan & Servis --}}
            <div class="grid grid-cols-2 gap-10 mb-4 print:gap-6 print:mb-3">
                <div>
                    <h3 class="text-xs font-bold uppercase text-gray-400 mb-2 tracking-wider print:text-[10px]">Identitas Pengambil</h3>
                    <table class="w-full text-sm print:text-xs">
                        <tr>
                            <td class="py-1 w-32 font-semibold text-gray-600">Nama Pelanggan</td>
                            <td class="text-gray-900">: {{ $servis->booking->pelanggan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-semibold text-gray-600">No HP</td>
                            <td class="text-gray-900">: {{ $servis->booking->pelanggan->no_hp }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-semibold text-gray-600">Unit Perangkat</td>
                            <td class="text-gray-900">: {{ $servis->booking->merk_tipe }}</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase text-gray-400 mb-2 tracking-wider print:text-[10px]">Riwayat Waktu</h3>
                    <table class="w-full text-sm print:text-xs">
                        <tr>
                            <td class="py-1 w-32 font-semibold text-gray-600">Tanggal Masuk</td>
                            <td class="text-gray-900">: {{ date('d M Y', strtotime($servis->tgl_masuk)) }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-semibold text-gray-600">Tanggal Ambil</td>
                            <td class="text-gray-900">: {{ date('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-semibold text-gray-600">Status Servis</td>
                            <td class="uppercase font-bold text-emerald-600">: {{ $servis->status_servis }} (DIAMBIL)</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Detail Tabel Rincian Biaya & Komponen --}}
            <div class="overflow-hidden border border-gray-200 rounded-xl mb-4 print:mb-3">
                <table class="w-full text-sm text-left print:text-xs">
                    <thead class="bg-gray-800 text-white uppercase text-xs print:text-[10px]">
                        <tr>
                            <th class="px-5 py-2.5 w-16 text-center print:py-1.5">No</th>
                            <th class="px-5 py-2.5 print:py-1.5">Rincian Perbaikan & Pergantian Sparepart</th>
                            <th class="px-5 py-2.5 text-center w-20 print:py-1.5">Qty</th>
                            <th class="px-5 py-2.5 text-right w-40 print:py-1.5">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($servis->detailJasa as $jasa)
                        <tr class="border-b">
                            <td class="px-5 py-2 text-center text-gray-500 print:py-1">{{ $no++ }}</td>
                            <td class="px-5 py-2 font-medium text-gray-800 print:py-1">{{ $jasa->jasa->nama_jasa }} <span class="text-[10px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded ml-1">Jasa</span></td>
                            <td class="px-5 py-2 text-center print:py-1">1</td>
                            <td class="px-5 py-2 text-right text-gray-900 print:py-1">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        
                        @foreach($servis->detailSparepart as $sparepart)
                        <tr class="border-b">
                            <td class="px-5 py-2 text-center text-gray-500 print:py-1">{{ $no++ }}</td>
                            <td class="px-5 py-2 font-medium text-gray-800 print:py-1">{{ $sparepart->sparepart->nama_sparepart }} <span class="text-[10px] text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded ml-1">Sparepart</span></td>
                            <td class="px-5 py-2 text-center print:py-1">{{ $sparepart->qty }}</td>
                            <td class="px-5 py-2 text-right text-gray-900 print:py-1">Rp {{ number_format($sparepart->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-emerald-50/50 font-bold border-t-2 border-emerald-600">
                        <tr>
                            <td colspan="3" class="px-5 py-3 text-right text-gray-700 uppercase tracking-wider print:py-2">Total Pembayaran (Lunas)</td>
                            <td class="px-5 py-3 text-right text-lg text-emerald-700 print:py-2 print:text-base">
                                Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pernyataan Serah Terima Pelanggan --}}
            <div class="border border-dashed border-emerald-300 bg-emerald-50/20 rounded-xl p-3 text-center text-xs text-emerald-800 leading-relaxed mb-4 print:py-2 print:text-[11px] print:mb-4">
                "Dengan menandatangani dokumen ini, pelanggan menyatakan bahwa unit yang diperbaiki telah diterima kembali dalam kondisi baik, dicek bersama, serta seluruh biaya administrasi perbaikan dinyatakan <strong>LUNAS</strong>."
            </div>

            {{-- Tanda Tangan Dokumen Fisik --}}
            <div class="grid grid-cols-2 gap-10 print:mt-2">
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-12 print:text-[10px] print:mb-10">Diserahkan Oleh (Admin/Teknisi),</p>
                    <p class="font-bold text-gray-800 border-t border-gray-400 pt-1.5 w-48 mx-auto print:text-xs">SEVEN COMPUTER</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-12 print:text-[10px] print:mb-10">Diterima Oleh (Pelanggan),</p>
                    <p class="font-bold text-gray-800 border-t border-gray-400 pt-1.5 w-48 mx-auto print:text-xs">{{ $servis->booking->pelanggan->nama }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection