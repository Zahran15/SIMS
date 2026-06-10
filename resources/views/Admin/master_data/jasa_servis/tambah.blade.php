@extends('layouts.layout')

@section('title', 'Tambah Jasa Servis')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Jasa Servis Baru</h2>
        <p class="text-gray-500 mt-1">Lengkapi data jasa servis baru secara lengkap dan benar.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-xl font-bold text-white">Form Tambah Jasa Servis</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.jasa_servis.store') }}">
            @csrf

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Jasa Servis</label>
                    <input type="text" name="nama_jasa" required placeholder="Masukkan nama jasa servis" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" required placeholder="Masukkan nominal harga (contoh: 50000)" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.jasa_servis.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Kembali</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition">Simpan</button>
            </div>
        </form>
    </div>
@endsection