@extends('layouts.layout')

@section('title', 'Tambah Pengadaan Sparepart')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Form Pengadaan Baru</h2>
        <p class="text-gray-500 mt-1">Catat transaksi masuknya suku cadang baru untuk menambah stok gudang.</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700">
            <h3 class="text-xl font-bold text-white">Form Pengadaan Sparepart</h3>
        </div>

        <form action="{{ route('admin.pengadaan_sparepart.store') }}" method="POST">
            @csrf

            <div class="p-8 space-y-6">
            {{-- PILIH SPAREPART (Pencarian Berdasarkan Nama - Sederhana & Ramah Dosen) --}}
            <div class="bg-white border rounded-xl p-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Komponen / Sparepart</label>
                <div x-data="{
                        open: false,
                        searchNama: '',
                        selectedText: '',
                        spareparts: [
                            @foreach($sparepart as $s)
                            { id: '{{ $s->id_sparepart }}', nama: '{{ $s->nama_sparepart }}', stok: '{{ $s->stok }}' },
                            @endforeach
                        ],
                        // Fungsi ketika sparepart dipilih dari list dropdown
                        pilih(s) {
                            this.selectedText = s.nama + ' (Stok: ' + s.stok + ' Pcs)';
                            this.searchNama = s.nama; // Mengisi kolom input dengan nama yang dipilih
                            this.open = false;
                        }
                     }"
                     @click.away="open = false" class="relative">

                    {{-- Hidden Input untuk mengirim ID Sparepart asli ke Controller Laravel --}}
                    <input type="hidden" name="id_sparepart" :value="selectedText ? spareparts.find(s => s.nama === searchNama)?.id : ''" required>

                    {{-- Kolom Input Pencarian sekaligus Tampilan Data --}}
                    <input type="text" x-model="searchNama" @focus="open = true"@input="selectedText = ''"placeholder="Ketik nama sparepart (Contoh: RAM)..." 
                           class="w-full border rounded-lg p-2.5 text-sm bg-white outline-none focus:border-blue-500">

                    {{-- Dropdown Hasil Pencarian --}}
                    <div x-show="open && searchNama" class="absolute z-50 w-full mt-1 bg-white border rounded-lg shadow-md max-h-40 overflow-y-auto p-1">
                        <ul class="text-sm">
                            {{-- Filter menggunakan .includes() agar pencarian nama bersifat bebas --}}
                            <template x-for="s in spareparts.filter(s => s.nama.toLowerCase().includes(searchNama.toLowerCase()))">
                                <li>
                                    <button type="button" @click="pilih(s)" class="w-full text-left p-2 hover:bg-blue-50 rounded flex justify-between">
                                        <span class="text-gray-700" x-text="s.nama"></span>
                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-500" x-text="'Stok: ' + s.stok"></span>
                                    </button>
                                </li>
                            </template>

                            {{-- Jika Hasil Pencarian Kosong --}}
                            <template x-if="spareparts.filter(s => s.nama.toLowerCase().includes(searchNama.toLowerCase())).length === 0">
                                <li class="p-2 text-gray-400 text-xs text-center">Sparepart tidak ditemukan</li>
                            </template>
                        </ul>
                    </div>

                </div>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- TANGGAL PESAN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Pesan</label>
                        <input type="date" name="tgl_pesan" value="{{ date('d M Y') }}" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-gray-700" required>
                    </div>

                    {{-- STATUS PENGADAAN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Transaksi</label>
                        <select name="status_pengadaan" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 appearance-none bg-no-repeat text-gray-700" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="dipesan">Dipesan</option>
                            <option value="diterima">Diterima</option>
                            <option value="dibatalkan">Dibatalkan</option>
                            <option value="diajukan">Diajukan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- JUMLAH MASUK --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah (Qty)</label>
                        <input type="number" id="jumlah" name="jumlah" min="1" placeholder="0" class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>

                    {{-- HARGA BELI --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Harga Beli Satuan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-semibold border-r pr-3 border-gray-100">Rp</span>
                            <input type="number" id="harga_beli" name="harga_beli" min="0" placeholder="0" class="w-full border border-gray-200 rounded-xl p-3 pl-16 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                        </div>
                    </div>
                </div>

                {{-- LIVE HITUNG KESELURUHAN TOTAL --}}
                <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl flex justify-between items-center shadow-inner">
                    <span class="text-gray-600 text-xs font-bold uppercase tracking-wider">Estimasi Total Pengeluaran:</span>
                    <span id="label-total" class="text-2xl font-medium text-blue-600">Rp 0</span>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.pengadaan_sparepart.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition">Simpan</button>
            </div>
        </form>
    </div>

    {{-- LOGIKA HITUNG OTOMATIS --}}
    <script>
        const inputJumlah = document.getElementById('jumlah');
        const inputHarga = document.getElementById('harga_beli');
        const labelTotal = document.getElementById('label-total');

        function hitungTotal() {
            const qty = parseFloat(inputJumlah.value) || 0;
            const price = parseFloat(inputHarga.value) || 0;
            const total = qty * price;
            labelTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        inputJumlah.addEventListener('input', hitungTotal);
        inputHarga.addEventListener('input', hitungTotal);
    </script>
@endsection