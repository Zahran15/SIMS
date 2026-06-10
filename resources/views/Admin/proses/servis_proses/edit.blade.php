@extends('layouts.layout')

@section('title', 'Edit Servis')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Servis</h2>
            <p class="text-gray-500 mt-1 flex items-center">
                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                Manajemen pembaruan status dan rincian servis pelanggan
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.servis_proses.index') }}"
                class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.servis_proses.update', $servis->id_servis) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            {{-- BAGIAN ATAS: INFORMASI UTAMA --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- CARD INFORMASI SERVIS --}}
                <div class="lg:col-span-1 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Status & Jadwal
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Kode Servis</label>
                            <input type="text" value="{{ $servis->kode_servis }}" readonly
                                class="w-full border-none bg-blue-50 text-blue-700 font-mono font-bold rounded-2xl p-4 ring-1 ring-blue-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Perkiraan Selesai</label>
                            <input type="date" name="perkiraan_selesai" value="{{ $servis->perkiraan_selesai }}"
                                class="w-full border border-gray-200 rounded-2xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Status Servis</label>
                            <select name="status_servis"
                                class="w-full border border-gray-200 rounded-2xl p-3 bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all appearance-none">
                                <option value="menunggu" {{ $servis->status_servis == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="proses" {{ $servis->status_servis == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ $servis->status_servis == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="bisa diambil" {{ $servis->status_servis == 'bisa diambil' ? 'selected' : '' }}>Bisa Diambil</option>
                                <option value="sudah diambil" {{ $servis->status_servis == 'sudah diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                <option value="dibatalkan" {{ $servis->status_servis == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Status Pelunasan</label>
                            <select name="status_pelunasan_disabled" class="w-full border border-gray-200 rounded-2xl p-3 bg-gray-50 text-gray-500 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all appearance-none cursor-not-allowed" disabled>
                                <option value="belum lunas" selected>Belum Lunas</option>
                                <option value="sudah lunas">Sudah Lunas</option>
                            </select>
                            <input type="hidden" name="status_pelunasan" value="belum lunas">
                        </div>
                    </div>
                </div>

                {{-- CARD DATA PELANGGAN & UNIT --}}
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pelanggan & Unit
                        </h3>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase">{{ $servis->booking->kategori_servis }}</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pemilik</p>
                                <p class="text-lg font-bold text-gray-800">{{ $servis->booking->pelanggan->nama }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $servis->booking->pelanggan->no_hp }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kendaraan / Unit</p>
                                <p class="text-lg font-bold text-gray-800">{{ $servis->booking->merk_tipe }}</p>
                                <p class="text-sm text-gray-500 mt-1">Tgl Masuk: {{ \Carbon\Carbon::parse($servis->tgl_masuk)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Keluhan Pelanggan</label>
                            <div class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 text-gray-700 italic">
                                "{{ $servis->booking->keluhan }}"
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN BAWAH: RINCIAN BIAYA (BERSEBELAHAN) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- DETAIL JASA --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center uppercase text-xs tracking-widest">
                            Detail Jasa Servis
                        </h3>
                        <button type="button" onclick="tambahJasa()"
                            class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <div id="list-jasa" class="p-6 space-y-4 flex-grow">
                        @foreach($servis->detailJasa as $item)
                        <div class="flex gap-3 group animate-fadeIn">
                            <select name="jasa[]" onchange="hitungTotal()"
                                class="jasa-select flex-1 border border-gray-200 rounded-2xl p-3 bg-white focus:border-blue-500 focus:ring-0 transition-all text-sm">
                                <option value="">-- Pilih Jasa --</option>
                                @foreach($jasa as $j)
                                <option value="{{ $j->id_jasa }}" data-harga="{{ $j->harga }}" {{ $item->id_jasa == $j->id_jasa ? 'selected' : '' }}>
                                    {{ $j->nama_jasa }}
                                </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="this.parentElement.remove(); hitungTotal()"
                                class="px-4 rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- DETAIL SPAREPART --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center uppercase text-xs tracking-widest">
                            Detail Sparepart
                        </h3>
                        <button type="button" onclick="tambahSparepart()"
                            class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <div id="list-sparepart" class="p-6 space-y-4 flex-grow">
                        @foreach($servis->detailSparepart as $item)
                        <div class="flex gap-3 animate-fadeIn">
                            <select name="sparepart[]" onchange="hitungTotal()"
                                class="sparepart-select flex-1 border border-gray-200 rounded-2xl p-3 bg-white focus:border-blue-500 focus:ring-0 transition-all text-sm">
                                <option value="">-- Pilih Sparepart --</option>
                                @foreach($sparepart as $sp)
                                <option value="{{ $sp->id_sparepart }}" data-harga="{{ $sp->harga_jual }}"
                                    {{ $item->id_sparepart == $sp->id_sparepart ? 'selected' : '' }}>
                                    {{ $sp->nama_sparepart }}
                                </option>
                                @endforeach
                            </select>
                            <input type="number" name="qty[]" value="{{ $item->qty }}" min="1" oninput="hitungTotal()"
                                class="qty-input w-20 border border-gray-200 rounded-2xl p-3 text-center focus:border-blue-500 focus:ring-0 transition-all">
                            <button type="button" onclick="this.parentElement.remove(); hitungTotal()"
                                class="px-4 rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ESTIMASI BIAYA & AKSI --}}
            <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
                <div class="relative z-10 text-center md:text-left">
                    <p class="text-gray-400 font-medium mb-1 flex items-center justify-center md:justify-start">
                        Total Estimasi Biaya
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </p>
                    <h2 id="grand-total" class="text-5xl font-extrabold text-white tracking-tighter">
                        Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="flex gap-4 relative z-10 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none px-10 py-5 rounded-2xl bg-yellow-500 hover:bg-yellow-400 text-yellow-950 font-bold shadow-lg shadow-yellow-500/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const jasaOption = `
        <option value="">-- Pilih Jasa --</option>
        @foreach($jasa as $j)
            <option value="{{ $j->id_jasa }}" data-harga="{{ $j->harga }}">
                {{ $j->nama_jasa }}
            </option>
        @endforeach
    `;

    const sparepartOption = `
        <option value="">-- Pilih Sparepart --</option>
        @foreach($sparepart as $sp)
            <option value="{{ $sp->id_sparepart }}" data-harga="{{ $sp->harga_jual }}">
                {{ $sp->nama_sparepart }}
            </option>
        @endforeach
    `;

    function tambahJasa() {
        let html = `
            <div class="flex gap-3">
                <select name="jasa[]" onchange="hitungTotal()" class="jasa-select flex-1 border border-gray-200 rounded-xl p-3 bg-white">
                    ${jasaOption}
                </select>
                <button type="button" onclick="this.parentElement.remove(); hitungTotal()" class="px-4 rounded-xl bg-red-100 text-red-600">
                    ✕
                </button>
            </div>
        `;
        document.getElementById('list-jasa').insertAdjacentHTML('beforeend', html);
    }

    function tambahSparepart() {
        let html = `
            <div class="flex gap-3">
                <select name="sparepart[]" onchange="hitungTotal()" class="sparepart-select flex-1 border border-gray-200 rounded-xl p-3 bg-white">
                    ${sparepartOption}
                </select>
                <input type="number" name="qty[]" min="1" value="1" oninput="hitungTotal()" class="qty-input w-24 border border-gray-200 rounded-xl p-3">
                <button type="button" onclick="this.parentElement.remove(); hitungTotal()" class="px-4 rounded-xl bg-red-100 text-red-600">
                    ✕
                </button>
            </div>
        `;
        document.getElementById('list-sparepart').insertAdjacentHTML('beforeend', html);
    }

    function hitungTotal() {
        let total = 0;

        // JASA
        document.querySelectorAll('.jasa-select').forEach(function(select) {
            let harga = select.options[select.selectedIndex]?.dataset?.harga || 0;
            total += parseInt(harga);
        });

        // SPAREPART
        document.querySelectorAll('.sparepart-select').forEach(function(select, index) {
            let harga = select.options[select.selectedIndex]?.dataset?.harga || 0;
            let qty = document.querySelectorAll('.qty-input')[index]?.value || 1;
            total += parseInt(harga) * parseInt(qty);
        });

        document.getElementById('grand-total').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Jalankan kalkulasi pertama kali saat halaman dimuat
    hitungTotal();
</script>
@endsection