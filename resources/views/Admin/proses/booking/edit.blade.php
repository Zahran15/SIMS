@extends('layouts.layout')

@section('title', 'Edit Booking')

@section('content')
<div class="p-6">
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Edit Booking</h2>
        <p class="text-gray-500 mt-1">Perbarui data booking servis</p>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- HEADER CARD --}}
        <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-lg font-bold text-white">Form Edit Booking</h3>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.booking.update', $booking->id_booking) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6 bg-gray-50">
                {{-- DATA BOOKING --}}
                <div class="bg-white border rounded-2xl p-5">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Data Booking</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Kode Booking</label>
                            <input type="text" value="{{ $booking->kode_booking }}" readonly class="w-full border bg-gray-100 rounded-xl p-3 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Tanggal Booking</label>
                            <input type="date" name="tgl_booking" value="{{ $booking->tgl_booking }}" class="w-full border rounded-xl p-3 mt-1">
                        </div>
                    </div>
                </div>

                {{-- PELANGGAN --}}
                <div class="bg-white border rounded-2xl p-5">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Pelanggan</h4>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Pelanggan</label>
                    <select name="id_pelanggan" class="w-full border rounded-xl p-3">
                        @foreach ($pelanggan as $p)
                            <option value="{{ $p->id_pelanggan }}" {{ $booking->id_pelanggan == $p->id_pelanggan ? 'selected' : '' }}>
                                {{ $p->kode_pelanggan }} - {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DEVICE --}}
                <div class="bg-white border rounded-2xl p-5">
                    <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Device</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Merk / Tipe</label>
                            <input type="text" name="merk_tipe" value="{{ $booking->merk_tipe }}"
                                class="border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none" 
                                placeholder="Contoh: Asus ROG Strix">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Spesifikasi</label>
                            <input type="text" name="spesifikasi" value="{{ $booking->spesifikasi }}"
                                class="border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none" 
                                placeholder="Contoh: RAM 16GB, SSD 512GB">
                        </div>
                    </div>
                    <div class="mt-5 flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Kategori Servis</label>
                        <select name="kategori_servis" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="ringan" {{ $booking->kategori_servis == 'ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="sedang" {{ $booking->kategori_servis == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="berat" {{ $booking->kategori_servis == 'berat' ? 'selected' : '' }}>Berat</option>
                        </select>
                    </div>
                    <div class="flex flex-col mt-4">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Keluhan</label>
                        <textarea name="keluhan" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none" 
                            rows="3" placeholder="Jelaskan detail kerusakan...">{{ $booking->keluhan }}</textarea>
                    </div>
                    <div class="flex flex-col mt-4">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Kelengkapan</label>
                        <textarea name="kelengkapan" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none" 
                            rows="2" placeholder="Contoh: Unit, Charger, Tas">{{ $booking->kelengkapan }}</textarea>
                    </div>
                </div>

            {{-- STATUS --}}
            <div class="bg-white border rounded-2xl p-5">
                <h4 class="text-sm font-bold uppercase text-gray-500 mb-4">Status & Kategori</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Metode Pengambilan</label>
                        <select name="metode_pengambilan" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none">
                            <option value="diantar" {{ $booking->metode_pengambilan == 'diantar' ? 'selected' : '' }}>Diantar</option>
                            <option value="ambil sendiri" {{ $booking->metode_pengambilan == 'ambil sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Status Deposit</label>
                        <select name="status_deposit" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none">
                            <option value="belum lunas" {{ $booking->status_deposit == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="sudah lunas" {{ $booking->status_deposit == 'sudah lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Status Booking</label>
                        <select name="status_booking" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500 outline-none">
                            <option value="pending" {{ $booking->status_booking == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diterima" {{ $booking->status_booking == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ $booking->status_booking == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 flex justify-end gap-3 bg-white border-t">
                <a href="{{ route('admin.booking.index') }}" class="px-5 py-2 border rounded-xl hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection