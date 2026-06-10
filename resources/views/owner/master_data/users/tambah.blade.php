@extends('layouts.layout')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah User Baru</h2>
        <p class="text-gray-500 mt-1">Lengkapi data untuk membuat akun pengguna aplikasi baru secara lengkap.</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center gap-3">
            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Form Tambah User</h3>
            </div>
        </div>

        <form method="POST" action="{{ route('owner.users.store') }}">
            @csrf

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama lengkap" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 placeholder:text-gray-400" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" placeholder="contoh@email.com" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. Handphone</label>
                        <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hak Akses (Role)</label>
                        <select name="role" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 appearance-none bg-no-repeat text-gray-700 cursor-pointer" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Aktivasi</label>
                        <div class="flex items-center h-[50px] gap-3 px-4 bg-green-50 border border-green-200 rounded-xl">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Aktif</span>
                        </div>
                        <input type="hidden" name="status" value="aktif">
                    </div>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('owner.users.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition">Simpan</button>
            </div>
        </form>
    </div>
@endsection