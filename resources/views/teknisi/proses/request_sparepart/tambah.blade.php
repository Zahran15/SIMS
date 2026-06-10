@extends('layouts.layout')

@section('title', 'Buat Request Sparepart')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Form Request Sparepart</h2>
        <p class="text-gray-500 mt-1">Ajukan permintaan kebutuhan komponen untuk proses servis aktif.</p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center gap-3">
            <div>
                <h3 class="text-xl font-bold text-white">Form Pengajuan Suku Cadang</h3>
            </div>
        </div>

        <form action="{{ route('teknisi.request_sparepart.store') }}" method="POST">
            @csrf

            <div class="p-8 space-y-6">
                {{-- INPUT PADA PENUGASAN SERVIS --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Kode/Data Servis</label>
                    <select name="id_penugasan" class="w-full border @error('id_penugasan') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 outline-none transition appearance-none bg-no-repeat text-gray-700" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                        <option value="" disabled selected>-- Pilih Data Servis Aktif --</option>
                        @foreach($penugasan as $p)
                            <option value="{{ $p->id_penugasan }}" {{ old('id_penugasan') == $p->id_penugasan ? 'selected' : '' }}>
                                {{ $p->servis->kode_servis ?? 'ID: '.$p->id_penugasan }} - {{ $p->servis->nama_pelanggan ?? 'Pelanggan' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_penugasan') <p class="text-sm text-red-500 mt-2 ml-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- INPUT SPAREPART --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Sparepart</label>
                        <select name="id_sparepart" class="w-full border @error('id_sparepart') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 outline-none transition appearance-none bg-no-repeat text-gray-700" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22%23cbd5e1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.75rem center; background-size: 1.2em;" required>
                            <option value="" disabled selected>-- Pilih Barang yang Dibutuhkan --</option>
                            @foreach($sparepart as $s)
                                <option value="{{ $s->id_sparepart }}" {{ old('id_sparepart') == $s->id_sparepart ? 'selected' : '' }}>
                                    {{ $s->nama_sparepart }} (Sisa Stok Gudang: {{ $s->stok }} Pcs)
                                </option>
                            @endforeach
                        </select>
                        @error('id_sparepart') <p class="text-sm text-red-500 mt-2 ml-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- INPUT JUMLAH --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah (Qty)</label>
                        <div class="relative">
                            <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" placeholder="Contoh: 1" class="w-full border @error('jumlah') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 pr-12 outline-none transition text-gray-700" required>
                            <span class="absolute right-4 top-3.5 text-gray-400 text-sm font-semibold">Pcs</span>
                        </div>
                        @error('jumlah') <p class="text-sm text-red-500 mt-2 ml-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- INPUT ALASAN PERGANTIAN --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alasan Pergantian / Kerusakan Komponen</label>
                    <textarea name="alasan" rows="4" placeholder="Jelaskan kondisi sparepart lama (misal: Kampas rem aus berbunyi keras)" class="w-full border @error('alasan') border-red-500 focus:ring-red-100 focus:border-red-500 @else border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @enderror rounded-xl p-3 outline-none transition text-gray-700 resize-none" required>{{ old('alasan') }}</textarea>
                    @error('alasan') <p class="text-sm text-red-500 mt-2 ml-1 font-semibold">{{ $message }}</p> @enderror
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