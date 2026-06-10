@extends('layouts.layout')

@section('title', 'Data Jasa Servis')

@section('content')

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Jasa Servis</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh data jasa servis.</p>
    </div>
    <a href="{{ route('admin.jasa_servis.create') }}" 
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
        Tambah Jasa Servis
    </a>
</div>

{{-- Table Section Jasa Servis --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-blue-600 text-xs uppercase text-white">
                <tr>
                    <th class="px-5 py-5 text-center">No</th>
                    <th class="px-5 py-5 text-center">Nama Jasa</th>
                    <th class="px-5 py-5 text-center">Harga</th>
                    <th class="px-5 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jasa as $index => $j)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-5 text-center">{{ $jasa->firstItem() + $index }}</td>
                        <td class="px-5 py-5 text-center font-medium text-gray-900">{{ $j->nama_jasa }}</td>
                        <td class="px-5 py-5 text-center text-green-600 font-semibold">Rp {{ number_format($j->harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.jasa_servis.edit', $j->id_jasa) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                {{-- Tombol Delete --}}
                                <form action="{{ route('admin.jasa_servis.delete', $j->id_jasa) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-colors"
                                        title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-500 italic">
                            Belum ada data jasa servis
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $jasa->links() }}
</div>

@endsection