@extends('layouts.layout')

@section('title', 'Edit Data Sparepart - Admin')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Edit Data Sparepart
        </h2>
        <p class="text-gray-500 mt-1">
            Sesuaikan informasi stok, penyesuaian harga jual, atau status barang yang terdaftar di sistem.
        </p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Form Header with Gradient --}}
        <div class="px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-xl font-bold text-white">
                Form Edit Sparepart
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.sparepart.update', $sparepart->id_sparepart) }}">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6">
                {{-- Nama Sparepart --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Sparepart</label>
                    <input type="text" name="nama_sparepart" value="{{ $sparepart->nama_sparepart }}" required placeholder="Masukkan nama sparepart" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Kategori & Stok (Grid 2 Kolom) --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
                        <input type="text" name="kategori" value="{{ $sparepart->kategori }}" required placeholder="Masukkan kategori" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kuantitas Stok Gudang</label>
                        <input type="number" value="{{ $sparepart->stok }}" readonly class="w-full border border-gray-200 rounded-xl p-3 bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                </div>

                {{-- Harga Jual & Status Ketersediaan (Grid 2 Kolom) --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Harga Jual (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-semibold border-r pr-3 border-gray-200">Rp</span>
                            <input type="number"name="harga_jual" value="{{ $sparepart->harga_jual }}" min="0" required placeholder="0" class="w-full border border-gray-200 rounded-xl p-3 pl-16 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Ketersediaan</label>
                        <input type="text" value="{{ $sparepart->stok > 0 ? 'Tersedia' : 'Habis/Kosong' }}" readonly class="w-full border border-gray-200 rounded-xl p-3 pl-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi (Footer) --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.sparepart.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/20 transition">Update Sparepart</button>
            </div>
        </form>
    </div>
@endsection