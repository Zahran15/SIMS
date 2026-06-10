@extends('layouts.layout')

@section('title', 'Edit Pelanggan')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Pelanggan</h2>
        <p class="text-gray-500 mt-1">Perbarui data pelanggan yang sudah terdaftar di sistem.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-xl font-bold text-white">Form Edit Pelanggan</h3>
        </div>

        <form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan->id_pelanggan) }}">
            @csrf
            @method('PUT')
            <div class="p-8 space-y-6">
                {{-- Kode Pelanggan (Readonly) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kode Pelanggan</label>
                    <input type="text" value="{{ $pelanggan->kode_pelanggan }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-500 font-mono cursor-not-allowed focus:outline-none">
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $pelanggan->nama }}" required placeholder="Masukkan nama lengkap" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" value="{{ $pelanggan->email }}" required placeholder="contoh@email.com" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- No HP & Status --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No HP</label>
                        <input type="text" name="no_hp" value="{{ $pelanggan->no_hp }}" required placeholder="08xxxxxxxxxx" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl p-3 bg-white outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 cursor-pointer">
                            <option value="aktif" {{ $pelanggan->status == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                            <option value="nonaktif" {{ $pelanggan->status == 'nonaktif' ? 'selected' : '' }}>NONAKTIF</option>
                        </select>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" required placeholder="Tuliskan alamat lengkap..." class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 resize-none">{{ $pelanggan->alamat }}</textarea>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.pelanggan.index') }}"class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Kembali</a>
                <button type="submit"class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/20 transition">Update</button>
            </div>
        </form>
    </div>
@endsection