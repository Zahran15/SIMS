@extends('layouts.layout')

@section('title', 'Data Tools - Owner')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Laporan Data Tools</h2>
    <p class="text-gray-500 mt-1">Memantau seluruh alat operasional yang digunakan oleh teknisi</p>
</div>

{{-- FILTER --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('owner.pengadaan_tools.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">

            {{-- Filter Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="tidak tersedia" {{ request('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    Cari
                </button>

                <a href="{{ route('owner.pengadaan_tools.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('status'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter: 
        @if(request('status')) Status <span class="font-semibold text-gray-800">{{ ucfirst(request('status')) }}</span> @endif
    </div>
@endif

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-blue-600 text-xs uppercase text-white">
                <tr>
                    <th class="px-5 py-4 text-center">No</th>
                    <th class="px-5 py-4 text-center">Nama Teknisi</th>
                    <th class="px-5 py-4 text-center">Nama Tools</th>
                    <th class="px-5 py-4 text-center">Jumlah</th>
                    <th class="px-5 py-4 text-center">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($tools as $index => $t)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-center font-medium text-gray-500">{{ $tools->firstItem() + $index }}</td>
                        <td class="px-5 py-4 text-center"><div class="font-semibold text-gray-800">{{ $t->user->nama ?? 'Tidak Diketahui' }}</div></td>
                        <td class="px-5 py-4 text-center font-medium text-gray-900">{{ $t->nama_tools }}</td>
                        <td class="px-5 py-4 text-center font-semibold text-gray-800">{{ $t->jumlah }}</td>
                        <td class="px-5 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium inline-block
                                {{ $t->status == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500 italic bg-gray-50/50">
                            Belum ada data unit tools yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $tools->appends(request()->query())->links() }}
</div>

@endsection