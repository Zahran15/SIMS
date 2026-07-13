@extends('layouts.layout')

@section('title', 'Pantauan Request Sparepart - Owner')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Laporan Request Sparepart</h2>
        <p class="text-gray-500 mt-1">Pantau seluruh penggunaan komponen dan status pengajuan dari bengkel secara real-time.</p>
    </div>

{{-- KARTU STATISTIK SINGKAT --}}
@php
$request_stats = [
    [
        'title' => 'Total Request', 
        'total' => $totalRequest . ' Pengajuan', 
        'icon'  => 'fa-clipboard-list', 
        'color' => 'blue', 
        'label' => 'Semua Permintaan'
    ],
    [
        'title' => 'Selesai / Keluar', 
        'total' => $totalDisetujui . ' Selesai', 
        'icon'  => 'fa-check-circle', 
        'color' => 'emerald', 
        'label' => 'Stok Telah Dipotong'
    ],
    [
        'title' => 'Menunggu Proses', 
        'total' => $totalAntrean . ' Antrean', 
        'icon'  => 'fa-hourglass-half', 
        'color' => 'amber', 
        'label' => 'Tahap Validasi Bertingkat'
    ],
];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    @foreach($request_stats as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all group duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">{{ $stat['title'] }}</p>
                <h3 class="text-2xl font-medium text-gray-800 transition-colors mt-1">{{ $stat['total'] }}</h3>
                <span class="text-[9px] font-bold text-{{ $stat['color'] }}-600 bg-{{ $stat['color'] }}-50 px-2 py-0.5 rounded uppercase mt-2 block w-max tracking-wide">{{ $stat['label'] }}</span>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center group-hover:bg-{{ $stat['color'] }}-50 transition-colors duration-300">
                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-xl group-hover:scale-110 transition-transform"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- FILTER --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('owner.request_sparepart.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            {{-- Filter Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status_request"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[250px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending_admin" {{ request('status_request') == 'pending_admin' ? 'selected' : '' }}>Pending Admin</option>
                    <option value="dikirim_ke_pelanggan" {{ request('status_request') == 'dikirim_ke_pelanggan' ? 'selected' : '' }}>Menunggu Konfirmasi Pelanggan</option>
                    <option value="disetujui_pelanggan" {{ request('status_request') == 'disetujui_pelanggan' ? 'selected' : '' }}>Disetujui Pelanggan</option>
                    <option value="disetujui" {{ request('status_request') == 'disetujui' ? 'selected' : '' }}>Disetujui Admin (Selesai)</option>
                    <option value="ditolak" {{ request('status_request') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    Cari
                </button>
                <a href="{{ route('owner.request_sparepart.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('status_request'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter: Status <span class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', request('status_request'))) }}</span>
    </div>
@endif

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                {{-- THEAD --}}
                <thead class="bg-blue-600 text-white shadow-sm">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Kode Servis</th>
                        <th class="px-5 py-4 text-center">Sparepart</th>
                        <th class="px-5 py-4 text-center">Jumlah Keluar</th>
                        <th class="px-5 py-4 text-center">Tanggal Pengajuan</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- TBODY --}}
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($requestSparepart as $index => $r)
                    <tr class="hover:bg-gray-50/70 transition-all">
                        <td class="px-5 py-4 text-center text-gray-500">{{ $requestSparepart->firstItem() + $index }}</td>
                        <td class="px-5 py-4 text-center font-bold text-blue-600">
                            {{ $r->penugasan->servis->kode_servis ?? '-' }}
                            <span class="block text-xs font-normal text-gray-400 mt-0.5">{{ $r->penugasan->servis->booking->pelanggan->nama ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4 text-center text-gray-800">{{ $r->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}</td>
                        <td class="px-5 py-4 text-center text-gray-600 font-semibold">{{ $r->jumlah }} Pcs</td>
                        <td class="px-5 py-4 text-center text-gray-500 font-normal">{{ $r->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($r->status_request == 'pending_admin')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">Pending Admin</span>
                            @elseif($r->status_request == 'dikirim_ke_pelanggan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">Di Pelanggan</span>
                            @elseif($r->status_request == 'disetujui_pelanggan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">ACC Pelanggan</span>
                            @elseif($r->status_request == 'disetujui')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center">
                                {{-- OWNER CUMA BISA DETAIL UNTUK LIHAT ALASANNYA --}}
                                <a href="{{ route('owner.request_sparepart.detail', $r->id_request) }}" 
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                                    title="Lihat Detail Keluhan">
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
                        <td colspan="7" class="text-center py-12 text-gray-400 font-medium">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <span>Belum ada data transaksi permintaan barang.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $requestSparepart->appends(request()->query())->links() }}
    </div>
@endsection