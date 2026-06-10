@extends('layouts.layout')

@section('title', 'Edit Data Tools')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Data Tool</h2>
        <p class="text-gray-500 mt-1">Perbarui rincian mutasi atau nama alat kerja operasional yang terdaftar di sistem.</p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Form Header with Gradient --}}
        <div class="px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-xl font-bold text-white">Form Edit Alat / Tools</h3>
        </div>

        <form action="{{ route('admin.pengadaan_tools.update', $tool->id_tools) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-8 space-y-6">
                {{-- Teknisi --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Teknisi Pemegang <span class="text-red-500">*</span></label>
                    <select name="id_user" required class="w-full border border-gray-200 rounded-xl p-3 bg-white outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 cursor-pointer @error('id_user') border-red-500 focus:ring-red-100 @enderror">
                        @foreach($teknisi as $tk)
                            <option value="{{ $tk->id }}" {{ old('id_user', $tool->id_user) == $tk->id ? 'selected' : '' }}>{{ $tk->nama }}</option>
                        @endforeach
                    </select>
                    @error('id_user') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Tools & Jumlah Quantity (Grid 2 Kolom) --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Alat / Tools <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_tools" value="{{ old('nama_tools', $tool->nama_tools) }}" placeholder="Contoh: Tang Kombinasi" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 @error('nama_tools') border-red-500 focus:ring-red-100 @enderror">
                        @error('nama_tools') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah Unit <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', $tool->jumlah) }}" min="0" placeholder="0" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 @error('jumlah') border-red-500 focus:ring-red-100 @enderror">
                        @error('jumlah') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi (Footer) --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.pengadaan_tools.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/20 transition">Perbarui Alat</button>
            </div>
        </form>
    </div>
@endsection