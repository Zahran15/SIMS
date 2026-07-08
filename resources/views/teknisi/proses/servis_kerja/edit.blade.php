@extends('layouts.layout')

@section('title','Kerjakan Servis')

@section('content')

@php
    $penugasanAktif = $penugasan; 
@endphp

<div class="mb-6">
    <h2 class="text-3xl font-bold">Kerjakan Servis</h2>
    <p class="text-gray-500">Update progres pengerjaan servis</p>
</div>

{{-- INFO SERVIS --}}
<div class="bg-white rounded-2xl shadow p-6 mb-6 border border-gray-100">
    {{-- Grid Atas: Data Unit & Administrasi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Kode Servis</label>
            <div class="font-bold text-blue-600 text-lg mt-0.5">{{ $servis->kode_servis }}</div>
        </div>
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Pelanggan</label>
            <div class="font-bold text-gray-800 text-lg mt-0.5">{{ $servis->booking->pelanggan->nama }}</div>
        </div>
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Device</label>
            <div class="font-bold text-gray-800 text-lg mt-0.5">{{ $servis->booking->merk_tipe }}</div>
        </div>
        
        {{-- 🆕 KATEGORI SERVIS (FIELD BARU) --}}
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider block mb-1">Kategori Servis</label>
            <span class="font-bold text-indigo-700 uppercase text-xs bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg inline-block">
                {{ $servis->booking->kategori_servis }}
            </span>
        </div>
        
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Status Operasional</label>
            <div class="font-bold text-blue-600 text-lg mt-0.5">{{ ucfirst($servis->status_servis) }}</div>
        </div>
        
        @if($penugasanAktif)
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Status Penugasan</label>
            <div class="font-bold text-gray-800 text-lg mt-0.5">{{ ucfirst($penugasanAktif->status_penugasan) }}</div>
        </div>
        
        <div>
            <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider block mb-1">Prioritas Tugas</label>
            <div class="font-bold text-gray-800">
                <span class="px-3 py-1 text-xs rounded-full font-bold uppercase
                    {{ $penugasanAktif->prioritas == 'berat' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $penugasanAktif->prioritas == 'sedang' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $penugasanAktif->prioritas == 'ringan' || is_null($penugasanAktif->prioritas) ? 'bg-green-100 text-green-700' : '' }}">
                    {{ $penugasanAktif->prioritas ? ucfirst($penugasanAktif->prioritas) : 'Belum Ditentukan' }}
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- 🆕 KELUHAN UTAMA PELANGGAN (FIELD BARU) --}}
    <div class="mt-6 border-t border-gray-100 pt-4">
        <label class="text-red-400 text-xs font-bold uppercase tracking-wider block mb-1.5">Keluhan / Masalah Utama Pelanggan</label>
        <div class="bg-red-50/40 border border-red-100 rounded-xl p-3.5 text-red-950 text-sm font-medium">
            {{ $servis->keluhan ?? $servis->booking->keluhan ?? 'Tidak ada catatan keluhan khusus dari pelanggan.' }}
        </div>
    </div>
</div>

{{-- FORM UPDATE --}}
@if($penugasanAktif)
<form action="{{ route('teknisi.servis_kerja.updateStatus', $penugasan->id_penugasan) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="bg-white rounded-2xl shadow p-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            {{-- Input Status Pengerjaan --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Status Pengerjaan Anda</label>
                <select name="status_penugasan" class="w-full border rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="belum dikerjakan" {{ $penugasanAktif->status_penugasan == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                    <option value="sedang dikerjakan" {{ $penugasanAktif->status_penugasan == 'sedang dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="menunggu sparepart" {{ $penugasanAktif->status_penugasan == 'menunggu sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                    <option value="selesai" {{ $penugasanAktif->status_penugasan == 'selesai' ? 'selected' : '' }}>Selesai / Berhasil</option>
                    <option value="gagal" {{ $penugasanAktif->status_penugasan == 'gagal' ? 'selected' : '' }}>Gagal / Tidak Bisa Diperbaiki</option>
                </select>
            </div>

            {{-- INPUT BARU: EDIT PRIORITAS (Sesuai Enum Migration: ringan, sedang, berat) --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Tingkat Prioritas</label>
                <select name="prioritas" required class="w-full border rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="" {{ is_null($penugasanAktif->prioritas) ? 'selected' : '' }} disabled>-- Pilih Prioritas --</option>
                    <option value="ringan" {{ $penugasanAktif->prioritas == 'ringan' ? 'selected' : '' }}>Ringan</option>
                    <option value="sedang" {{ $penugasanAktif->prioritas == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="berat" {{ $penugasanAktif->prioritas == 'berat' ? 'selected' : '' }}>Berat</option>
                </select>
            </div>
        </div>

        {{-- INPUT ESTIMASI SELESAI --}}
        <div class="mb-5">
            <label class="block mb-2 font-semibold text-gray-700">Estimasi Selesai (Tentukan Target Tanggal)</label>
            <input type="date" name="estimasi_selesai" 
                value="{{ $penugasanAktif->estimasi_selesai ? \Carbon\Carbon::parse($penugasanAktif->estimasi_selesai)->format('Y-m-d') : '' }}"
                class="w-full border rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
            <p class="text-xs text-gray-400 mt-1">*Tentukan target kapan unit laptop ini selesai Anda kerjakan.</p>
        </div>

        {{-- Input Catatan Teknisi --}}
        <div class="mb-2">
            <label class="block mb-2 font-semibold text-gray-700">Catatan Teknisi</label>
            <textarea name="catatan_teknisi" rows="5" class="w-full border rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Tuliskan kendala atau hasil perbaikan perangkat disini...">{{ $penugasanAktif->catatan_teknisi ?? '' }}</textarea>
        </div>
    </div>
    
    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('teknisi.servis_kerja.index') }}" class="px-5 py-3 border rounded-xl hover:bg-gray-50 transition text-center min-w-[100px]">Kembali</a>
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-md hover:shadow-lg transition font-medium min-w-[100px]">Simpan Progres</button>
    </div>
</form>
@else
    <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50" role="alert">
        <span class="font-medium">Peringatan!</span> Anda tidak memiliki hak akses atau tugas untuk mengupdate servis ini.
    </div>
    <div class="mt-6 flex justify-end">
        <a href="{{ route('teknisi.servis_kerja.index') }}" class="px-5 py-3 border rounded-xl hover:bg-gray-50 transition">Kembali</a>
    </div>
@endif
@endsection