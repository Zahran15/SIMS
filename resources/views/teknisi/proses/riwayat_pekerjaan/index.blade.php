@extends('layouts.layout')

@section('title', 'Riwayat Pekerjaan')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Riwayat Pekerjaan</h2>
        <p class="text-gray-500 mt-1">Daftar pekerjaan servis yang telah selesai</p>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                {{-- HEADER --}}
                <thead class="bg-blue-600 text-white text-sm">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Kode</th>
                        <th class="px-5 py-4 text-center">Pelanggan</th>
                        <th class="px-5 py-4 text-center">Device</th>
                        <th class="px-5 py-4 text-center">Prioritas</th>
                        <th class="px-5 py-4 text-center">Total</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($riwayat as $index => $r)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-4 text-center">{{ $riwayat->firstItem() + $index }}</td>
                        <td class="px-5 py-4 text-center font-bold text-blue-600">{{ $r->servis->kode_servis }}</td>
                        <td class="px-5 py-4 text-center">
                            <div class="font-semibold">{{ $r->servis->booking->pelanggan->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $r->servis->booking->kode_booking }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">{{ $r->servis->booking->merk_tipe }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($r->prioritas == 'tinggi')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">Tinggi</span>
                            @elseif($r->prioritas == 'sedang')
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Sedang</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">Rendah</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center font-bold text-green-600">Rp {{ number_format($r->servis->total_biaya, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-center"><span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">{{ ucfirst($r->status_penugasan) }}</span></td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center">
                                <a href="{{ route('teknisi.riwayat_pekerjaan.detail', $r->id_penugasan) }}" 
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Detail">
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
                        <td colspan="8" class="text-center py-10 text-gray-400">
                            Belum ada riwayat pekerjaan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $riwayat->links() }}
    </div>
</div>
@endsection