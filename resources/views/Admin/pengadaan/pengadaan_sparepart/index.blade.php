@extends('layouts.layout')

@section('title', 'Data Pengadaan Sparepart')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Pengadaan Sparepart</h2>
            <p class="text-gray-500 mt-1">Kelola pembelian dan riwayat stok sparepart masuk</p>
        </div>

        <a href="{{ route('admin.pengadaan_sparepart.create') }}"
            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Tambah Pengadaan Sparepart
        </a>
    </div>

    {{-- FLASH ALERT --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 font-medium border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                {{-- HEADER --}}
                <thead class="bg-blue-600 text-xs uppercase text-white">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Sparepart</th>
                        <th class="px-5 py-4 text-center">Tanggal</th>
                        <th class="px-5 py-4 text-center">Jumlah</th>
                        <th class="px-5 py-4 text-center">Harga Beli</th>
                        <th class="px-5 py-4 text-center">Total</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse ($pengadaan as $index => $p)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-4 text-center text-gray-500">{{ $pengadaan->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center text-gray-900 font-bold">{{ $p->sparepart->nama_sparepart ?? '-' }}</td>
                            <td class="px-5 py-4 text-center text-gray-500 font-normal">{{ \Carbon\Carbon::parse($p->tgl_pesan)->translatedFormat('d F Y') }}</td>
                            <td class="px-5 py-4 text-center font-medium text-gray-700">{{ $p->jumlah }} Pcs</td>
                            <td class="px-5 py-4 text-center text-blue-600 font-semibold">Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>
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
                                <div class="flex justify-center gap-1.5">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('admin.pengadaan_sparepart.detail', $p->id_pengadaan) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all"
                                        title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.pengadaan_sparepart.edit', $p->id_pengadaan) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                            
                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.pengadaan_sparepart.delete', $p->id_pengadaan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus data pengadaan ini?')"
                                        class="inline-block m-0">
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
                            <td colspan="8" class="text-center py-12 text-gray-400 font-medium italic">
                                Belum ada data pengadaan sparepart.
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