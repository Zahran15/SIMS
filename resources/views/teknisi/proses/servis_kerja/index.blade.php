@extends('layouts.layout')

@section('title','Tugas Servis')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Tugas Servis</h2>
        <p class="text-gray-500 mt-1">Daftar servis yang ditugaskan ke Anda</p>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

{{-- FILTER & SEARCH TUGAS SERVIS --}}
    <div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
        <form action="{{ route('teknisi.servis_kerja.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4 md:items-end">
                
                {{-- Input Pencarian Nama Pelanggan --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pelanggan</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Nama pelanggan..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Filter Prioritas --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <select name="prioritas"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[180px] text-sm">
                        <option value="">Semua Prioritas</option>
                        <option value="rendah" {{ request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="normal" {{ request('prioritas') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="tinggi" {{ request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="urgent" {{ request('prioritas') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                {{-- Filter Status Penugasan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Penugasan</label>
                    <select name="status_penugasan"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[180px] text-sm">
                        <option value="">Semua Status</option>
                        <option value="belum dikerjakan" {{ request('status_penugasan') == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                        <option value="sedang dikerjakan" {{ request('status_penugasan') == 'sedang dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="menunggu sparepart" {{ request('status_penugasan') == 'menunggu sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                        <option value="selesai" {{ request('status_penugasan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="gagal" {{ request('status_penugasan') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition font-medium">
                        Cari
                    </button>
                    <a href="{{ route('teknisi.servis_kerja.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition font-medium">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Badge Filter Aktif --}}
    @if(request('search') || request('status_penugasan') || request('prioritas'))
        <div class="mb-4 text-sm text-gray-600">
            <span>Filter aktif:</span>
            @if(request('search')) <span class="font-semibold text-gray-800">Pelanggan: "{{ request('search') }}"</span> @endif
            @if(request('status_penugasan')) <span class="font-semibold text-gray-800">Status: {{ ucwords(request('status_penugasan')) }}</span> @endif
            @if(request('prioritas')) <span class="font-semibold text-gray-800">Prioritas: {{ ucfirst(request('prioritas')) }}</span> @endif
        </div>
    @endif

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead class="bg-blue-600 text-white text-sm">
                <tr>
                    <th class="px-5 py-4 text-center">No</th>
                    <th class="px-5 py-4 text-center">Kode</th>
                    <th class="px-5 py-4 text-center">Pelanggan</th>
                    <th class="px-5 py-4 text-center">Device</th>
                    <th class="px-5 py-4 text-center">Prioritas</th>
                    <th class="px-5 py-4 text-center">Estimasi Selesai</th>
                    <th class="px-5 py-4 text-center">Status</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($penugasan as $index=>$p)
                <tr class="border-b hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-center font-medium">{{ $penugasan->firstItem()+$index }}</td>
                    <td class="px-5 py-4 text-center font-bold text-blue-600">{{ $p->servis->kode_servis }}</td>
                    <td class="px-5 py-4 text-center">
                        <div class="font-semibold text-gray-800">{{ $p->servis->booking->pelanggan->nama }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $p->servis->booking->kode_booking }}</div>
                    </td>  
                    <td class="px-5 py-4 text-center text-gray-600">{{ $p->servis->booking->merk_tipe }}</td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        @if($p->prioritas == 'urgent')
                            <span class="px-3 py-1 bg-red-300 text-red-900 rounded-full text-xs font-semibold">Urgent</span>
                        @elseif($p->prioritas == 'tinggi')
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Tinggi</span>
                        @elseif($p->prioritas == 'normal' || $p->prioritas == 'sedang')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Normal</span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Rendah</span>
                        @endif
                    </td>
                        <td class="px-5 py-4 text-center font-medium text-gray-800">
                            {{ $p->estimasi_selesai ? \Carbon\Carbon::parse($p->estimasi_selesai)->format('d-m-Y') : '-' }}
                        </td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            {{ ucfirst($p->status_penugasan) }}
                        </td>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center items-center gap-2">
                            {{-- DETAIL --}}
                            <a href="{{ route('teknisi.servis_kerja.detail', $p->id_penugasan) }}"
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            {{-- KERJAKAN/EDIT --}}
                            @if($p->status_penugasan !== 'selesai')
                            <a href="{{ route('teknisi.servis_kerja.edit', $p->id_penugasan) }}"                                    
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/xl" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-gray-400 font-medium">
                        Belum ada penugasan servis
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $penugasan->appends(request()->query())->links() }}
    </div>
</div>
@endsection