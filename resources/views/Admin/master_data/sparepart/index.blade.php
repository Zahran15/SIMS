@extends('layouts.layout')

@section('title', 'Data Sparepart - Admin')

@section('content')
    {{-- HEADER SECTION --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Master Sparepart</h2>
            <p class="text-gray-500 mt-1">Kelola seluruh katalog suku cadang, kategori, harga, dan kontrol stok gudang.</p>
        </div>

        {{-- 💡 FIX: Diubah dari pemicu modal ke Link Halaman Mandiri --}}
        <a href="{{ route('admin.sparepart.create') }}"
            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Tambah Sparepart
        </a>
    </div>

    {{-- FLASH ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 font-medium border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE SECTION --}}
    <div class="bg-white mb-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                {{-- HEADER --}}
                <thead class="bg-blue-600 text-xs uppercase text-white">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Nama Sparepart</th>
                        <th class="px-5 py-4 text-center">Kategori</th>
                        <th class="px-5 py-4 text-center">Stok</th>
                        <th class="px-5 py-4 text-center">Harga Jual</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse ($sparepart as $index => $s)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-5 py-4 text-center text-gray-500">{{ $sparepart->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center font-medium text-gray-900">{{ $s->nama_sparepart }}</td>
                            <td class="px-5 py-4 text-center text-gray-600 font-normal">{{ $s->kategori }}</td>
                            <td class="px-5 py-4 text-center font-medium">
                                @if($s->stok <= 5)
                                    <span class="text-center text-red-600 font-medium flex items-center justify-center gap-1" title="Stok kritis!">{{ $s->stok }} Pcs</span>
                                @else
                                    <span class="text-center text-gray-700">{{ $s->stok }} Pcs</span>
                                @endif
                            </td>
                            
                            <td class="px-5 py-4 text-center font-medium text-green-600 font-bold">Rp {{ number_format($s->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-medium border whitespace-nowrap {{ $s->status == 'tersedia' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">{{ ucfirst($s->status) }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.sparepart.edit', $s->id_sparepart) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                        title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.sparepart.delete', $s->id_sparepart) }}" 
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini dari master katalog?')" 
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
                            <td colspan="7" class="text-center py-12 text-gray-400 italic">
                                Belum ada data sparepart di gudang.
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
</div>
@endsection