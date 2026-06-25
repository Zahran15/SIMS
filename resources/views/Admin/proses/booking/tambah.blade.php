@extends('layouts.layout')

@section('title', 'Tambah Booking')

@section('content')

<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Tambah Booking</h2>
        <p class="text-gray-500 mt-1">Tambahkan data booking servis baru</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- HEADER CARD --}}
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-lg font-bold text-white">
                Form Booking Servis
            </h3>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.booking.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6 bg-gray-50">
                {{-- DATA BOOKING --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Booking</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kode Booking</label>
                            <input type="text" name="kode_booking" value="{{ $kode_booking }}" readonly class="w-full border border-gray-200 bg-gray-100 rounded-xl p-3 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Booking</label>
                            <input type="date" name="tgl_booking" value="{{ date('d-m-Y') }}" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                {{-- DATA PELANGGAN --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Pelanggan</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Pelanggan</label>
                        <select name="id_pelanggan" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none bg-white">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($pelanggan as $p)
                                <option value="{{ $p->id_pelanggan }}">
                                    {{ $p->kode_pelanggan }} - {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- DATA DEVICE --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Device</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Merk / Tipe</label>
                            <input type="text" name="merk_tipe" placeholder="Contoh: Asus ROG Strix" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2"> Spesifikasi</label>
                            <input type="text" name="spesifikasi" placeholder="Contoh: RAM 16GB, SSD 512GB" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori Servis</label>
                        <select name="kategori_servis" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="berat">Berat</option>
                        </select>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keluhan</label>
                        <textarea name="keluhan" rows="4" required placeholder="Masukkan keluhan pelanggan..." class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"></textarea>
                    </div>
                    <div class="mt-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kelengkapan</label>
                        <textarea name="kelengkapan" rows="3" placeholder="Contoh: Charger, tas laptop, mouse" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"></textarea>
                    </div>
                </div>

            {{-- STATUS --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Status Booking</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Metode Pengembalian Unit</label>
                        <select name="metode_pengembalian" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none bg-white">
                            <option value="">-- Pilih Metode --</option>
                            <option value="diantar">Diantar Kurir</option>
                            <option value="ambil sendiri">Ambil Sendiri</option>
                        </select>
                    </div>
            
                    {{-- Status DP (Di-set otomatis ke Belum Lunas) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status DP</label>
                        <input type="text" value="Belum Lunas" readonly class="w-full border border-gray-200 bg-gray-100 text-gray-600 rounded-xl p-3 outline-none cursor-not-allowed">
                        <input type="hidden" name="status_dp" value="belum lunas">
                    </div>
            
                    {{-- Status Booking (Di-set otomatis ke Pending) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Booking</label>
                        <input type="text" value="Pending" readonly class="w-full border border-gray-200 bg-gray-100 text-gray-600 rounded-xl p-3 outline-none cursor-not-allowed">
                        <input type="hidden" name="status_booking" value="pending">
                    </div>
                </div>
            </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 bg-white border-t flex items-center justify-end gap-3">
                <a href="{{ route('admin.booking.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-all">
                    Kembali
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition-all">
                    Simpan Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection