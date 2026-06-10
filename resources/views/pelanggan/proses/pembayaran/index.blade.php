@extends('layouts.layout')

@section('title', 'Data Pembayaran')

@section('content')

{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Pembayaran</h2>
        <p class="text-gray-500 mt-1">Pantau dan kelola seluruh riwayat transaksi pembayaran Anda</p>
    </div>
</div>

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-green-100 border border-green-200 text-green-700">
        {{ session('success') }}
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
                    <th class="px-5 py-5 text-center">Jenis Pembayaran</th>
                    <th class="px-5 py-5 text-center">Nominal</th>
                    <th class="px-5 py-5 text-center">Status</th>
                    <th class="px-5 py-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pembayaran as $item)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-5 text-center">{{ $loop->iteration }}</td>
                        <td class="px-5 py-5 text-center font-medium text-gray-800">{{ ucfirst($item->jenis_pembayaran) }}</td>
                        <td class="px-5 py-5 text-center font-bold text-gray-800">Rp {{ number_format($item->nominal,0,',','.') }}</td>
                        <td class="px-5 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($item->status_pembayaran == 'sukses')
                                    bg-green-100 text-green-700
                                @elseif($item->status_pembayaran == 'pending')
                                    bg-yellow-100 text-yellow-700
                                @else
                                    bg-red-100 text-red-700
                                @endif
                                ">
                                @if($item->status_pembayaran == 'sukses')
                                    Lunas
                                @elseif($item->status_pembayaran == 'pending')
                                    Pending
                                @else
                                    Gagal
                                @endif
                            </span>
                        </td>

                        <td class="px-5 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('pelanggan.pembayaran.detail', $item->id_pembayaran) }}"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                   title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500 italic">
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
    {{ $pembayaran->links() }}
</div>

@endsection