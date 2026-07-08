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

{{-- KARTU STATISTIK USER --}}
@php
$user_stats = [
    ['title' => 'Total Pelanggan',  'total' => $total_pelanggan . ' Pengguna', 'icon'  => 'fa-users', 'color' => 'blue', 'label' => 'Semua Akun'],
    ['title' => 'Pelanggan Aktif', 'total' => $pelanggan_aktif . ' Akun', 'icon'  => 'fa-user-check', 'color' => 'emerald', 'label' => 'Status Aktif'],
    ['title' => 'Pelanggan Nonaktif', 'total' => $pelanggan_nonaktif . ' Akun', 'icon'  => 'fa-user-slash', 'color' => 'red', 'label' => 'Status Nonaktif'],
];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    @foreach($user_stats as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all group duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] uppercase text-gray-400 font-medium tracking-wider mb-1">{{ $stat['title'] }}</p>
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

{{-- FILTER & SEARCH --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.pelanggan.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            
            {{-- Input Pencarian Kode Booking --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pelanggan</label>
                <input type="text" name="kode_pelanggan" value="{{ request('kode_pelanggan') }}" placeholder="Masukkan kode pelanggan..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Input Pencarian Nama Pelanggan --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" value="{{ request('nama_pelanggan') }}" placeholder="Masukkan nama pelanggan..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    Cari
                </button>

                <a href="{{ route('admin.pelanggan.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('kode_pelanggan') || request('nama_pelanggan') || request('status'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter: 
        @if(request('kode_pelanggan'))Kode Pelanggan<span class="font-semibold text-gray-800">"{{ request('kode_pelanggan') }}"</span>@endif
        @if(request('nama_pelanggan'))Nama Pelanggan<span class="font-semibold text-gray-800">"{{ request('nama_pelanggan') }}"</span>@endif             
        @if(request('status')) Status <span class="font-semibold text-gray-800">{{ ucfirst(request('status')) }}</span> @endif
    </div>
@endif

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
    {{ $pelanggan->appends(request()->query())->links() }}
</div>

<script>
        // 2. LOGIKA UNTUK TOMBOL HAPUS
        const formsHapus = document.querySelectorAll('.form-hapus');
        formsHapus.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pelanggan ini akan dihapus permanen!",
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