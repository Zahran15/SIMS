@extends('layouts.layout')

@section('title','Tugas Servis')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Tugas Servis</h2>
        <p class="text-gray-500 mt-1">Daftar servis yang ditugaskan ke Anda</p>
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
        <table class="w-full data-table">
            <thead class="bg-blue-600 text-white text-sm">
                <tr>
                    <th class="px-5 py-4 text-center">No</th>
                    <th class="px-5 py-4 text-center">Kode</th>
                    <th class="px-5 py-4 text-center">Pelanggan</th>
                    <th class="px-5 py-4 text-center">Device</th>
                    <th class="px-5 py-4 text-center">Prioritas</th>
                    <th class="px-5 py-4 text-center">Status</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($penugasan as $index=>$p)
                <tr class="border-b hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-center font-medium">{{ $penugasan->firstItem()+$index }}</td>
                    <td class="px-5 py-4 text-center font-bold text-blue-600">{{ $p->servis->kode_servis }}</td>
                    <td class="px-5 py-4 text-center">
                        <div class="font-semibold text-gray-800">{{ $p->servis->booking->pelanggan->nama }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $p->servis->booking->kode_booking }}</div>
                    </td>  
                    <td class="px-5 py-4 text-center text-gray-600">{{ $p->servis->booking->merk_tipe }}</td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        @if($p->prioritas=='tinggi')
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Tinggi</span>
                        @elseif($p->prioritas=='sedang')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Sedang</span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Rendah</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            {{ ucfirst($p->status_penugasan) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center items-center gap-2">
                            {{-- DETAIL --}}
                            <a href="{{ route('teknisi.servis_kerja.detail',$p->id_penugasan) }}"
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            {{-- KERJAKAN/EDIT --}}
                            @if($p->status_penugasan !== 'selesai')
                            <a href="{{ route('teknisi.servis_kerja.edit', $p->id_penugasan) }}"                                    
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400 font-medium">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <span class="text-base">Belum ada penugasan servis</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $penugasan->links() }}
    </div>
</div>
@endsection