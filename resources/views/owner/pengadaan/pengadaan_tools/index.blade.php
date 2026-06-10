@extends('layouts.layout')

@section('title', 'Data Tools - Owner')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Laporan Data Tools</h2>
    <p class="text-gray-500 mt-1">Memantau seluruh alat operasional yang digunakan oleh teknisi</p>
</div>

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
    {{ $tools->links() }}
</div>

@endsection