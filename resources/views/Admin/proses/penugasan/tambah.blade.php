@extends('layouts.layout')

@section('title', 'Tugaskan Teknisi')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Tugaskan Teknisi</h2>
        <p class="text-gray-500 mt-1">Pilih teknisi untuk mengerjakan servis berdasarkan keluhan pelanggan</p>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- HEADER CARD --}}
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-700">
            <h3 class="text-lg font-bold text-white">Form Penugasan Teknisi</h3>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.penugasan.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6 bg-gray-50">
                
                {{-- DATA SERVIS & REFERENSI BOOKING --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4 tracking-wider border-b pb-2">Informasi Perangkat & Pelanggan</h4>
                    
                    {{-- Grid Atas: Administrasi & Pelanggan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Kode Servis</label>
                            <input type="text" value="{{ $servis->kode_servis }}" readonly
                                class="w-full border bg-gray-50 rounded-xl p-3 mt-1 font-semibold text-blue-600 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Nama Pelanggan</label>
                            <input type="text" value="{{ $servis->booking->pelanggan->nama }}" readonly
                                class="w-full border bg-gray-50 rounded-xl p-3 mt-1 font-medium text-gray-700 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Merk / Tipe Perangkat</label>
                            <input type="text" value="{{ $servis->booking->merk_tipe }}" readonly
                                class="w-full border bg-gray-50 rounded-xl p-3 mt-1 font-bold text-gray-800 outline-none">
                        </div>
                    </div>

                    {{-- Grid Tengah: Kategori, Metode, Kelengkapan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Kategori Servis (Estimasi User)</label>
                            <span class="block w-full border bg-gray-50 rounded-xl p-3 mt-1 font-semibold uppercase text-grey-700 text-sm">
                                {{ $servis->booking->kategori_servis }}
                            </span>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Metode Pengembalian</label>
                            <input type="text" value="{{ $servis->booking->metode_pengembalian }}" readonly
                                class="w-full border bg-gray-50 rounded-xl p-3 mt-1 capitalize text-gray-700 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Kelengkapan Unit</label>
                            <input type="text" value="{{ $servis->booking->kelengkapan ?? '-' }}" readonly
                                class="w-full border bg-gray-50 rounded-xl p-3 mt-1 text-gray-700 outline-none">
                        </div>
                    </div>

                    {{-- Row Bawah: Spesifikasi & Keluhan Utama --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Spesifikasi Unit</label>
                            <div class="w-full border bg-gray-50/50 rounded-xl p-3 mt-1 text-gray-600">
                                {{ $servis->booking->spesifikasi }}
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-red-400 uppercase">Keluhan / Kerusakan</label>
                            <div class="w-full border bg-red-50/30 border-red-100 rounded-xl p-3 mt-1 text-red-900 font-medium">
                                {{ $servis->booking->keluhan }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PILIH TEKNISI --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4 tracking-wider">Penugasan Personel</h4>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Pilih Teknisi Penanggung Jawab <span class="text-red-500">*</span></label>
                        <select name="id_user" required
                            class="w-full border border-gray-200 rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="">-- Pilih Teknisi Tersedia --</option>
                            @foreach ($teknisi as $t)
                                <option value="{{ $t->id_user }}">{{ $t->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- DETAIL PENUGASAN --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4 tracking-wider">Parameter Kerja & Catatan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- ESTIMASI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Estimasi Selesai Pengerjaan</label>
                            <input type="date" name="estimasi_selesai"
                                class="w-full border border-gray-200 rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Status Penugasan Awal</label>
                            <input type="hidden" name="status_penugasan" value="belum dikerjakan">
                            <select disabled
                                class="w-full border rounded-xl p-3 mt-1 bg-gray-100 cursor-not-allowed text-gray-400 focus:ring-0 outline-none appearance-none">
                                <option value="belum dikerjakan" selected>Belum Dikerjakan</option>
                                <option value="sedang dikerjakan">Sedang Dikerjakan</option>
                                <option value="menunggu sparepart">Menunggu Sparepart</option>
                                <option value="selesai">Selesai</option>
                                <option value="gagal">Gagal</option>
                            </select>
                        </div>
                    </div>

                    {{-- CATATAN --}}
                    <div class="mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Catatan Tambahan untuk Teknisi</label>
                        <textarea name="catatan_teknisi" rows="3"
                            class="w-full border border-gray-200 rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="Tulis instruksi khusus jika ada..."></textarea>
                    </div>
                </div>

                {{-- HIDDEN ID SERVIS --}}
                <input type="hidden" name="id_servis" value="{{ $servis->id_servis }}">
            </div>

            {{-- FOOTER CARD --}}
            <div class="px-6 py-4 bg-white border-t flex justify-end gap-3">
                <a href="{{ route('admin.penugasan.index') }}" 
                   class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium transition text-sm">
                   Kembali
                </a>
                <button type="submit" 
                    class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-md shadow-indigo-100 transition text-sm">
                    Simpan & Tugaskan
                </button>
            </div>
        </form>
    </div>
@endsection