@extends('layouts.layout')

@section('title','Kerjakan Servis')

@section('content')
@php
    $penugasanAktif = $penugasan; 
@endphp

<div class="p-6">
    <div class="mb-6">
        <h2 class="text-3xl font-bold">Kerjakan Servis</h2>
        <p class="text-gray-500">Update progres pengerjaan servis</p>
    </div>

    {{-- INFO SERVIS --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="text-gray-500 text-sm">Kode Servis</label>
            <div class="font-bold text-gray-800 text-lg">{{ $servis->kode_servis }}</div>
            </div>
            <div><label class="text-gray-500 text-sm">Pelanggan</label>
            <div class="font-bold text-gray-800 text-lg">{{ $servis->booking->pelanggan->nama }}</div>
            </div>
            <div><label class="text-gray-500 text-sm">Device</label>
            <div class="font-bold text-gray-800 text-lg">{{ $servis->booking->merk_tipe }}</div>
            </div>
            <div><label class="text-gray-500 text-sm">Status Saat Ini</label>
            <div class="font-bold text-blue-600 text-lg">{{ ucfirst($servis->status_servis) }}</div>
            </div>
            
            @if($penugasanAktif)
            <div>
                <label class="text-gray-500 text-sm">Prioritas Tugas</label>
                <div class="font-bold text-gray-800">
                    <span class="px-3 py-1 text-xs rounded-full 
                        {{ $penugasanAktif->prioritas == 'tinggi' || $penugasanAktif->prioritas == 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $penugasanAktif->prioritas == 'sedang' || $penugasanAktif->prioritas == 'normal' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $penugasanAktif->prioritas == 'rendah' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ ucfirst($penugasanAktif->prioritas) }}
                    </span>
                </div>
            </div>
            
            <div>
                <label class="text-gray-500 text-sm">Status Penugasan</label>
                <div class="font-bold text-gray-800">{{ ucfirst($penugasanAktif->status_penugasan) }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- FORM UPDATE --}}
    @if($penugasanAktif)
<form action="{{ route('teknisi.servis_kerja.updateStatus', $penugasan->id_penugasan) }}" method="POST">
    @csrf
    @method('PUT')
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="mb-5">
                <label class="block mb-2 font-semibold text-gray-700">Status Pengerjaan Anda</label>
                <select name="status_penugasan" class="w-full border rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="belum dikerjakan" {{ $penugasanAktif->status_penugasan == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                    <option value="sedang dikerjakan" {{ $penugasanAktif->status_penugasan == 'sedang dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="menunggu sparepart" {{ $penugasanAktif->status_penugasan == 'menunggu sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                    <option value="selesai" {{ $penugasanAktif->status_penugasan == 'selesai' ? 'selected' : '' }}>Selesai / Berhasil</option>
                    <option value="gagal" {{ $penugasanAktif->status_penugasan == 'gagal' ? 'selected' : '' }}>Gagal / Tidak Bisa Diperbaiki</option>
                </select>
            </div>
            <div>
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
        <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50" role="alert"><span class="font-medium">Peringatan!</span> Anda tidak memiliki hak akses atau tugas untuk mengupdate servis ini.</div>
        <div class="mt-6 flex justify-end"><a href="{{ route('teknisi.servis_kerja.index') }}" class="px-5 py-3 border rounded-xl hover:bg-gray-50 transition">Kembali</a></div>
        @endif
    </div>
@endsection