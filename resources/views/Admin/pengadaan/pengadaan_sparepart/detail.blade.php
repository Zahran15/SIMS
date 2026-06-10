@extends('layouts.layout')

@section('title', 'Detail Pengadaan Sparepart')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.pengadaan_sparepart.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2 transition-all">
                ← Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Detail Nota Pengadaan</h2>
            <p class="text-gray-500 mt-1">Keterangan logistik inventori masuk gudang.</p>
        </div>

        {{-- STATUS BADGE --}}
        <div>
            <span class="px-4 py-2 rounded-xl text-sm font-bold shadow-sm border
                @if($data->status_pengadaan == 'dipesan') bg-yellow-100 text-yellow-700 border-yellow-200
                @elseif($data->status_pengadaan == 'diterima') bg-green-100 text-green-700 border-green-200
                @else bg-red-100 text-red-700 border-red-200
                @endif">
                Status: {{ ucfirst($data->status_pengadaan) }}
            </span>
        </div>
    </div>

    {{-- CONTENT CARD --}}
    <div class="bg-white rounded-2xl shadow border p-6 font-medium space-y-6">
        <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Informasi Transaksi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-gray-400 font-semibold uppercase tracking-wider text-xs">ID Pengadaan</p>
                <p class="text-base font-bold text-gray-800 mt-1">#PGD-{{ str_pad($data->id_pengadaan, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-gray-400 font-semibold uppercase tracking-wider text-xs">Tanggal Pembelian</p>
                <p class="text-base font-bold text-gray-800 mt-1">{{ \Carbon\Carbon::parse($data->tgl_pesan)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-gray-100 grid grid-cols-3 gap-2">
                <div>
                    <p class="text-xs text-gray-400 font-semibold">Nama Suku Cadang</p>
                    <p class="text-sm font-bold text-blue-600 mt-1">{{ $data->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 font-semibold">Kuantitas</p>
                    <p class="text-sm font-bold text-gray-800 mt-1">{{ $data->jumlah }} Unit</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 font-semibold">Harga Beli Satuan</p>
                    <p class="text-sm font-bold text-gray-800 mt-1">Rp {{ number_format($data->harga_beli, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- TOTAL PENGELUARAN AKHIR --}}
        <div class="pt-4 border-t flex justify-between items-center">
            <span class="text-gray-500 font-bold">Total Pembayaran Invoice:</span>
            <span class="text-2xl font-black text-green-600">
                Rp {{ number_format($data->total, 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>
@endsection