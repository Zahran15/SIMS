@extends('layouts.layout')

@section('title', 'Tanda Terima Awal - Unit Masuk')

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
            <h2 class="text-3xl font-bold text-gray-800">Tanda Terima Unit Masuk</h2>
            <p class="text-gray-500 mt-1">Cetak bukti penitipan unit untuk dibawa pulang oleh pelanggan</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.servis_proses.index') }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-all">Kembali</a>
            <button onclick="window.print()"class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition-all">Cetak Tanda Terima</button>
        </div>
    </div>

    {{-- AREA NOTA --}}
    <div class="max-w-5xl mx-auto bg-white border border-gray-200 shadow-lg rounded-2xl overflow-hidden print-area">

        {{-- Header Nota --}}
        <div class="p-8 border-b border-dashed border-gray-300">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 tracking-wide">SEVEN COMPUTER</h1>
                    <p class="text-sm text-gray-500 mt-2">Jasa Servis Laptop & Komputer</p>
                    <div class="mt-4 text-sm text-gray-700 leading-6">
                        Jl. Pudang No.53, Pasiran, Tegalreja, Kec. Cilacap Sel.<br>
                        Cilacap, Jawa Tengah<br>
                        Telp: 0858-7900-0070
                    </div>
                </div>

                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wider text-blue-600">Bukti Titip Servis</h2>
                    <p class="mt-3 text-sm text-gray-500">Kode Servis</p>
                    <h3 class="text-xl font-black text-gray-800">{{ $servis->kode_servis }}</h3>
                </div>
            </div>
        </div>

        {{-- Konten Utama Nota --}}
        <div class="p-8">
            {{-- Bagian Info Pelanggan & Servis --}}
            <div class="grid grid-cols-2 gap-10 mb-8">
                <div>
                    <h3 class="text-sm font-bold uppercase text-gray-400 mb-4 tracking-wider">Data Pelanggan</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-2 w-32 font-semibold text-gray-700">Nama Pelanggan</td>
                            <td class="text-gray-900">: {{ $servis->booking->pelanggan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold text-gray-700">No. HP / WA</td>
                            <td class="text-gray-900">: {{ $servis->booking->pelanggan->no_hp }}</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase text-gray-400 mb-4 tracking-wider">Detail Kedatangan</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-2 w-32 font-semibold text-gray-700">Tanggal Masuk</td>
                            <td class="text-gray-900">: {{ date('d M Y', strtotime($servis->tgl_masuk)) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold text-gray-700">Status Unit</td>
                            <td class="font-bold text-blue-600 uppercase">: {{ $servis->status_servis }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Detail Unit & Keluhan --}}
            <div class="overflow-hidden border border-gray-200 rounded-2xl mb-8">
                <table class="w-full text-sm text-left">
                    <thead class="bg-blue-600 text-white uppercase text-xs">
                        <tr>
                            <th class="px-5 py-4 w-1/3">Spesifikasi Unit / Perangkat</th>
                            <th class="px-5 py-4 w-1/3">Keluhan / Kerusakan</th>
                            <th class="px-5 py-4 w-1/3">Kelengkapan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-top bg-blue-50/30">
                            <td class="px-5 py-5 text-gray-800 leading-relaxed font-semibold text-base">{{ $servis->booking->merk_tipe }}</td>
                            <td class="px-5 py-5 text-gray-600 leading-relaxed border-l border-gray-200">{{ $servis->booking->keluhan ?? '-' }}</td>
                            <td class="px-5 py-5 text-gray-600 leading-relaxed border-l border-gray-200">{{ $servis->booking->kelengkapan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Catatan & Syarat Ketentuan --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-xs text-gray-500 leading-relaxed">
                <p class="font-bold text-gray-700 mb-2 uppercase text-center tracking-wide">Syarat & Ketentuan Perbaikan Laptop</p>
                <ol class="list-decimal pl-4 space-y-1">
                    <li>Nota ini **wajib dibawa** kembali oleh pelanggan saat melakukan pengambilan unit.</li>
                    <li>Perubahan rincian estimasi biaya dan part yang rusak akan selalu kami konfirmasi terlebih dahulu melalui WhatsApp/Telepon.</li>
                    <li>Kehilangan data di dalam storage (HDD/SSD) akibat proses perbaikan di luar tanggung jawab pihak SEVEN COMPUTER.</li>
                    <li>Barang servis yang tidak diambil dalam waktu **30 hari** setelah konfirmasi selesai, di luar tanggung jawab kami jika terjadi risiko kehilangan/kerusakan.</li>
                </ol>
            </div>

            {{-- Tanda Tangan --}}
            <div class="mt-12 grid grid-cols-2 gap-10">
                <div class="text-center">
                    <p class="text-sm text-gray-500 italic mb-16">Penerima (Admin SEVEN COMPUTER),</p>
                    <p class="font-bold text-gray-800 border-t border-gray-800 pt-2 w-48 mx-auto">SEVEN COMPUTER</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 italic mb-16">Yang Menyerahkan (Pelanggan),</p>
                    <p class="font-bold text-gray-800 border-t border-gray-800 pt-2 w-48 mx-auto">{{ $servis->booking->pelanggan->nama }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection