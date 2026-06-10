@extends('layouts.layout')

@section('title', 'Servis Proses')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Servis Proses</h2>
            <p class="text-gray-500 mt-1">Data servis yang sedang diproses</p>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-green-100 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-100 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                {{-- HEADER TABLE --}}
                <thead class="bg-blue-600 text-white uppercase text-xs">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Kode Servis</th>
                        <th class="px-5 py-4 text-center">Pelanggan</th>
                        <th class="px-5 py-4 text-center">Device</th>
                        <th class="px-5 py-4 text-center">Tanggal Masuk</th>
                        <th class="px-5 py-4 text-center">Status Servis</th>
                        <th class="px-5 py-4 text-center">Total Biaya</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                {{-- BODY --}}
                <tbody>
                    @forelse ($servis as $index => $s)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-5 py-4 text-center">{{ $servis->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center font-bold text-blue-600">{{ $s->kode_servis }}</td>
                            <td class="px-5 py-4 text-center">
                                <div class="font-semibold text-gray-800">{{ $s->booking->pelanggan->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $s->booking->kode_booking }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">{{ $s->booking->merk_tipe }}</td>
                            <td class="px-5 py-4 text-center">{{ date('d M Y', strtotime($s->tgl_masuk)) }}</td>
                            <td class="px-5 py-4 text-center">
                                {{-- Menampilkan Badge Status Global Utama dari Admin --}}
                                <div class="mb-1">
                                    @if($s->status_servis == 'menunggu')
                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">Menunggu</span>
                                    @elseif($s->status_servis == 'proses')
                                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">Proses</span>
                                    @endif
                                </div>
                            
                                {{-- Indikator Tambahan untuk Memantau Kerja Teknisi --}}
                                @if($s->penugasan && $s->penugasan->status_penugasan == 'selesai')
                                    <div class="flex items-center justify-center gap-1 mt-1 text-xs font-bold text-green-600">
                                        <span>Teknisi Selesai</span>
                                    </div>
                                @elseif($s->penugasan && $s->penugasan->status_penugasan == 'proses')
                                    <div class="flex items-center gap-1 mt-1 text-xs text-gray-400 italic">
                                        <span class="inline-block w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                                        <span>Dikerjakan Teknisi</span>
                                    </div>
                                @else
                                    <div class="text-xs text-gray-400 mt-1 italic">Belum Ada Teknisi</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-green-600">Rp {{ number_format($s->total_biaya, 0, ',', '.') }}</td>
                        {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('admin.servis_proses.detail', $s->id_servis) }}" 
                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                       title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                            
                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.servis_proses.edit', $s->id_servis) }}" 
                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.servis_proses.nota', $s->id_servis) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white transition-all"
                                        title="Print Tanda Terima">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500 italic">
                                Belum ada data servis proses
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $servis->links() }}
    </div>
</div>
@endsection