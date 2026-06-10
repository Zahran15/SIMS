@extends('layouts.layout')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        {{-- Banner Background --}}
        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative"></div>

        {{-- Profil Konten --}}
        <div class="px-8 pb-8 text-center">
            {{-- Avatar Lingkaran (Inisial Nama) --}}
            <div class="-mt-14 mb-4 relative z-10">
                <div class="w-28 h-28 mx-auto bg-gradient-to-br from-indigo-50 to-blue-100 rounded-full border-4 border-white shadow-xl flex items-center justify-center text-indigo-600 text-4xl font-black tracking-wider">
                    {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
                </div>
            </div>

            {{-- Nama & Kode Pelanggan --}}
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $pelanggan->nama }}</h2>
            <span class="inline-block mt-2 px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-mono font-bold tracking-wide">{{ $pelanggan->kode_pelanggan }}</span>

            {{-- Grid Informasi Detail --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                {{-- Email --}}
                <div class="p-4 bg-gray-50/70 border border-gray-100 rounded-xl transition hover:bg-gray-50">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email</p>
                    <p class="font-semibold text-gray-700 break-all">{{ $pelanggan->email }}</p>
                </div>

                {{-- No HP --}}
                <div class="p-4 bg-gray-50/70 border border-gray-100 rounded-xl transition hover:bg-gray-50">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">No HP</p>
                    <p class="font-semibold text-gray-700">{{ $pelanggan->no_hp }}</p>
                </div>

                {{-- Status --}}
                <div class="p-4 bg-gray-50/70 border border-gray-100 rounded-xl transition hover:bg-gray-50 md:col-span-2 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Akun</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide
                            {{ $pelanggan->status == 'aktif' 
                                ? 'bg-green-100 text-green-700' 
                                : 'bg-red-100 text-red-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $pelanggan->status == 'aktif' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ strtoupper($pelanggan->status) }}
                        </span>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="p-4 bg-gray-50/70 border border-gray-100 rounded-xl transition hover:bg-gray-50 md:col-span-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Tempat Tinggal</p>
                    <p class="font-semibold text-gray-700 leading-relaxed">{{ $pelanggan->alamat }}</p>
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-8 pt-2">
                <a href="{{ route('admin.pelanggan.index') }}"
                    class="inline-block w-full sm:w-auto px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition text-center shadow-sm">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection