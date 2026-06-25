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
                {{-- TEKNISI --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Teknisi Pemegang *</label>
                    <select name="id_user" class="w-full border @error('id_user') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-100 @enderror rounded-xl p-3 outline-none transition appearance-none bg-no-repeat text-gray-700" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                        @foreach($teknisi as $tk)
                            <option value="{{ $tk->id_user }}" @selected($tool->id_user == $tk->id_user)>{{ $tk->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- NAMA TOOLS --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Alat / Tools *</label>
                    <input type="text" name="nama_tools" value="{{ $tool->nama_tools }}" placeholder="Contoh: Solder IC, Obeng Set" 
                        class="w-full border @error('nama_tools') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-100 @enderror rounded-xl p-3 outline-none transition" required>
                </div>

                {{-- GRID JUMLAH & STATUS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- JUMLAH QUANTITY --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah Unit (Qty) *</label>
                        <input type="number" name="jumlah" value="{{ $tool->jumlah }}" min="0" placeholder="0"
                            class="w-full border @error('jumlah') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-100 @enderror rounded-xl p-3 outline-none transition" required>
                    </div>

                    {{-- STATUS TOOLS --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Tools *</label>
                        <select name="status" class="w-full border @error('status') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-100 @enderror rounded-xl p-3 outline-none transition appearance-none bg-no-repeat text-gray-700" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                            <option value="tersedia" {{ $tool->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ $tool->status == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
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