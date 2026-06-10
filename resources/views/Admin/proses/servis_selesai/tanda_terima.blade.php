@extends('layouts.layout')

@section('title', 'Tanda Terima Pengambilan - Arsip Toko')

@section('content')

<style>
    @media print {
        nav, aside, footer, .no-print, button, a {
            display: none !important;
        }

        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .print-area {
            box-shadow: none !important;
            border: none !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

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
            {{-- Mengarah ke Servis Selesai --}}
            <a href="{{ route('admin.servis_selesai.index') }}"
               class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                Kembali
            </a>

            <button onclick="window.print()"
                    class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm transition-all">
                Cetak Arsip Pengambilan
            </button>
        </div>
    </div>

    {{-- AREA NOTA --}}
    <div class="max-w-5xl mx-auto bg-white border border-gray-200 shadow-lg rounded-2xl overflow-hidden print-area">

        {{-- Header Nota --}}
        <div class="p-8 border-b border-dashed border-gray-300 bg-gray-50/50">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 tracking-wide">SEVEN COMPUTER</h1>
                    <p class="text-sm text-gray-500 mt-2">Jasa Servis Laptop & Komputer</p>
                    <div class="mt-4 text-sm text-gray-600 leading-6">
                        Jl. Pudang No.53, Pasiran, Tegalreja, Kec. Cilacap Sel.<br>
                        Telp: 0858-7900-0070
                    </div>
                </div>

                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-800 uppercase text-emerald-600">Bukti Pengambilan Unit</h2>
                    <p class="mt-1 text-sm text-gray-500">Kode Servis: <span class="font-bold text-gray-800">{{ $servis->kode_servis }}</span></p>
                </div>
            </div>
        </div>

        {{-- Konten Utama Nota --}}
        <div class="p-8">
            {{-- Bagian Info Pelanggan & Servis --}}
            <div class="grid grid-cols-2 gap-10 mb-6">
                <div>
                    <h3 class="text-xs font-bold uppercase text-gray-400 mb-3 tracking-wider">Identitas Pengambil</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1.5 w-32 font-semibold text-gray-600">Nama Pelanggan</td>
                            <td class="text-gray-900">: {{ $servis->booking->pelanggan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 font-semibold text-gray-600">No HP</td>
                            <td class="text-gray-900">: {{ $servis->booking->pelanggan->no_hp }}</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 font-semibold text-gray-600">Unit Perangkat</td>
                            <td class="text-gray-900">: {{ $servis->booking->merk_tipe }}</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase text-gray-400 mb-3 tracking-wider">Riwayat Waktu</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1.5 w-32 font-semibold text-gray-600">Tanggal Masuk</td>
                            <td class="text-gray-900">: {{ date('d M Y', strtotime($servis->tgl_masuk)) }}</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 font-semibold text-gray-600">Tanggal Ambil</td>
                            <td class="text-gray-900">: {{ date('d M Y H:i') }} WIB (Hari Ini)</td>
                        </tr>
                        <tr>
                            <td class="py-1.5 font-semibold text-gray-600">Status Akhir</td>
                            <td class="uppercase font-bold text-emerald-600">: {{ $servis->status_servis }} (DIAMBIL)</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Detail Tabel Rincian Biaya & Komponen --}}
            <div class="overflow-hidden border border-gray-200 rounded-xl mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800 text-white uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">No</th>
                            <th class="px-5 py-3">Rincian Perbaikan & Pergantian Sparepart</th>
                            <th class="px-5 py-3 text-center w-20">Qty</th>
                            <th class="px-5 py-3 text-right w-40">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($servis->detailJasa as $jasa)
                        <tr class="border-b">
                            <td class="px-5 py-3 text-center text-gray-500">{{ $no++ }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $jasa->jasa->nama_jasa }} <span class="text-xs text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded ml-1">Jasa</span></td>
                            <td class="px-5 py-3 text-center">1</td>
                            <td class="px-5 py-3 text-right text-gray-900">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        
                        @foreach($servis->detailSparepart as $sparepart)
                        <tr class="border-b">
                            <td class="px-5 py-3 text-center text-gray-500">{{ $no++ }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $sparepart->sparepart->nama_sparepart }} <span class="text-xs text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded ml-1">Sparepart</span></td>
                            <td class="px-5 py-3 text-center">{{ $sparepart->qty }}</td>
                            <td class="px-5 py-3 text-right text-gray-900">Rp {{ number_format($sparepart->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-emerald-50/50 font-bold border-t-2 border-emerald-600">
                        <tr>
                            <td colspan="3" class="px-5 py-4 text-right text-gray-700 uppercase tracking-wider">Total Pembayaran (Lunas)</td>
                            <td class="px-5 py-4 text-right text-xl text-emerald-700">
                                Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pernyataan Serah Terima Pelanggan --}}
            <div class="border border-dashed border-emerald-300 bg-emerald-50/20 rounded-xl p-4 text-center text-xs text-emerald-800 leading-relaxed mb-8">
                "Dengan menandatangani dokumen ini, pelanggan menyatakan bahwa unit yang diperbaiki telah diterima kembali dalam kondisi baik, dicek bersama, serta seluruh biaya administrasi perbaikan dinyatakan <strong>LUNAS</strong>."
            </div>

            {{-- Tanda Tangan Dokumen Fisik --}}
            <div class="grid grid-cols-2 gap-10">
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-16">Diserahkan Oleh (Admin/Teknisi),</p>
                    <p class="font-bold text-gray-800 border-t border-gray-400 pt-2 w-48 mx-auto">SEVEN COMPUTER</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-16">Diterima Oleh (Pelanggan),</p>
                    <p class="font-bold text-gray-800 border-t border-gray-400 pt-2 w-48 mx-auto">{{ $servis->booking->pelanggan->nama }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection