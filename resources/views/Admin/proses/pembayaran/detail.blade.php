@extends('layouts.layout')

@section('title', 'Detail Pembayaran Admin')

@section('content')
    {{-- HEADER HALAMAN --}}
    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Detail Pembayaran #{{ $pembayaran->id_pembayaran }}</h2>
            <p class="text-gray-500 text-xs mt-0.5">Kelola dan tinjau rincian rekonsiliasi data pembayaran dari pelanggan</p>
        </div>
        <div>
            <a href="{{ route('admin.pembayaran.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded border border-gray-300 transition-colors shadow-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- UTAMA: RINCIAN TRANSAKSI & PELANGGAN --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- BLOK 1: INFORMASI UTAMA --}}
            <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Rincian Pembayaran</h3>
                    @if($pembayaran->status_pembayaran == 'sukses')
                        <span class="px-2.5 py-1 text-[10px] font-bold bg-green-100 text-green-800 border border-green-200 rounded uppercase tracking-wide">Lunas / Sukses</span>
                    @elseif($pembayaran->status_pembayaran == 'pending')
                        <span class="px-2.5 py-1 text-[10px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 rounded uppercase tracking-wide">Pending</span>
                    @else
                        <span class="px-2.5 py-1 text-[10px] font-bold bg-red-100 text-red-800 border border-red-200 rounded uppercase tracking-wide">Gagal / Expired</span>
                    @endif
                </div>

                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-xs">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Kode Booking</span>
                        <a href="#" class="text-blue-600 font-bold text-sm hover:underline tracking-wide">
                            {{ $pembayaran->booking->kode_booking ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Total Nominal</span>
                        <strong class="text-gray-900 text-base font-extrabold">
                            Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="border-t pt-3 md:border-none md:pt-0">
                        <span class="text-gray-400 block mb-0.5">Jenis Pembayaran</span>
                        <span class="text-gray-800 font-semibold uppercase tracking-wide bg-gray-100 px-2 py-0.5 rounded text-[11px]">
                            {{ $pembayaran->jenis_pembayaran ?? '-' }}
                        </span>
                    </div>
                    <div class="border-t pt-3 md:border-none md:pt-0">
                        <span class="text-gray-400 block mb-0.5">Metode Pembayaran</span>
                        <span class="text-purple-800 font-semibold uppercase tracking-wide bg-purple-50 px-2 py-0.5 rounded text-[11px] border border-purple-100">
                            {{ $pembayaran->metode_pembayaran ?? 'Belum Memilih' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- BLOK 2: DETAIL PELANGGAN --}}
            <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Informasi Pelanggan</h3>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block">Nama Lengkap</span>
                        <strong class="text-gray-800 text-sm font-bold">{{ $pembayaran->booking->pelanggan->nama ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Email Pelanggan</span>
                        <span class="text-gray-700 font-medium">{{ $pembayaran->booking->pelanggan->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">No. Telepon / WhatsApp</span>
                        <span class="text-gray-700 font-medium">{{ $pembayaran->booking->pelanggan->no_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: AUDIT SYSTEM LOG & GATEWAY METADATA --}}
        <div class="space-y-5">
            {{-- BLOK LOGISTIK & WAKTU --}}
            <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden text-xs">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Waktu Transaksi</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <span class="text-gray-400 block">Tanggal Pembayaran Dibuat</span>
                        <span class="text-gray-700 font-medium">{{ $pembayaran->created_at ? $pembayaran->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="border-t pt-2">
                        <span class="text-gray-400 block">Tanggal Konfirmasi / Bayar</span>
                        <span class="text-gray-700 font-bold">
                            {{ $pembayaran->tanggal_bayar ? date('d M Y', strtotime($pembayaran->tanggal_bayar)) : 'Belum Melakukan Transfer' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- BLOK INTEGRASI MIDTRANS --}}
            @if($pembayaran->metode_pembayaran == 'transfer' || isset($pembayaran->snap_token))
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded border border-gray-200 shadow-sm overflow-hidden text-xs">
                    <div class="px-4 py-3 bg-slate-200/60 border-b border-slate-300 flex items-center justify-between">
                        <h3 class="font-bold text-slate-700 uppercase text-xs tracking-wider">Log Midtrans Gateway</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <span class="text-slate-500 block mb-1">Snap Token</span>
                            @if($pembayaran->snap_token)
                                <div class="flex items-center gap-1.5">
                                    <code class="bg-white px-2 py-1 rounded border border-slate-200 font-mono text-slate-700 select-all break-all block w-full text-[11px]">
                                        {{ $pembayaran->snap_token }}
                                    </code>
                                </div>
                            @else
                                <span class="text-gray-400 italic">Belum Digenerate</span>
                            @endif
                        </div>
                        <div class="border-t border-slate-200 pt-2">
                            <span class="text-slate-500 block">Midtrans Order ID</span>
                            <code class="text-slate-800 font-mono font-bold">{{ $pembayaran->midtrans_order_id ?? '-' }}</code>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection