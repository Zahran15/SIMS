@extends('layouts.layout')

@section('title', 'Tambah Data Sparepart - Admin')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Sparepart Baru</h2>
        <p class="text-gray-500 mt-1">Registrasikan merek komponen atau komoditas baru ke sistem.</p>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-xl font-bold text-white">Form Tambah Sparepart</h3>
        </div>

        <form method="POST" action="{{ route('admin.sparepart.store') }}">
            @csrf
            
            <div class="p-8 space-y-6">
                {{-- NAMA SPAREPART --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Sparepart</label>
                    <input type="text" name="nama_sparepart" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="Contoh: Baterai iPhone 11 Original" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- KATEGORI --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
                        <select name="kategori" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 appearance-none bg-no-repeat" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Layar">Layar/LCD</option>
                            <option value="Baterai">Baterai</option>
                            <option value="Hardware">Hardware</option>
                            <option value="Aksesoris">Aksesoris</option>
                        </select>
                    </div>
                    
                    {{-- STOK AWAL --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Stok Awal Fisik</label>
                        <input type="number" name="stok" min="0" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="0" required>
                    </div>
                </div>

                {{-- HARGA JUAL --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Harga Jual Konsumen</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-semibold border-r pr-3 border-gray-100">Rp</span>
                        <input type="number" name="harga_jual" min="0" class="w-full border border-gray-200 rounded-xl p-3 pl-16 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="0" required>
                    </div>
                </div>

                {{-- HIDDEN STATUS DEFAULT --}}
                <input type="hidden" name="status" value="tersedia">
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.sparepart.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition">Simpan</button>
            </div>
        </form>
    </div>
@endsection