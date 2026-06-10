@extends('layouts.layout')

@section('title', 'Data Pelanggan')

@section('content')

{{-- Header Section --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Pelanggan</h2>
            <p class="text-gray-500 mt-1">Kelola seluruh data pelanggan.</p>
        </div>

        <a href="{{ route('admin.pelanggan.create') }}"
            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Tambah Pelanggan
        </a>
    </div>

{{-- Table Section --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-blue-600 text-xs uppercase text-white">
                <tr>
                    <th class="px-5 py-5 text-center">No</th>
                    <th class="px-5 py-5 text-center">Kode</th>
                    <th class="px-5 py-5 text-center">Nama</th>
                    <th class="px-5 py-5 text-center">Email</th>
                    <th class="px-5 py-5 text-center">No HP</th>
                    <th class="px-5 py-5 text-center">Alamat</th>
                    <th class="px-5 py-5 text-center">Status</th>
                    <th class="px-5 py-5 text-center">Aksi</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody>
                @forelse ($pelanggan as $index => $p)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-5 text-center">{{ $pelanggan->firstItem() + $index }}</td>
                        <td class="px-5 py-5 text-center font-bold text-indigo-600">{{ $p->kode_pelanggan }}</td>
                        <td class="px-5 py-5 text-center font-medium text-gray-900">{{ $p->nama }}</td>
                        <td class="px-5 py-5 text-center">{{ $p->email }}</td>
                        <td class="px-5 py-5 text-center">{{ $p->no_hp }}</td>
                        <td class="px-5 py-5 text-center">{{ $p->alamat ?? '-' }}</td>
                        <td class="px-5 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $p->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">

                                {{-- DETAIL --}}
                                <a href="{{ route('admin.pelanggan.detail', $p->id_pelanggan) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                    title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.pelanggan.edit', $p->id_pelanggan) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.pelanggan.delete', $p->id_pelanggan) }}" method="POST"
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
                        <td colspan="8" class="text-center py-10 text-gray-500 italic">
                            Belum ada data pelanggan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $pelanggan->links() }}
</div>

@endsection