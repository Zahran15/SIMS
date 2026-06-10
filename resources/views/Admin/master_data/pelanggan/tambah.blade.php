@extends('layouts.layout')

@section('title', 'Tambah Pelanggan')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Pelanggan Baru</h2>
        <p class="text-gray-500 mt-1">Lengkapi data pelanggan baru secara lengkap dan benar.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-xl font-bold text-white">Form Tambah Pelanggan</h3>
        </div>
        <form method="POST" action="{{ route('admin.pelanggan.store') }}">
            @csrf

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kode Pelanggan</label>
                    <input type="text" name="kode_pelanggan" value="{{ $kode }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-500 font-mono cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Masukkan nama lengkap" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" required placeholder="contoh@email.com" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No HP</label>
                        <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" required placeholder="Tuliskan alamat lengkap rumah..." class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 resize-none"></textarea>
                </div>
                <input type="hidden" name="status" value="aktif">
            </div>

            {{-- Tombol Aksi --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.pelanggan.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Kembali</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition">Simpan</button>
            </div>
        </form>
    </div>
@endsection