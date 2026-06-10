@extends('layouts.layout')

@section('title', 'Katalog & Stok Sparepart - Owner')

@section('content')
    {{-- HEADER SECTION --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Katalog & Stok Sparepart</h2>
        <p class="text-gray-500 mt-1">Laporan real-time ketersediaan item komponen aktif pada sistem bengkel.</p>
    </div>

{{-- STATS WIDGET --}}
@php
$sparepart_stats = [
    ['title' => 'Variasi Komponen', 'total' => $sparepart->total() . ' Jenis', 'icon'  => 'fa-cogs', 'color' => 'blue', 'label' => 'Macam Item'],
    ['title' => 'Total Unit Fisik Ready', 'total' => number_format($sparepart->sum('stok'), 0, ',', '.') . ' Pcs', 'icon'  => 'fa-cubes', 'color' => 'emerald', 'label' => 'Total Stok'],
];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-10">
    @foreach($sparepart_stats as $stat)
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

    {{-- TABLE SECTION --}}
    <div class="bg-white mb-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                
                {{-- HEADER --}}
                <thead class="bg-blue-600 text-xs uppercase text-white">
                    <tr>
                        <th class="px-5 py-5 text-center">No</th>
                        <th class="px-5 py-5 text-center">Nama Sparepart</th>
                        <th class="px-5 py-5 text-center">Kategori</th>
                        <th class="px-5 py-5 text-center">Stok</th>
                        <th class="px-5 py-5 text-center">Harga Jual</th>
                        <th class="px-5 py-5 text-center">Status</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse ($sparepart as $index => $s)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-5 py-4 text-center text-gray-500">{{ $sparepart->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center text-gray-900">{{ $s->nama_sparepart }}</td>
                            <td class="px-5 py-4 text-center text-gray-600 font-normal">{{ $s->kategori }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($s->stok <= 5)
                                    <span class="text-center text-red-600 font-medium flex items-center justify-center gap-1" title="Stok kritis, butuh restock">{{ $s->stok }} Pcs</span>
                                @else
                                    <span class="text-center text-gray-700">{{ $s->stok }} Pcs</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center text-green-600 font-medium">Rp {{ number_format($s->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-normal border whitespace-nowrap {{ $s->status == 'tersedia' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400 italic">
                                Belum ada data master katalog sparepart terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $sparepart->links() }}
    </div>
@endsection