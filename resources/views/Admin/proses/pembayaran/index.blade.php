@extends('layouts.layout')

@section('title', 'Kelola Pembayaran')

@section('content')
{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Kelola Pembayaran Pelanggan</h2>
        <p class="text-gray-500 mt-1">Daftar semua transaksi pembayaran deposit dan pelunasan.</p>
    </div>
</div>

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-green-100 border border-green-200 text-green-700 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- FILTER & SEARCH KELOLA PEMBAYARAN --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.pembayaran.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            
            {{-- Input Pencarian Nama --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Masukkan nama..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Filter Jenis Pembayaran --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembayaran</label>
                <select name="jenis_pembayaran"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[160px] text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="dp" {{ request('jenis_pembayaran') == 'dp' }}>Dp</option>
                    <option value="pelunasan" {{ request('jenis_pembayaran') == 'pelunasan' }}>Pelunasan</option>
                </select>
            </div>

            {{-- Filter Status Pembayaran --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="status_pembayaran"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[160px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="sukses" {{ request('status_pembayaran') == 'sukses' }}>Lunas</option>
                    <option value="pending" {{ request('status_pembayaran') == 'pending' }}>Pending</option>
                    <option value="gagal" {{ request('status_pembayaran') == 'gagal' }}>Gagal</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition font-medium">Cari</button>
                <a href="{{ route('admin.pembayaran.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition font-medium">Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('search') || request('jenis_pembayaran') || request('status_pembayaran'))
    <div class="mb-4 text-sm text-gray-600 flex flex-wrap gap-1 items-center">
        <span>Menampilkan data filter:</span> 
        @if(request('search')) 
            <span class="px-2 py-0.5 rounded text-xs font-medium">Nama: "{{ request('search') }}"</span> 
        @endif
        @if(request('jenis_pembayaran')) 
            <span class="px-2 py-0.5 rounded text-xs font-medium">Jenis: {{ ucfirst(request('jenis_pembayaran')) }}</span> 
        @endif
        @if(request('status_pembayaran')) 
            <span class="px-2 py-0.5 rounded text-xs font-medium">Status: {{ request('status_pembayaran') == 'sukses' ? 'Lunas' : ucfirst(request('status_pembayaran')) }}</span> 
        @endif
    </div>
@endif

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            {{-- TABLE HEADER --}}
            <thead class="bg-blue-600 text-xs uppercase text-white">
                <tr>
                    <th class="px-5 py-5 text-center">Kode Booking</th>
                    <th class="px-5 py-5 text-center">Pelanggan</th>
                    <th class="px-5 py-5 text-center">Jenis</th>
                    <th class="px-5 py-5 text-center">Metode</th>
                    <th class="px-5 py-5 text-center">Nominal</th>
                    <th class="px-5 py-5 text-center">Status</th>
                    <th class="px-5 py-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pembayaran as $p)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-5 text-center font-semibold text-blue-600">{{ $p->booking->kode_booking }}</td>
                        <td class="px-5 py-5 text-center text-gray-800">{{ $p->booking->pelanggan->nama }}</td>
                        <td class="px-5 py-5 text-center font-medium text-gray-800 uppercase">{{ $p->jenis_pembayaran }}</td>
                        <td class="px-5 py-5 text-center uppercase">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $p->metode_pembayaran == 'cash' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $p->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="px-5 py-5 text-center font-medium text-gray-800">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                        <td class="px-5 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($p->status_pembayaran == 'sukses')
                                    bg-green-100 text-green-700
                                @elseif($p->status_pembayaran == 'pending')
                                    bg-yellow-100 text-yellow-700
                                @else
                                    bg-red-100 text-red-700
                                @endif
                            ">
                                @if($p->status_pembayaran == 'sukses')
                                    Lunas
                                @elseif($p->status_pembayaran == 'pending')
                                    Pending
                                @else
                                    Gagal
                                @endif
                            </span>
                        </td>
                        <td class="px-5 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- BUTTON DETAIL --}}
                                <a href="{{ route('admin.pembayaran.detail', $p->id_pembayaran) }}" 
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                   title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                {{-- BUTTON EDIT --}}
                                @if($p->status_pembayaran != 'sukses')
                                    <a href="{{ route('admin.pembayaran.edit', $p->id_pembayaran) }}" 
                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all" 
                                       title="Edit">
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
                        <td colspan="7" class="text-center py-10 text-gray-500 italic">
                            Tidak ada data pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
<div class="mt-5">
    {{ $pembayaran->appends(request()->query())->links() }}
</div>
@endsection