@extends('layouts.layout')

@section('title', 'Tugaskan Teknisi')

@section('content')
<div class="p-6">
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Tugaskan Teknisi</h2>
        <p class="text-gray-500 mt-1">Pilih teknisi untuk mengerjakan servis</p>
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
                {{-- DATA SERVIS --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Servis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Kode Servis</label>
                            <input type="text" value="{{ $servis->kode_servis }}" readonly
                                class="w-full border bg-gray-100 rounded-xl p-3 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Pelanggan</label>
                            <input type="text" value="{{ $servis->booking->pelanggan->nama ?? '-' }}" readonly
                                class="w-full border bg-gray-100 rounded-xl p-3 mt-1">
                        </div>
                    </div>
                </div>

                {{-- PILIH TEKNISI --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Pilih Teknisi</h4>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Teknisi</label>
                        <select name="id_user" required
                            class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach ($teknisi as $t)
                                <option value="{{ $t->id_user }}">{{ $t->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- DETAIL PENUGASAN --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Detail Penugasan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- PRIORITAS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Prioritas</label>
                            <select name="prioritas" required
                                class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="">-- Pilih Prioritas --</option>
                                <option value="rendah">Rendah</option>
                                <option value="normal">Normal</option>
                                <option value="tinggi">Tinggi</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        {{-- ESTIMASI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Estimasi Selesai</label>
                            <input type="date" name="estimasi_selesai"
                                class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Status Penugasan</label>
                        <input type="hidden" name="status_penugasan" value="belum dikerjakan">
                        <select disabled
                            class="w-full border rounded-xl p-3 mt-1 bg-gray-100 cursor-not-allowed text-gray-500 focus:ring-0 outline-none appearance-none">
                            <option value="belum dikerjakan" selected>Belum Dikerjakan</option>
                            <option value="sedang dikerjakan">Sedang Dikerjakan</option>
                            <option value="menunggu sparepart">Menunggu Sparepart</option>
                            <option value="selesai">Selesai</option>
                            <option value="gagal">Gagal</option>
                        </select>
                    </div>

                    {{-- CATATAN --}}
                    <div class="mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Catatan Teknisi</label>
                        <textarea name="catatan_teknisi" rows="3"
                            class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none"
                            placeholder="Opsional..."></textarea>
                    </div>
                </div>

                {{-- HIDDEN --}}
                <input type="hidden" name="id_servis" value="{{ $servis->id_servis }}">
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 bg-white border-t flex justify-end gap-3">
                <a href="{{ route('admin.penugasan.index') }}"
                    class="px-5 py-3 rounded-xl border text-gray-600 hover:bg-gray-50 transition">
                    Kembali
                </a>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
                    Simpan Penugasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection