@extends('layouts.layout')

@section('title', 'Perbarui Pengguna')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Perbarui Pengguna
        </h2>
        <p class="text-gray-500 mt-1">
            Ubah informasi akun dan hak akses pengguna yang terdaftar di sistem.
        </p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Form Header with Gradient --}}
        <div class="px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600 flex items-center gap-3">
            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Form Edit Pengguna</h3>
            </div>
        </div>

        <form method="POST" action="{{ route('owner.users.update', $user->id_user) }}">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" placeholder="Nama sesuai KTP" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- Email Aktif --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Aktif</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="nama@email.com" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                </div>

                {{-- No HP & Akses/Status --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="{{ $user->role === 'owner' ? 'md:col-span-2' : '' }}">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. Handphone</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    {{-- Kondisi jika user yang diedit bukan Owner agar role-nya bisa diganti --}}
                    @if($user->role !== 'owner')
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hak Akses (Role)</label>
                            <select name="role" 
                                class="w-full border border-gray-200 rounded-xl p-3 bg-white outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 cursor-pointer">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="teknisi" {{ $user->role == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="role" value="owner">
                    @endif
                </div>

                {{-- Status Akun --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Status Akun
                    </label>
                    <select name="status" 
                        class="w-full border border-gray-200 rounded-xl p-3 bg-white outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 cursor-pointer text-sm font-bold tracking-wide uppercase">
                        <option value="aktif" class="text-green-600" {{ $user->status == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                        <option value="nonaktif" class="text-red-600" {{ $user->status == 'nonaktif' ? 'selected' : '' }}>NONAKTIF</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi (Footer) --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('owner.users.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/20 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection