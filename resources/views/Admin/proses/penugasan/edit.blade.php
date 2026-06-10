@extends('layouts.layout')

@section('title', 'Edit Penugasan Teknisi')

@section('content')

<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Edit Penugasan Teknisi</h2>
        <p class="text-gray-500 mt-1">Ubah data penugasan teknisi</p>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- HEADER CARD --}}
        <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-600">
            <h3 class="text-lg font-bold text-white">
                Form Edit Penugasan
            </h3>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.penugasan.update', $penugasan->id_penugasan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6 bg-gray-50">

                {{-- DATA SERVIS --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Servis</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Kode Servis</label>
                            <input type="text" value="{{ $penugasan->servis->kode_servis }}" readonly
                                class="w-full border bg-gray-100 rounded-xl p-3 mt-1">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Pelanggan</label>
                            <input type="text"
                                value="{{ $penugasan->servis->booking->pelanggan->nama ?? '-' }}" readonly
                                class="w-full border bg-gray-100 rounded-xl p-3 mt-1">
                        </div>

                    </div>
                </div>

                {{-- PILIH TEKNISI --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Pilih Teknisi</h4>

                    <select name="id_user" required
                        class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-500 outline-none">

                        @foreach ($teknisi as $t)
                            <option value="{{ $t->id_user }}"
                                {{ $penugasan->id_user == $t->id_user ? 'selected' : '' }}>
                                {{ $t->nama }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- DETAIL PENUGASAN --}}
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Detail Penugasan</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- PRIORITAS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Prioritas</label>
                            <select name="prioritas" required
                                class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-500 outline-none">

                                <option value="rendah" {{ $penugasan->prioritas == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="normal" {{ $penugasan->prioritas == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="tinggi" {{ $penugasan->prioritas == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ $penugasan->prioritas == 'urgent' ? 'selected' : '' }}>Urgent</option>

                            </select>
                        </div>

                        {{-- ESTIMASI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Estimasi Selesai</label>
                            <input type="date" name="estimasi_selesai"
                                value="{{ $penugasan->estimasi_selesai }}"
                                class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                        </div>

                    </div>

                    {{-- STATUS --}}
                    <div class="mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Status Penugasan</label>
                        <select name="status_penugasan" required
                            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-500 outline-none">

                            <option value="belum dikerjakan" {{ $penugasan->status_penugasan == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                            <option value="sedang dikerjakan" {{ $penugasan->status_penugasan == 'sedang dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                            <option value="menunggu sparepart" {{ $penugasan->status_penugasan == 'menunggu sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                            <option value="selesai" {{ $penugasan->status_penugasan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="gagal" {{ $penugasan->status_penugasan == 'gagal' ? 'selected' : '' }}>Gagal</option>

                        </select>
                    </div>

                    {{-- CATATAN --}}
                    <div class="mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Catatan Teknisi</label>
                        <textarea name="catatan_teknisi" rows="3"
                            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-yellow-500 outline-none">{{ $penugasan->catatan_teknisi }}</textarea>
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 bg-white border-t flex justify-end gap-3">
                <a href="{{ route('admin.penugasan.index') }}"
                    class="px-5 py-3 rounded-xl border text-gray-600 hover:bg-gray-50">
                    Kembali
                </a>

                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold">
                    Update Penugasan
                </button>
            </div>

        </form>

    </div>

</div>

@endsection