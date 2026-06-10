@extends('layouts.layout')

@section('title', 'Tambah Data Tools')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Alat / Tools Baru</h2>
        <p class="text-gray-500 mt-1">Daftarkan alat baru beserta penanggung jawab teknisinya secara lengkap.</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-xl font-bold text-white">Form Tambah Alat / Tools</h3>
        </div>

        <form action="{{ route('admin.pengadaan_tools.store') }}" method="POST">
            @csrf

            <div class="p-8 space-y-6">
                {{-- TEKNISI --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Teknisi Pemegang *</label>
                    <select name="id_user" class="w-full border @error('id_user') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 outline-none transition appearance-none bg-no-repeat text-gray-700" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                        <option value="" disabled selected>-- Pilih Teknisi Seven Komputer --</option>
                        @foreach($teknisi as $tk)
                            <option value="{{ $tk->id }}" {{ old('id_user') == $tk->id ? 'selected' : '' }}>{{ $tk->nama }}</option>
                        @endforeach
                    </select>
                    @error('id_user') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- NAMA TOOLS --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Alat / Tools *</label>
                        <input type="text" name="nama_tools" value="{{ old('nama_tools') }}" placeholder="Contoh: Solder IC, Obeng Set" 
                            class="w-full border @error('nama_tools') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 outline-none transition" required>
                        @error('nama_tools') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- JUMLAH QUANTITY --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah Unit (Qty) *</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="0" placeholder="0"
                            class="w-full border @error('jumlah') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 outline-none transition" required>
                        @error('jumlah') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.pengadaan_tools.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition">Simpan Alat</button>
            </div>
        </form>
    </div>
@endsection