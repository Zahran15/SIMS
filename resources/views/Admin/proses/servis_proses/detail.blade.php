@extends('layouts.layout')

@section('title', 'Detail Servis')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Transaksi Servis</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap data transaksi dan perangkat pelanggan</p>
        </div>
        
        <div class="flex items-center gap-2">
            {{-- BUTTON QUICK ACCEPT / SELESAI (Hanya Muncul Jika Status 'proses') --}}
            @if($servis->status_servis == 'proses' && $servis->penugasan && $servis->penugasan->status_penugasan == 'selesai')
                <form id="form-selesai-{{ $servis->id_servis }}" action="{{ route('admin.servis_proses.quick_selesai', $servis->id_servis) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" 
                            onclick="konfirmasiSelesai('{{ $servis->id_servis }}', '{{ $servis->kode_servis }}')"
                            class="px-3 py-1.5 rounded bg-green-600 hover:bg-green-700 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1" 
                            title="Selesaikan Servis">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Selesaikan Servis
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.servis_proses.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: STATUS & ADMINISTRASI --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Status & Administrasi Servis</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Servis</span>
                    <strong class="text-blue-600 text-sm">{{ $servis->kode_servis }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Kode Booking</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->kode_booking }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Tanggal Masuk</span>
                    <span class="text-gray-800 font-semibold">{{ date('d M Y', strtotime($servis->tgl_masuk)) }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Perkiraan Selesai</span>
                    <span class="text-gray-800 font-semibold">{{ date('d M Y', strtotime($servis->perkiraan_selesai)) }}</span>
                </div>
            </div>
            
            <div class="px-4 py-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-gray-50/50 text-xs">
                <div>
                    <span class="text-gray-500">Status Pengerjaan:</span>
                    @if($servis->status_servis == 'menunggu')
                        <span class="ml-1 px-2 py-0.5 rounded text-xs bg-yellow-100 text-yellow-800 font-bold border border-yellow-200 uppercase">Menunggu</span>
                    @elseif($servis->status_servis == 'proses')
                        <span class="ml-1 px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800 font-bold border border-blue-200 uppercase">Proses</span>
                    @elseif($servis->status_servis == 'selesai')
                        <span class="ml-1 px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 font-bold border border-green-200 uppercase">Selesai</span>
                    @endif
                </div>
                    {{-- PERUBAHAN DISINI: Menampilkan Status Kerja/Penugasan Teknisi Berdasarkan Enum --}}
                    <div>
                        <span class="text-gray-500">Status Kerja Teknisi:</span>
                        @if($servis->penugasan)
                            @if($servis->penugasan->status_penugasan == 'belum dikerjakan')
                                <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-gray-100 text-gray-700 font-bold border border-gray-200 uppercase">Belum Dikerjakan</span>
                            @elseif($servis->penugasan->status_penugasan == 'sedang dikerjakan')
                                <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-purple-100 text-purple-800 font-bold border border-purple-200 uppercase">Sedang Dikerjakan</span>
                            @elseif($servis->penugasan->status_penugasan == 'menunggu sparepart')
                                <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-amber-100 text-amber-800 font-bold border border-amber-200 uppercase">Menunggu Sparepart</span>
                            @elseif($servis->penugasan->status_penugasan == 'selesai')
                                <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-emerald-100 text-emerald-800 font-bold border border-emerald-200 uppercase">Selesai</span>
                            @elseif($servis->penugasan->status_penugasan == 'gagal')
                                <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-red-100 text-red-800 font-bold border border-red-200 uppercase">Gagal</span>
                            @else
                                <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-gray-100 text-gray-600 font-bold border border-gray-200 uppercase">{{ $servis->penugasan->status_penugasan }}</span>
                            @endif
                        @else
                            <span class="ml-1 px-2 py-0.5 rounded text-[11px] bg-gray-100 text-gray-400 italic font-medium border border-gray-200">Belum Ditugaskan</span>
                        @endif
                    </div>
                <div>
                    <span class="text-gray-500 font-semibold">Total Biaya Sementara:</span>
                    <strong class="ml-1 text-green-600 text-base">Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- BLOK 2: DATA PELANGGAN --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Pelanggan & Perangkat</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Nama Pelanggan</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->pelanggan->nama }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">No HP / WhatsApp</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->pelanggan->no_hp }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Merk / Tipe Unit</span>
                    <span class="text-gray-800 font-semibold">{{ $servis->booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Kategori Servis</span>
                    <span class="text-gray-800 font-bold capitalize">{{ $servis->booking->kategori_servis }}</span>
                </div>
            </div>

            <div class="px-4 pb-4 text-xs">
                <span class="text-gray-400 block mb-1">Keluhan Kerusakan:</span>
                <div class="bg-gray-50 border border-gray-200 rounded p-3 text-gray-700 italic">
                    "{{ $servis->booking->keluhan }}"
                </div>
            </div>
        </div>

        {{-- BLOK 3: TABEL RINCIAN NOTA --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Tindakan & Suku Cadang</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Deskripsi Tindakan / Suku Cadang</th>
                            <th class="w-1/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- LOOP DATA JASA --}}
                        @if(isset($servis->detailJasa) && $servis->detailJasa->count() > 0)
                            @foreach($servis->detailJasa as $jasa)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded mr-1 uppercase">Jasa</span>
                                        {{ $jasa->jasa->nama_jasa }}
                                    </td>
                                    <td class="px-4 py-2 text-center">1</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($jasa->harga ?? $jasa->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- LOOP DATA SPAREPART --}}
                        @if(isset($servis->detailSparepart) && $servis->detailSparepart->count() > 0)
                            @foreach($servis->detailSparepart as $sparepart)
                                <tr>
                                    <td class="px-4 py-2 text-gray-800 font-medium">
                                        <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                        {{ $sparepart->sparepart->nama_sparepart }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $sparepart->qty }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($sparepart->harga ?? ($sparepart->subtotal / $sparepart->qty), 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right font-bold text-gray-800">Rp {{ number_format($sparepart->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- KOSONG --}}
                        @if((!isset($servis->detailJasa) || $servis->detailJasa->count() == 0) && (!isset($servis->detailSparepart) || $servis->detailSparepart->count() == 0))
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/50">
                                    Belum ada data jasa atau sparepart pada nota ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- FOOTER TOTAL --}}
            <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Total Pembayaran Bill:</span>
                <strong class="text-xl font-bold text-blue-600">
                    Rp {{ number_format($servis->total_biaya, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiSelesai(id, kodeServis) {
        Swal.fire({
            title: 'Selesaikan Servis?',
            text: "Apakah Anda yakin ingin menyelesaikan servis ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981', 
            cancelButtonColor: '#6B7280',  
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal',
            border: 'none',
            borderRadius: '1rem'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-selesai-' + id).submit();
            }
        });
    }
</script>
@endsection