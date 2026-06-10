@extends('layouts.layout')

@section('title', 'Laporan Pengadaan Sparepart - Owner')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Laporan Pengadaan Sparepart</h2>
        <p class="text-gray-500 mt-1">Pantau rincian pengeluaran modal modal pembelanjaan stok masuk toko.</p>
    </div>

    {{-- KARTU STATISTIK FINANSIAL (Sangat Disukai Dosen Saat Sidang TA) --}}
    @php
    $financial_stats = [
        ['title' => 'Total Transaksi', 'total' => $pengadaan->total() . ' Nota', 'icon'  => 'fa-boxes', 'color' => 'blue', 'label' => 'Semua Pengadaan'],
        ['title' => 'Total Pengeluaran Modal', 'total' => 'Rp ' . number_format($pengadaan->sum('total'), 0, ',', '.'), 'icon'  => 'fa-wallet', 'color' => 'emerald', 'label' => 'Kas Keluar'],
        ['title' => 'Status Diterima', 'total' => $pengadaan->where('status_pengadaan', 'diterima')->count() . ' Item', 'icon'  => 'fa-check-double', 'color' => 'indigo', 'label' => 'Selesai Dicek'],
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @foreach($financial_stats as $stat)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all group duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">{{ $stat['title'] }}</p>
                    <h3 class="text-2xl font-medium text-gray-800 group-hover:text-{{ $stat['color'] }}-600 transition-colors mt-1">{{ $stat['total'] }}</h3>
                    <span class="text-[9px] font-bold text-{{ $stat['color'] }}-600 bg-{{ $stat['color'] }}-50 px-2 py-0.5 rounded uppercase mt-2 block w-max tracking-wide">{{ $stat['label'] }}</span>
                </div>
                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center group-hover:bg-{{ $stat['color'] }}-50 transition-colors duration-300">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-xl group-hover:scale-110 transition-transform"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">

                {{-- HEADER --}}
                <thead class="bg-blue-600 text-xs uppercase text-white shadow-sm">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Sparepart</th>
                        <th class="px-5 py-4 text-center">Tanggal Pesan</th>
                        <th class="px-5 py-4 text-center">Jumlah</th>
                        <th class="px-5 py-4 text-center">Harga Beli</th>
                        <th class="px-5 py-4 text-center">Total Pengeluaran</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse ($pengadaan as $index => $p)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-4 text-center text-gray-500">{{ $pengadaan->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center font-medium text-gray-900">{{ $p->sparepart->nama_sparepart ?? '-' }}</td>
                            <td class="px-5 py-4 text-center text-gray-500 font-medium">{{ \Carbon\Carbon::parse($p->tgl_pesan)->translatedFormat('d F Y') }}</td>
                            <td class="px-5 py-4 text-center text-gray-700 font-medium">{{ $p->jumlah }} Pcs</td>
                            <td class="px-5 py-4 text-center text-gray-600">Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center text-green-600 font-medium">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($p->status_pengadaan == 'dipesan') bg-yellow-100 text-yellow-700 border border-yellow-200
                                    @elseif($p->status_pengadaan == 'diterima') bg-green-100 text-green-700 border border-green-200
                                    @else bg-red-100 text-red-700 border border-red-200
                                    @endif">
                                    {{ ucfirst($p->status_pengadaan) }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center">
                                    {{-- OWNER HANYA BISA LIHAT DETAIL NOTA --}}
                                    <a href="{{ route('owner.pengadaan_sparepart.detail', $p->id_pengadaan) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                        title="Lihat Detail Nota Pembelian">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400 italic">
                                Belum ada riwayat transaksi pengadaan suku cadang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $pengadaan->links() }}
    </div>
@endsection