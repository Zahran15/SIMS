@extends('layouts.layout')

@section('title', 'Buat Request Sparepart')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Form Request Sparepart</h2>
        <p class="text-gray-500 mt-1">Ajukan permintaan kebutuhan komponen untuk proses servis aktif.</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" 
         x-data="{
            {{-- Master Data dari Laravel ke Alpine --}}
            masterSpareparts: [
                @foreach($sparepart as $s)
                { id: '{{ $s->id_sparepart }}', nama: '{{ $s->nama_sparepart }}', stok: parseInt('{{ $s->stok }}') },
                @endforeach
            ],
            
            {{-- State untuk menampung sparepart yang dipilih --}}
            selectedItems: [
                { id_sparepart: '', searchNama: '', open: false, stok: 0 }
            ],

            {{-- Fungsi Tambah Baris --}}
            addItem() {
                this.selectedItems.push({ id_sparepart: '', searchNama: '', open: false, stok: 0 });
            },

            {{-- Fungsi Hapus Baris --}}
            removeItem(index) {
                if(this.selectedItems.length > 1) {
                    this.selectedItems.splice(index, 1);
                } else {
                    alert('Minimal harus ada 1 jenis sparepart yang diajukan!');
                }
            },

            {{-- State Tambahan untuk Pencarian & Informasi Servis Terpilih --}}
            selectedId: '',
            selectedKode: '',
            selectedNama: '',
            selectedDevice: '',
            selectedKeluhan: '',
            
            {{-- Master Data Servis dari Laravel ke Alpine --}}
            servises: [
                @foreach($penugasan as $p)
                { 
                    id: '{{ $p->id_penugasan }}', 
                    kode: '{{ $p->servis->kode_servis }}', 
                    nama: '{{ $p->servis->booking->pelanggan->nama }}',
                    device: '{{ $p->servis->booking->merk_tipe }}',
                    keluhan: '{{ $p->servis->keluhan ?? $p->servis->booking->keluhan ?? 'Tidak ada keluhan khusus' }}'
                },
                @endforeach
            ],

            pilih(servis) {
                this.selectedId = servis.id;
                this.selectedKode = servis.kode;
                this.selectedNama = servis.nama;
                this.selectedDevice = servis.device;
                this.selectedKeluhan = servis.keluhan;
            }
         }">
         
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center gap-3">
            <div>
                <h3 class="text-xl font-bold text-white">Form Pengajuan Suku Cadang</h3>
            </div>
        </div>

        <form action="{{ route('teknisi.request_sparepart.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-6">
                
                {{-- 1. SEKSI DATA SERVIS --}}
                <div class="bg-white border rounded-xl p-4 space-y-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Kode / Data Servis</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Hidden Input ID Penugasan --}}
                        <input type="hidden" name="id_penugasan" :value="selectedId" required>
            
                        {{-- Input Kode Servis --}}
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <label class="block text-xs text-gray-400 mb-1">Cari Kode Servis</label>
                            <input type="text" x-model="selectedKode" @focus="open = true" placeholder="Ketik kode..." class="w-full border rounded-lg p-2 text-sm bg-white outline-none focus:border-blue-500">
                            
                            <div x-show="open && selectedKode" class="absolute z-50 w-full mt-1 bg-white border rounded-lg shadow-md max-h-40 overflow-y-auto p-1">
                                <ul class="text-sm">
                                    <template x-for="s in servises.filter(s => s.kode.toLowerCase().includes(selectedKode.toLowerCase()))">
                                        <li>
                                            <button type="button" @click="pilih(s); open = false;" class="w-full text-left p-2 hover:bg-blue-50 rounded flex justify-between">
                                                <span class="font-bold text-blue-600" x-text="s.kode"></span>
                                                <span class="text-gray-500" x-text="s.nama"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
            
                        {{-- Input Nama Pelanggan --}}
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <label class="block text-xs text-gray-400 mb-1">Cari Nama Pelanggan</label>
                            <input type="text" x-model="selectedNama" @focus="open = true" placeholder="Ketik huruf depan nama..." class="w-full border rounded-lg p-2 text-sm bg-white outline-none focus:border-blue-500">
                            
                            <div x-show="open && selectedNama" class="absolute z-50 w-full mt-1 bg-white border rounded-lg shadow-md max-h-40 overflow-y-auto p-1">
                                <ul class="text-sm">
                                    <template x-for="s in servises.filter(s => s.nama.toLowerCase().startsWith(selectedNama.toLowerCase()))">
                                        <li>
                                            <button type="button" @click="pilih(s); open = false;" class="w-full text-left p-2 hover:bg-blue-50 rounded flex justify-between">
                                                <span class="text-gray-700" x-text="s.nama"></span>
                                                <span class="text-xs text-gray-400" x-text="s.kode"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Tambahan Dinamis: Tampil Otomatis Mengikuti Desain Asli --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100" x-show="selectedId" x-transition>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Merk / Tipe Perangkat</label>
                            <div class="w-full border rounded-lg p-2 text-sm bg-gray-50 text-gray-800 font-semibold" x-text="selectedDevice || '-'"></div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-red-500 mb-1">Keluhan Pelanggan</label>
                            <div class="w-full border border-red-100 rounded-lg p-2 text-sm bg-red-50/50 text-red-900 font-medium" x-text="selectedKeluhan"></div>
                        </div>
                    </div>

                </div>
            
                {{-- 2. SEKSI MULTIPLE SPAREPARTS --}}
                <div class="bg-white border rounded-xl p-4 space-y-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Daftar Kebutuhan Sparepart</label>
                        <button type="button" @click="addItem()" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            + Tambah Baris
                        </button>
                    </div>

                    {{-- Perulangan Baris Sparepart --}}
                    <div class="space-y-3">
                        <template x-for="(item, index) in selectedItems" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 p-3 bg-gray-50 rounded-xl relative border border-gray-100 items-end">
                                
                                {{-- Kolom Input / Cari Sparepart --}}
                                <div class="md:col-span-8 relative" @click.away="item.open = false">
                                    <label class="block text-xs text-gray-400 mb-1">Nama Sparepart (<span x-text="'Stok: ' + item.stok"></span>)</label>
                                    
                                    {{-- Hidden Input untuk kirim ID (Array) --}}
                                    <input type="hidden" name="id_sparepart[]" :value="item.id_sparepart" required>
                                    
                                    <input type="text" x-model="item.searchNama" @focus="item.open = true" placeholder="Ketik nama sparepart..." class="w-full border rounded-lg p-2 text-sm bg-white outline-none focus:border-blue-500">
                                    
                                    {{-- Dropdown Hasil Pencarian --}}
                                    <div x-show="item.open && item.searchNama" class="absolute z-50 w-full mt-1 bg-white border rounded-lg shadow-md max-h-40 overflow-y-auto p-1">
                                        <ul class="text-sm">
                                            <template x-for="s in masterSpareparts.filter(s => s.nama.toLowerCase().includes(item.searchNama.toLowerCase()))">
                                                <li>
                                                    <button type="button" 
                                                            @click="item.id_sparepart = s.id; item.searchNama = s.nama; item.stok = s.stok; item.open = false;" 
                                                            class="w-full text-left p-2 hover:bg-blue-50 rounded flex justify-between">
                                                        <span class="text-gray-700" x-text="s.nama"></span>
                                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-500" x-text="'Stok: ' + s.stok"></span>
                                                    </button>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Kolom Jumlah (Qty) --}}
                                <div class="md:col-span-3 relative">
                                    <label class="block text-xs text-gray-400 mb-1">Jumlah</label>
                                    <input type="number" name="jumlah[]" min="1" :max="item.stok" placeholder="1" class="w-full border rounded-lg p-2 text-sm outline-none focus:border-blue-500 pr-10 text-gray-700" required>
                                    <span class="absolute right-3 bottom-2 text-gray-400 text-xs font-semibold">Pcs</span>
                                </div>

                                {{-- Tombol Hapus Baris --}}
                                <div class="md:col-span-1 text-center">
                                    <button type="button" @click="removeItem(index)" class="p-2 bg-red-50 text-red-500 hover:bg-red-100 rounded-lg transition w-full flex justify-center items-center" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>
            
                {{-- 3. INPUT ALASAN PERGANTIAN --}}
                <div class="bg-white border rounded-xl p-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alasan Pergantian / Kerusakan Komponen</label>
                    <textarea name="alasan" rows="4" placeholder="Jelaskan kondisi sparepart lama secara detail untuk semua komponen yang diminta..." class="w-full border rounded-lg p-3 text-sm outline-none focus:border-blue-500 text-gray-700 resize-none" required></textarea>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('teknisi.request_sparepart.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition flex items-center justify-center gap-2">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
@endsection