@extends('layouts.layout')

@section('title', 'Detail User')

@section('content')

<div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Header / Cover --}}
    <div class="relative h-28 bg-gradient-to-r from-blue-600 to-indigo-700">
        <a href="{{ route('owner.users.index') }}" class="absolute top-4 left-4 text-white/80 hover:text-white p-2 hover:bg-white/10 rounded-full transition-all" title="Kembali">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    {{-- Profile Section --}}
    <div class="px-6 pb-8 text-center">
        {{-- Avatar Inisial --}}
        <div class="relative -mt-14 mb-4 inline-block">
            <div class="inline-flex items-center justify-center w-28 h-28 bg-white rounded-full border-4 border-white shadow-xl text-indigo-600 text-4xl font-black uppercase tracking-tighter">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
        </div>

        {{-- Nama & Role --}}
        <div class="mb-8">
            <h3 class="text-2xl font-black text-gray-800 leading-tight mb-1">{{ $user->nama }}</h3>
            <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 uppercase tracking-widest border border-indigo-100">
                {{ str_replace('_', ' ', $user->role) }}
            </span>
        </div>

        {{-- Info Cards --}}
        <div class="space-y-4 text-left">
            {{-- Email --}}
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <div class="flex-shrink-0 w-10 h-10 bg-white shadow-sm rounded-xl flex items-center justify-center text-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Alamat Email</p>
                    <p class="text-sm font-bold text-gray-700 truncate">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- No HP --}}
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">No. Handphone</p>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.67-1.611-.918-2.208-.242-.581-.487-.503-.67-.512-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        <p class="text-sm font-bold text-gray-700">{{ $user->no_hp }}</p>
                    </div>
                </div>
                {{-- Status --}}
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-center">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Status Akun</p>
                    <div class="flex items-center gap-2">
                        @if($user->status === 'aktif')
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <span class="text-sm font-bold text-green-600 uppercase tracking-widest">Aktif</span>
                        @else
                            <span class="relative flex h-2 w-2">
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('owner.users.index') }}" class="block text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold text-sm transition-all">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

@endsection