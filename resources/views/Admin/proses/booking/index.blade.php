@extends('layouts.layout')

@section('title', 'Data Booking')

@section('content')

{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Booking</h2>
        <p class="text-gray-500 mt-1">
            Kelola seluruh data booking servis pelanggan
        </p>
    </div>

    {{-- BUTTON TAMBAH --}}
    <a href="{{ route('admin.booking.create') }}"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
        Tambah Booking 
    </a>
</div>

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-green-100 border border-green-200 text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- FILTER & SEARCH BOOKING --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.booking.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            {{-- Input Pencarian Nama Pelanggan --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pelanggan</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Masukkan nama pelanggan..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            {{-- Filter Kategori Servis --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Servis</label>
                <select name="kategori_servis"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[180px] text-sm">
                    <option value="">Semua Kategori</option>
                    <option value="ringan" {{ request('kategori_servis') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                    <option value="sedang" {{ request('kategori_servis') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="berat" {{ request('kategori_servis') == 'berat' ? 'selected' : '' }}>Berat</option>
                </select>
            </div>
            {{-- Filter Status Dp --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Dp</label>
                <select name="status_dp"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[180px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="sudah lunas" {{ request('status_dp') == 'sudah lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                    <option value="belum lunas" {{ request('status_dp') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>
            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition font-medium">
                    Cari
                </button>
                <a href="{{ route('admin.booking.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition font-medium">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('search') || request('kategori_servis') || request('status_deposit'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan hasil filter
        @if(request('search')) Nama <span class="font-semibold text-gray-800">"{{ request('search') }}"</span> @endif
        @if(request('kategori_servis')) Kategori <span class="font-semibold text-gray-800">{{ ucfirst(request('kategori_servis')) }}</span> @endif
        @if(request('status_dp')) Deposit <span class="font-semibold text-gray-800">{{ ucfirst(request('status_dp')) }}</span> @endif
    </div>
@endif

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            {{-- TABLE HEADER --}}
            <thead class="bg-blue-600 text-xs uppercase text-white">
                <tr>
                    <th class="px-5 py-5 text-center">No</th>
                    <th class="px-5 py-5 text-center">Kode Booking</th>
                    <th class="px-5 py-5 text-center">Pelanggan</th>
                    <th class="px-5 py-5 text-center">Tgl Booking</th>
                    <th class="px-5 py-5 text-center">Merk/Tipe</th>
                    <th class="px-5 py-5 text-center">Kategori</th>
                    <th class="px-5 py-5 text-center">Status Dp</th>
                    <th class="px-5 py-5 text-center">Status Booking</th>
                    <th class="px-5 py-5 text-center">Aksi</th>
                </tr>
            </thead>

            {{-- TABLE BODY --}}
            <tbody>
                @forelse ($booking as $index => $b)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-5 text-center">{{ $booking->firstItem() + $index }}</td>
                        <td class="px-5 py-5 text-center font-bold text-blue-600">{{ $b->kode_booking }}</td>
                        <td class="px-5 py-5 text-center font-medium text-gray-900">{{ $b->pelanggan->nama ?? '-' }}</td>
                        <td class="px-5 py-5 text-center">{{ $b->tgl_booking }}</td>
                        <td class="px-5 py-5 text-center">{{ $b->merk_tipe }}</td>
                        <td class="px-5 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($b->kategori_servis == 'ringan')
                                    bg-green-100 text-green-700
                                @elseif($b->kategori_servis == 'sedang')
                                    bg-yellow-100 text-yellow-700
                                @else
                                    bg-red-100 text-red-700
                                @endif
                                ">
                                {{ ucfirst($b->kategori_servis) }}
                            </span>
                        </td>
                        <td class="px-5 py-5 text-center">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap
                                {{ $b->status_dp == 'sudah lunas'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700'
                                }}">
                                {{ ucfirst($b->status_dp) }}
                            </span>
                        </td>

                        <td class="px-5 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($b->status_booking == 'pending')
                                    bg-yellow-100 text-yellow-700
                                @elseif($b->status_booking == 'diterima')
                                    bg-green-100 text-green-700
                                @else
                                    bg-red-100 text-red-700
                                @endif
                            ">
                                {{ ucfirst($b->status_booking) }}
                            </span>
                        </td>
                        
                        <td class="px-5 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Setuju (Hanya muncul jika pending DAN sudah lunas) --}}
                                @if($b->status_booking == 'pending' && $b->status_dp == 'sudah lunas')
                                    <!-- Hapus onsubmit, tambahkan class 'form-setuju' -->
                                    <form action="{{ route('admin.booking.terima', $b->id_booking) }}" 
                                          method="POST" 
                                          class="form-setuju inline-block m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all"
                                                title="Setujui Booking">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                {{-- Tombol Detail --}}
                                <a href="{{ route('admin.booking.show', $b->id_booking) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                    title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.booking.edit', $b->id_booking) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                {{-- Tombol Delete --}}
                                <form action="{{ route('admin.booking.destroy', $b->id_booking) }}" 
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
                        <td colspan="9"
                            class="text-center py-10 text-gray-500 italic">
                            Belum ada data booking
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
<div class="mt-5">
    {{ $booking->appends(request()->query())->links() }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. LOGIKA UNTUK TOMBOL SETUJU
        const formsSetuju = document.querySelectorAll('.form-setuju');
        formsSetuju.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Status booking akan diubah menjadi 'Diterima'!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',  
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // 2. LOGIKA UNTUK TOMBOL HAPUS
        const formsHapus = document.querySelectorAll('.form-hapus');
        formsHapus.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Tahan submit asli
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data booking ini akan dihapus permanen!",
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
    });
</script>

@endsection