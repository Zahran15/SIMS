@extends('layouts.layout')

@section('title', 'Edit Pengadaan Sparepart')

@section('content')
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Pengadaan Sparepart</h2>
        <p class="text-gray-500 mt-1">Perbarui transaksi masuknya suku cadang dan sesuaikan data gudang.</p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Form Header with Gradient --}}
        <div class="px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600">
            <h3 class="text-xl font-bold text-white">Form Edit Pengadaan</h3>
        </div>

        <form action="{{ route('admin.pengadaan_sparepart.update', $pengadaan->id_pengadaan) }}" method="POST">
            @csrf
            @method('PUT') 
            <div class="p-8 space-y-6">
                {{-- Pilih Sparepart --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Komponen / Sparepart</label>
                    <select name="id_sparepart" required class="w-full border border-gray-200 rounded-xl p-3 bg-white outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 cursor-pointer">
                        <option value="" disabled>-- Pilih Sparepart --</option>
                        @foreach($sparepart as $s)
                        <option value="{{ $s->id_sparepart }}" {{ $pengadaan->id_sparepart == $s->id_sparepart ? 'selected' : '' }}>{{ $s->nama_sparepart }} (Stok Saat Ini: {{ $s->stok }} Pcs)</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Pesan & Status Transaksi --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Pesan</label>
                        <input type="date" name="tgl_pesan" value="{{ old('tgl_pesan', $pengadaan->tgl_pesan) }}" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Transaksi</label>
                        <select name="status_pengadaan" required class="w-full border border-gray-200 rounded-xl p-3 bg-white outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100 cursor-pointer">
                            <option value="" disabled>-- Pilih Status --</option>
                            <option value="dipesan" {{ $pengadaan->status_pengadaan == 'dipesan' ? 'selected' : '' }}>DIPESAN</option>
                            <option value="diterima" {{ $pengadaan->status_pengadaan == 'diterima' ? 'selected' : '' }}>DITERIMA</option>
                            <option value="ditolak" {{ $pengadaan->status_pengadaan == 'ditolak' ? 'selected' : '' }}>DITOLAK</option>
                        </select>
                    </div>
                </div>

                {{-- Jumlah Masuk & Harga Beli --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah (Qty)</label>
                        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', $pengadaan->jumlah) }}" min="1" placeholder="0" required class="w-full border border-gray-200 rounded-xl p-3 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Harga Beli Satuan (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-semibold border-r pr-3 border-gray-200">Rp</span>
                            <input type="number" id="harga_beli" name="harga_beli" value="{{ old('harga_beli', $pengadaan->harga_beli) }}" min="0" placeholder="0" required class="w-full border border-gray-200 rounded-xl p-3 pl-16 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-100">
                        </div>
                    </div>
                </div>

                {{-- Live Hitung Keseluruhan Total --}}
                <div class="p-5 bg-amber-50/50 border border-amber-100 rounded-xl flex justify-between items-center shadow-inner">
                    <span class="text-gray-600 text-sm font-bold uppercase tracking-wider">Total Pengeluaran:</span>
                    <span id="label-total" class="text-2xl font-black text-orange-600">Rp {{ number_format($pengadaan->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Tombol Aksi (Footer) --}}
            <div class="border-t border-gray-100 p-6 flex gap-4 bg-gray-50/50">
                <a href="{{ route('admin.pengadaan_sparepart.index') }}" class="flex-1 text-center py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">Batal</a>
                <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/20 transition">Perbarui Data</button>
            </div>
        </form>
    </div>
@endsection

{{-- LOGIKA HITUNG OTOMATIS --}}
@section('scripts')
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

    // Jalankan kalkulasi sekali saat halaman pertama kali dimuat agar angka sinkron
    window.addEventListener('DOMContentLoaded', hitungTotal);
</script>
@endsection