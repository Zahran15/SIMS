@extends('layouts.layout')

@section('title', 'Edit Status Servis Selesai')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Edit Status Servis</h2>
            <p class="text-gray-500 mt-1">Mengubah status untuk Kode Servis: <span class="font-bold text-green-600">{{ $servis->kode_servis }}</span></p>
        </div>
    </div>

    {{-- VALIDATION ERROR ALERT --}}
    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-100 text-red-700 border border-red-200 text-sm">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM EDIT --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <form action="{{ route('admin.servis_selesai.update', $servis->id_servis) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Data Pelanggan (ReadOnly) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Nama Pelanggan</label>
                    <input type="text" class="w-full border border-gray-200 rounded-2xl p-3 bg-gray-50 text-gray-500 cursor-not-allowed outline-none text-sm" 
                        value="{{ $servis->booking->pelanggan->nama }}" readonly>
                </div>

                {{-- Data Device (ReadOnly) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Device / Merk Tipe</label>
                    <input type="text" class="w-full border border-gray-200 rounded-2xl p-3 bg-gray-50 text-gray-500 cursor-not-allowed outline-none text-sm" 
                        value="{{ $servis->booking->merk_tipe }}" readonly>
                </div>

                {{-- Total Biaya (ReadOnly) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Total Biaya</label>
                    <input type="text" class="w-full border border-gray-200 rounded-2xl p-3 bg-gray-50 text-gray-500 cursor-not-allowed outline-none text-sm font-semibold text-green-600" 
                        value="Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}" readonly>
                </div>

                {{-- TANGGAL MASUK (ReadOnly) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Tanggal Masuk</label>
                    <input type="text" class="w-full border border-gray-200 rounded-2xl p-3 bg-gray-50 text-gray-500 cursor-not-allowed outline-none text-sm" 
                        value="{{ date('d M Y', strtotime($s->tgl_masuk ?? $servis->tgl_masuk)) }}" readonly>
                </div>
            </div>

            <hr class="border-gray-100 mb-6">

            {{-- KOLOM STATUS (Hanya ini yang bisa di-edit) --}}
            <div class="mb-8 max-w-md">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Status Servis Selesai <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="status_servis"
                        class="w-full border border-gray-300 rounded-2xl p-3 bg-white text-gray-800 focus:ring-4 focus:ring-green-500/10 focus:border-green-600 outline-none transition-all appearance-none cursor-pointer text-sm font-semibold shadow-sm">
                        <option value="selesai" {{ $servis->status_servis == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="bisa diambil" {{ $servis->status_servis == 'bisa diambil' ? 'selected' : '' }}>Bisa Diambil</option>
                        <option value="sudah diambil" {{ $servis->status_servis == 'sudah diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                    </select>
                    {{-- Icon Panah Dropdown Custom --}}
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="flex items-center gap-3 justify-end bg-gray-50 -mx-6 -mb-6 p-4 border-t border-gray-100">
                <a href="{{ route('admin.servis_selesai.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 text-sm font-medium transition-all">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold transition-all shadow-sm shadow-green-600/10">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection