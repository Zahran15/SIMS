@extends('layouts.layout')

@section('title', 'Buat Booking Baru')

@section('content')

<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Buat Booking</h2>
        <p class="text-gray-500 mt-1">Ajukan permohonan booking servis baru untuk perangkat Anda</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- HEADER CARD --}}
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-lg font-bold text-white">
                Form Pengajuan Booking Servis
            </h3>
        </div>

        {{-- FORM --}}
        <form action="{{ route('pelanggan.booking.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6 bg-gray-50">
                
                {{-- DATA BOOKING --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Booking</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kode Booking</label>
                            <input type="text" name="kode_booking" value="{{ $kode_booking }}" readonly class="w-full border border-gray-200 bg-gray-100 rounded-xl p-3 outline-none text-gray-500 font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rencana Tanggal Servis</label>
                            <input type="date" name="tgl_booking" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                {{-- DATA DEVICE --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Perangkat / Device</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Merk / Tipe Perangkat</label>
                            <input type="text" name="merk_tipe" placeholder="Contoh: Laptop Asus ROG Strix G15" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Spesifikasi Singkat</label>
                            <input type="text" name="spesifikasi" placeholder="Contoh: Core i7, RAM 16GB, SSD 512GB" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori Estimasi Kerusakan</label>
                        <select name="kategori_servis" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none bg-white">
                            <option value="">-- Pilih Estimasi Kategori --</option>
                            <option value="ringan">Ringan (Install OS, Ganti RAM, Ganti Keyboard, Kebersihan)</option>
                            <option value="sedang">Sedang (Ganti LCD, Ganti Baterai, Service Engsel)</option>
                            <option value="berat">Berat (Mati Total, Short Motherboard, Reball Chipset)</option>
                        </select>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Metode Pengambilan  Unit</label>
                        <select name="metode_pengambilan" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none bg-white">
                            <option value="">-- Pilih Metode --</option>
                            <option value="diantar">Diantar</option>
                            <option value="ambil sendiri">Ambil Sendiri</option>
                        </select>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Detail Keluhan Perangkat</label>
                        <textarea name="keluhan" rows="4" required placeholder="Ceritakan detail kerusakan atau gejala masalah pada perangkat Anda..." class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"></textarea>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kelengkapan Unit yang Ditinggal</label>
                        <textarea name="kelengkapan" rows="3" placeholder="Contoh: Unit laptop + Charger original (Tas bawa pulang saja)" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"></textarea>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 bg-white border-t flex items-center justify-end gap-3">
                <a href="{{ route('pelanggan.booking.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition-all">
                    Kirim Pengajuan Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection