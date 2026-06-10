@extends('layouts.layout')

@section('title', 'Ubah Pengajuan Booking')

@section('content')
<div class="p-6">
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Ubah Pengajuan Booking</h2>
        <p class="text-gray-500 mt-1">Perbarui informasi perangkat atau keluhan sebelum diproses oleh admin</p>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- HEADER CARD --}}
        <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-lg font-bold text-white">Form Perubahan Data Booking</h3>
        </div>

        {{-- FORM --}}
        <form action="{{ route('pelanggan.booking.update', $booking->id_booking) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6 bg-gray-50">
                {{-- DATA BOOKING --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Pengajuan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Kode Booking</label>
                            <input type="text" value="{{ $booking->kode_booking }}" readonly class="w-full border bg-gray-100 text-gray-500 font-semibold rounded-xl p-3 mt-1 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Rencana Tanggal Servis</label>
                            <input type="date" name="tgl_booking" value="{{ $booking->tgl_booking }}" min="{{ date('Y-m-d') }}" required class="w-full border rounded-xl p-3 mt-1 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                {{-- DEVICE --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Perangkat / Device</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Merk / Tipe Perangkat</label>
                            <input type="text" name="merk_tipe" value="{{ $booking->merk_tipe }}" required
                                class="border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all" 
                                placeholder="Contoh: Asus ROG Strix">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Spesifikasi Singkat</label>
                            <input type="text" name="spesifikasi" value="{{ $booking->spesifikasi }}" required
                                class="border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all" 
                                placeholder="Contoh: RAM 16GB, SSD 512GB">
                        </div>
                    </div>
                    <div class="mt-5 flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Kategori Estimasi Kerusakan</label>
                        <select name="kategori_servis" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none bg-white transition-all">
                            <option value="ringan" {{ $booking->kategori_servis == 'ringan' ? 'selected' : '' }}>Ringan (Install OS, Ganti RAM, Keyboard, Kebersihan)</option>
                            <option value="sedang" {{ $booking->kategori_servis == 'sedang' ? 'selected' : '' }}>Sedang (Ganti LCD, Ganti Baterai, Engsel)</option>
                            <option value="berat" {{ $booking->kategori_servis == 'berat' ? 'selected' : '' }}>Berat (Mati Total, Short Motherboard, Chipset)</option>
                        </select>
                    </div>
                    <div class="mt-5 flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Metode Penyerahan Unit</label>
                        <select name="metode_pengambilan" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none bg-white transition-all">
                            <option value="diantar" {{ $booking->metode_pengambilan == 'diantar' ? 'selected' : '' }}>Diantar</option>
                            <option value="ambil sendiri" {{ $booking->metode_pengambilan == 'ambil sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                        </select>
                    </div>
                    <div class="flex flex-col mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Detail Keluhan Perangkat</label>
                        <textarea name="keluhan" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all" 
                            rows="4" placeholder="Jelaskan detail kerusakan...">{{ $booking->keluhan }}</textarea>
                    </div>
                    <div class="flex flex-col mt-5">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Kelengkapan Unit yang Ditinggal</label>
                        <textarea name="kelengkapan" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all" 
                            rows="3" placeholder="Contoh: Unit, Charger, Tas">{{ $booking->kelengkapan }}</textarea>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 flex justify-end gap-3 bg-white border-t">
                <a href="{{ route('pelanggan.booking.index') }}" class="px-5 py-3 border border-gray-200 rounded-xl text-gray-600 font-semibold hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-sm transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection