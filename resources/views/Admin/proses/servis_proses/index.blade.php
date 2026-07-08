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

{{-- FILTER SERVIS PROSES --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.servis_proses.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            
            {{-- Input Pencarian Kode Servis --}}
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Servis</label>
                <input type="text" name="kode_servis" value="{{ request('kode_servis') }}" placeholder="Kode servis..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Input Pencarian Nama Pelanggan --}}
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" value="{{ request('nama_pelanggan') }}" placeholder="Nama pelanggan..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Filter Status Servis --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Servis</label>
                <select name="status_servis"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[150px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status_servis') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="proses" {{ request('status_servis') == 'proses' ? 'selected' : '' }}>Proses</option>
                </select>
            </div>

            {{-- Filter Status Penugasan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Penugasan</label>
                <select name="status_penugasan"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[170px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="belum dikerjakan" {{ request('status_penugasan') == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                    <option value="sedang dikerjakan" {{ request('status_penugasan') == 'sedang dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="menunggu sparepart" {{ request('status_penugasan') == 'menunggu sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                    <option value="selesai" {{ request('status_penugasan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="gagal" {{ request('status_penugasan') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition font-medium">
                    Cari
                </button>
                <a href="{{ route('admin.servis_proses.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition font-medium">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('kode_servis') || request('nama_pelanggan') || request('status_servis') || request('status_penugasan'))
    <div class="mb-4 text-sm text-gray-600 flex flex-wrap gap-1 items-center"><span>Filter aktif:</span>
        @if(request('kode_servis'))<span class="font-semibold text-gray-800">Kode Servis: "{{ request('kode_servis') }}"</span>@endif
        @if(request('nama_pelanggan'))<span class="font-semibold text-gray-800">Pelanggan: "{{ request('nama_pelanggan') }}"</span>@endif
        @if(request('status_servis'))<span class="font-semibold text-gray-800">Status: {{ ucfirst(request('status_servis')) }}"</span>@endif
        @if(request('status_penugasan'))<span class="font-semibold text-gray-800">Penugasan: {{ request('status_penugasan') }}"</span>@endif
    </div>
@endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                {{-- HEADER TABLE --}}
                <thead class="bg-blue-600 text-white uppercase text-xs">
                    <tr>
                        <th class="px-4 py-4 text-center">No</th>
                        <th class="px-4 py-4 text-center">Kode Servis</th>
                        <th class="px-4 py-4 text-center">Pelanggan</th>
                        <th class="px-4 py-4 text-center">Device</th>
                        <th class="px-4 py-4 text-center">Tanggal Masuk</th>
                        <th class="px-4 py-4 text-center">Status Servis</th>
                        <th class="px-4 py-4 text-center">Penugasan</th>
                        <th class="px-4 py-4 text-center">Total Biaya</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                {{-- BODY --}}
                <tbody>
                    @forelse ($servis as $index => $s)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-4 py-4 text-center">{{ $servis->firstItem() + $index }}</td>
                            <td class="px-4 py-4 text-center font-bold text-blue-600">{{ $s->kode_servis }}</td>
                            <td class="px-4 py-4 text-center">
                                <div class="font-semibold text-gray-800">{{ $s->booking->pelanggan->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $s->booking->kode_booking }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">{{ $s->booking->merk_tipe }}</td>
                            <td class="px-4 py-4 text-center">{{ date('d M Y', strtotime($s->tgl_masuk)) }}</td>
                            <td class="px-4 py-4 text-center">
                                <div class="mb-1">
                                    @if($s->status_servis == 'menunggu')
                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">Menunggu</span>
                                    @elseif($s->status_servis == 'proses')
                                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">Proses</span>
                                    @endif
                                </div>
                            </td>
                            {{-- KOLOM STATUS PENUGASAN --}}
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                @if($s->penugasan)
                                    @if($s->penugasan->status_penugasan == 'belum dikerjakan')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Belum Dikerjakan</span>
                                    @elseif($s->penugasan->status_penugasan == 'sedang dikerjakan')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Sedang Dikerjakan</span>
                                    @elseif($s->penugasan->status_penugasan == 'menunggu sparepart')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Menunggu Sparepart</span>
                                    @elseif($s->penugasan->status_penugasan == 'selesai')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Selesai</span>
                                    @elseif($s->penugasan->status_penugasan == 'gagal')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Gagal</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500 italic">
                                            {{ ucwords($s->penugasan->status_penugasan) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-400 font-medium italic">
                                        Belum Ditugaskan
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-green-600">Rp {{ number_format($s->total_biaya, 0, ',', '.') }}</td>
                            {{-- AKSI --}}
                            <td class="px-4 py-4 text-center">
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
                                    
                                    {{-- BUTTON QUICK ACCEPT / SELESAI --}}
                                    @if($s->status_servis == 'proses' && $s->penugasan && $s->penugasan->status_penugasan == 'selesai')
                                        <form id="form-selesai-{{ $s->id_servis }}" action="{{ route('admin.servis_proses.quick_selesai', $s->id_servis) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="button" 
                                                    onclick="konfirmasiSelesai('{{ $s->id_servis }}', '{{ $s->kode_servis }}')"
                                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all" 
                                                    title="Selesaikan Servis">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                            
                                    {{-- EDIT --}}
                                    @if($s->status_servis == 'proses')
                                        <a href="{{ route('admin.servis_proses.edit', $s->id_servis) }}" 
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                           title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                            
                                    {{-- PRINT NOTA --}}
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
                            <td colspan="9" class="text-center py-10 text-gray-500 italic">
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
        {{ $servis->appends(request()->query())->links() }}
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function konfirmasiSelesai(id, kodeServis) {
        Swal.fire({
            title: 'Selesaikan Servis?',
            text: "Apakah Anda yakin ingin menyelesaikan servis ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981', 
            cancelButtonColor: '#6B7280',  
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal',
            border: 'none',
            borderRadius: '1rem'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-selesai-' + id).submit();
            }
        });
    }
</script>

@endsection