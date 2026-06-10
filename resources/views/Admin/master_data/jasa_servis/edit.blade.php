@extends('layouts.layout')

@section('title', 'Edit Jasa Servis')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Jasa Servis</h2>
        <p class="text-gray-500 mt-1">Perbarui data tarif dan nama jasa servis yang tersedia di sistem.</p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Form Header with Gradient --}}
        <div class="px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-xl font-bold text-white">Form Edit Jasa Servis</h3>
        </div>

        <form method="POST" action="{{ route('admin.jasa_servis.update', $jasa->id_jasa) }}">
            @csrf
            @method('PUT')
            <div class="p-8 space-y-6">
                {{-- Nama Jasa --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Jasa Servis</label>
                    <input type="text" name="nama_jasa" value="{{ $jasa->nama_jasa }}" required placeholder="Masukkan nama jasa servis" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Harga --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Biaya / Harga Jasa (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-semibold border-r pr-3 border-gray-200">Rp</span>
                        <input type="number" name="harga" value="{{ $jasa->harga }}" min="0" required placeholder="0" class="w-full border border-gray-200 rounded-xl p-3 pl-16 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi (Footer) --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.jasa_servis.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Kembali</a>
                <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/20 transition">Update Jasa</button>
            </div>
        </form>
    </div>
@endsection