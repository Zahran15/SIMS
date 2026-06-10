@extends('layouts.layout')

@section('title', 'Penugasan Teknisi')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Penugasan Teknisi</h2>
            <p class="text-gray-500 mt-1">Kelola penugasan teknisi untuk setiap servis</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-indigo-600 text-xs uppercase text-white">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Kode Servis</th>
                        <th class="px-5 py-4 text-center">Pelanggan</th>
                        <th class="px-5 py-4 text-center">Status Servis</th>
                        <th class="px-5 py-4 text-center">Teknisi</th>
                        <th class="px-5 py-4 text-center">Prioritas</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servis as $index => $s)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-center">{{ $servis->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center font-bold text-gray-800">{{ $s->kode_servis }}</td>
                            <td class="px-5 py-4 text-center">{{ $s->booking->pelanggan->nama ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'menunggu' => 'bg-gray-100 text-gray-600',
                                        'proses'   => 'bg-blue-100 text-blue-600',
                                        'selesai'  => 'bg-green-100 text-green-600',
                                    ];
                                    $class = $statusClasses[$s->status_servis] ?? 'bg-red-100 text-red-600';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $class }}">
                                    {{ strtoupper($s->status_servis) }}
                                </span>
                            </td>

                            {{-- TEKNISI --}}
                            <td class="px-5 py-4 text-center">
                                @if($s->penugasan)
                                    {{ $s->penugasan->teknisi->nama ?? '-' }}
                                @else
                                    <span class="text-red-500 italic text-xs">Belum ditugaskan</span>
                                @endif
                            </td>

                            {{-- PRIORITAS --}}
                            <td class="px-5 py-4 text-center">
                                @if($s->penugasan)
                                    @php
                                        $prioritasClasses = [
                                            'rendah' => 'bg-gray-100 text-gray-600',
                                            'normal' => 'bg-blue-100 text-blue-600',
                                            'tinggi' => 'bg-orange-100 text-orange-600',
                                            'urgent' => 'bg-red-100 text-red-600',
                                        ];
                                        $pClass = $prioritasClasses[$s->penugasan->prioritas] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pClass }}">
                                        {{ strtoupper($s->penugasan->prioritas) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    @if(!$s->penugasan)
                                        {{-- Tombol Tugaskan (User Plus Icon) --}}
                                        <a href="{{ route('admin.penugasan.create', $s->id_servis) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white transition-all"
                                           title="Tugaskan Teknisi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                        </a>
                                    @else
                                        {{-- Tombol Edit Penugasan --}}
                                        <a href="{{ route('admin.penugasan.edit', $s->penugasan->id_penugasan) }}"
                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        </a>
                            
                                        {{-- Tombol Detail Penugasan (Eye Icon) --}}
                                        <a href="{{ route('admin.penugasan.detail', $s->penugasan->id_penugasan) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" 
                                            title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500 italic">
                                Belum ada data penugasan teknisi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $servis->links() }}
    </div>
</div>
@endsection