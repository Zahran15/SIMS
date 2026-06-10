@extends('layouts.layout')

@section('title', 'Detail Pembayaran Admin')

@section('content')
<div class="mb-4 flex items-center justify-between border-b pb-3">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail Pembayaran #{{ $pembayaran->id_pembayaran }}</h2>
        <p class="text-gray-500 text-xs">Informasi lengkap data pembayaran pelanggan</p>
    </div>
    <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-500 text-white px-3 py-1.5 rounded text-xs">Kembali</a>
</div>

<div class="bg-white rounded border p-4 space-y-4 text-xs shadow-sm">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 border-b pb-4">
        <div><span class="text-gray-400 block">Nama Pelanggan</span> <strong>{{ $pembayaran->booking->pelanggan->nama }}</strong></div>
        <div><span class="text-gray-400 block">Kode Booking</span> <strong class="text-blue-600">{{ $pembayaran->booking->kode_booking }}</strong></div>
        <div><span class="text-gray-400 block">Nominal Tagihan</span> <strong class="text-sm text-gray-900">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</strong></div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div><span class="text-gray-400 block">Jenis</span> <span class="uppercase font-semibold">{{ $pembayaran->jenis_pembayaran }}</span></div>
        <div><span class="text-gray-400 block">Metode Pembayaran</span> <span class="uppercase font-semibold text-purple-700">{{ $pembayaran->metode_pembayaran }}</span></div>
        <div><span class="text-gray-400 block">Status</span> <span class="uppercase font-bold text-green-600">{{ $pembayaran->status_pembayaran }}</span></div>
        <div><span class="text-gray-400 block">Tanggal Bayar</span> <span>{{ $pembayaran->tanggal_bayar ?? '-' }}</span></div>
    </div>
    
    @if($pembayaran->metode_pembayaran == 'midtrans')
    <div class="bg-gray-50 p-3 rounded border mt-2">
        <span class="font-bold text-gray-700 block mb-1">Metadata Midtrans:</span>
        <p>Snap Token: <code class="bg-gray-200 px-1 rounded">{{ $pembayaran->snap_token ?? 'Belum Digenerate' }}</code></p>
        <p>Midtrans Order ID: <code>{{ $pembayaran->midtrans_order_id ?? '-' }}</code></p>
    </div>
    @endif
</div>
@endsection