@extends('layouts.layout')

@section('title', 'Persetujuan Request Sparepart - Pelanggan')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Persetujuan Penggantian Sparepart</h2>
        <p class="text-gray-500 mt-1">Silakan periksa dan berikan persetujuan terhadap pengajuan komponen/sparepart untuk perbaikan perangkat Anda.</p>
    </div>

    {{-- NOTIFIKASI / FLASH MESSAGE --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 font-medium shadow-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-100 text-red-700 font-medium shadow-sm border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
        <form action="{{ route('pelanggan.request_sparepart.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4 md:items-end">
                {{-- Filter Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status_request" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[250px] text-sm bg-white">
                        <option value="">Semua Riwayat</option>
                        <option value="dikirim_ke_pelanggan" {{ request('status_request') == 'dikirim_ke_pelanggan' ? 'selected' : '' }}>Menunggu Persetujuan Anda</option>
                        <option value="disetujui_pelanggan" {{ request('status_request') == 'disetujui_pelanggan' ? 'selected' : '' }}>Telah Anda Setujui</option>
                        <option value="disetujui" {{ request('status_request') == 'disetujui' ? 'selected' : '' }}>Selesai / Diproses Admin</option>
                        <option value="ditolak" {{ request('status_request') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Badge Filter Aktif --}}
    @if(request('status_request'))
        <div class="mb-4 text-sm text-gray-600">
            Menampilkan data filter: Status <span class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', request('status_request'))) }}</span>
        </div>
    @endif

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                {{-- THEAD --}}
                <thead class="bg-blue-600 text-white shadow-sm">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Perangkat / Kode Servis</th>
                        <th class="px-5 py-4 text-center">Sparepart</th>
                        <th class="px-5 py-4 text-center">Jumlah</th>
                        <th class="px-5 py-4 text-center">Alasan Pergantian</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi / Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($requestSparepart as $index => $r)
                    <tr class="hover:bg-gray-50/70 transition-all">
                        <td class="px-5 py-4 text-center text-gray-500">{{ $requestSparepart->firstItem() + $index }}</td>
                        <td class="px-5 py-4 text-center font-bold text-blue-600">
                            {{ $r->penugasan->servis->kode_servis ?? '-' }}
                            <span class="block text-xs font-normal text-gray-400 mt-0.5">{{ $r->penugasan->servis->booking->nama_perangkat ?? 'Perangkat' }}</span>
                        </td>
                        <td class="px-5 py-4 text-center text-gray-800">{{ $r->sparepart->nama_sparepart ?? '-' }}</td>
                        <td class="px-5 py-4 text-center text-gray-600 font-semibold">{{ $r->jumlah }} Pcs</td>
                        <td class="px-5 py-4 text-center text-gray-500 font-normal max-w-xs truncate" title="{{ $r->alasan }}">{{ $r->alasan }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($r->status_request == 'dikirim_ke_pelanggan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">Butuh Persetujuan</span>
                            @elseif($r->status_request == 'disetujui_pelanggan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">Sudah Anda ACC</span>
                            @elseif($r->status_request == 'disetujui')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Diproses Admin</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- TOMBOL DETAIL --}}
                                <a href="{{ route('pelanggan.request_sparepart.detail', $r->id_request) }}" 
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                                   title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- AKSI PERSETUJUAN (Hanya muncul jika status dikirim_ke_pelanggan) --}}
                                @if($r->status_request == 'dikirim_ke_pelanggan')
                                    {{-- FORM SETUJUI --}}
                                    <form action="{{ route('pelanggan.request_sparepart.approve', $r->id_request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" 
                                                title="Setujui Penggantian Komponen"
                                                onclick="return confirm('Apakah Anda menyetujui penggantian komponen ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- FORM TOLAK --}}
                                    <form action="{{ route('pelanggan.request_sparepart.reject', $r->id_request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" 
                                                title="Tolak Penggantian Komponen"
                                                onclick="return confirm('Apakah Anda ingin menolak pergantian sparepart ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400 font-medium">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <span>Belum ada pengajuan sparepart untuk perangkat Anda saat ini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($requestSparepart->hasPages())
        <div class="mt-5">
            {{ $requestSparepart->appends(request()->query())->links() }}
        </div>
    @endif
@endsection