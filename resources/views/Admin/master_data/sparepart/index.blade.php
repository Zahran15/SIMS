@extends('layouts.layout')

@section('title', 'Data Sparepart - Admin')

@section('content')
    {{-- HEADER SECTION --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Sparepart</h2>
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

    {{-- FILTER --}}
    <div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
        <form action="{{ route('admin.sparepart.index') }}" method="GET">
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
                    <a href="{{ route('admin.sparepart.index') }}"
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
                            <td class="px-5 py-4 text-center text-gray-600 font-medium">{{ $s->kategori }}</td>
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
                                    @if(!($s->stok == 0 && $s->harga_jual == 0 && $s->status == 'tidak tersedia'))
                                    <a href="{{ route('admin.sparepart.edit', $s->id_sparepart) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                        title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif
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
        {{ $sparepart->appends(request()->query())->links() }}
    </div>

    <script>
        const formsHapus = document.querySelectorAll('.form-hapus');
        formsHapus.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data sparepart ini akan dihapus permanen!",
                    icon: 'warning', 
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626', 
                    cancelButtonColor: '#6b7280',  
                    confirmButtonText: 'Ya, Hapus saja!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            });
        });
    </script>
@endsection