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

    {{-- FILTER --}}
    <div class="bg-white mb-4 rounded-xl shadow-sm border p-4">
        <form action="{{ route('teknisi.riwayat_pekerjaan.index') }}" method="GET">
            <div class="flex flex-col lg:flex-row gap-4 lg:items-end">
                {{-- Input Pencarian Kode Servis --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Servis</label>
                    <input type="text" name="kode_servis" value="{{ request('kode_servis') }}" placeholder="Masukkan kode servis..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Input Pencarian Nama Pelanggan --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" value="{{ request('nama_pelanggan') }}" placeholder="Masukkan nama pelanggan..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Filter Status --}}
                <div class="min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status_penugasan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status_penugasan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="gagal" {{ request('status_penugasan') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition">Cari</button>
                    <a href="{{ route('teknisi.riwayat_pekerjaan.index') }}" class="flex-1 lg:flex-none px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm font-medium text-center transition">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Badge Filter Aktif --}}
    @if(request('kode_servis') || request('nama_pelanggan') || request('status_penugasan'))
        <div class="mb-4 text-sm text-gray-600 flex flex-wrap gap-1 items-center">
            <span>Filter aktif:</span>
            @if(request('kode_servis'))<span class="px-2 py-0.5 bg-gray-100 rounded text-xs font-semibold text-gray-800">Kode Servis: "{{ request('kode_servis') }}"</span>@endif
            @if(request('nama_pelanggan'))<span class="px-2 py-0.5 bg-gray-100 rounded text-xs font-semibold text-gray-800">Pelanggan: "{{ request('nama_pelanggan') }}"</span>@endif
            @if(request('status_penugasan'))<span class="px-2 py-0.5 bg-gray-100 rounded text-xs font-semibold text-gray-800">Status: {{ ucfirst(request('status_penugasan')) }}</span>@endif
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
                        <td class="px-5 py-4 text-center font-medium">{{ $r->servis->booking->merk_tipe }}</td>
                        <td class="px-5 py-4 text-center font-medium">
                            @if($r->prioritas == 'tinggi')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">Tinggi</span>
                            @elseif($r->prioritas == 'sedang')
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Sedang</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">Rendah</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center font-bold text-green-600">Rp {{ number_format($r->servis->total_biaya, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-center font-medium"><span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">{{ ucfirst($r->status_penugasan) }}</span></td>
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
        {{ $riwayat->appends(request()->query())->links() }}
    </div>
</div>
@endsection