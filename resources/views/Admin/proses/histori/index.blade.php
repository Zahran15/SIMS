@extends('layouts.layout')

@section('title', 'Histori Aktivitas')

@section('content')

    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Histori Aktivitas</h2>
            <p class="text-gray-500 mt-1">Riwayat seluruh aktivitas servis</p>
        </div>
    </div>

    {{-- FILTER BULAN & TAHUN --}}
    <div class="mb-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-end gap-4">
            {{-- Dropdown Bulan --}}
            <div class="w-full sm:w-48">
                <label for="bulan" class="block text-xs font-semibold text-gray-600 uppercase mb-2">Bulan</label>
                <select name="bulan" id="bulan" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2.5 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Tahun --}}
            <div class="w-full sm:w-36">
                <label for="tahun" class="block text-xs font-semibold text-gray-600 uppercase mb-2">Tahun</label>
                <select name="tahun" id="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2.5 px-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">-- Semua Tahun --</option>
                    @php
                        $tahunSekarang = date('Y');
                        $tahunAwal = $tahunSekarang - 5; // Menampilkan opsi hingga 5 tahun ke belakang
                    @endphp
                    @for($t = $tahunSekarang; $t >= $tahunAwal; $t--)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    Cari
                </button>
                @if(request('bulan') || request('tahun'))
                    <a href="{{ url()->current() }}" class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-200 text-center transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-green-100 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                {{-- HEAD --}}
                <thead class="bg-blue-600 text-white uppercase text-xs">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Tanggal</th>
                        <th class="px-5 py-4 text-center">Kode Servis</th>
                        <th class="px-5 py-4 text-center">Pelanggan</th>
                        <th class="px-5 py-4 text-center">Aktivitas</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody>
                    @forelse ($histori as $index => $h)
                        <tr class="border-b hover:bg-gray-50 transition-all">
                            <td class="px-5 py-4 text-center">{{ $histori->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center"><div class="font-semibold text-gray-800">{{ date('d M Y', strtotime($h->tanggal)) }}</div></td>
                            <td class="px-5 py-4 text-center"><div class="font-bold text-blue-600">{{ $h->servis->kode_servis ?? '-' }}</div></td>
                            <td class="px-5 py-4 text-center">
                                <div class="font-semibold text-gray-800">{{ $h->servis->booking->pelanggan->nama ?? 'Tidak Diketahui' }}</div>
                                <div class="text-xs text-gray-500">{{ $h->servis->booking->merk_tipe ?? '-' }}</div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                @switch($h->aktivitas)
                                    @case('Penerimaan Unit')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">{{ $h->aktivitas }}</span>
                                        @break
                                    @case('Status: Proses')
                                    @case('Penugasan Teknisi')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $h->aktivitas }}</span>
                                        @break
                                    @case('Servis Selesai')
                                    @case('Status: Selesai')
                                    @case('Status: Sudah Diambil')
                                    @case('Status: Bisa Diambil')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">{{ $h->aktivitas }}</span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ $h->aktivitas }}</span>
                                @endswitch
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('admin.histori.detail', $h->id_histori) }}"
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
                            <td colspan="6" class="text-center py-10 text-gray-500 italic">
                                Belum ada histori aktivitas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{-- .appends(...) memastikan filter tidak hilang saat ganti halaman --}}
        {{ $histori->appends(request()->query())->links() }}
    </div>

@endsection