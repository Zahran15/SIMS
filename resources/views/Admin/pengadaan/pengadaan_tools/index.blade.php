@extends('layouts.layout')

@section('title', 'Kelola Data Tools - Admin')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- HEADER --}}
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Kelola Data Tools</h2>
        <p class="text-gray-500 mt-1">Daftar alat kerja teknisi Seven Komputer</p>
    </div>

    <a href="{{ route('admin.pengadaan_tools.create') }}"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
        Tambah Tools
    </a>
</div>

{{-- FILTER --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.pengadaan_tools.index') }}" method="GET">
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

                <a href="{{ route('admin.pengadaan_tools.index') }}"
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
                    <th class="px-5 py-4 text-center">Aksi</th>
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
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- EDIT --}}
                                <a href="{{ route('admin.pengadaan_tools.edit', $t->id_tools) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                
                                {{-- DELETE --}}
                                <form action="{{ route('admin.pengadaan_tools.delete', $t->id_tools) }}"
                                    method="POST"
                                    class="form-hapus inline-block m-0">
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
                        <td colspan="6" class="text-center py-10 text-gray-500 italic bg-gray-50/50">
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

    <script>
        const formsHapus = document.querySelectorAll('.form-hapus');
        formsHapus.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data tools ini akan dihapus permanen!",
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