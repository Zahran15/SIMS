@extends('layouts.layout')

@section('title', 'Detail Request Sparepart - Pelanggan')

@section('content')

    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Request Sparepart</h2>
            <p class="text-gray-500 text-xs">Informasi lengkap status, rincian biaya, dan konfirmasi penggantian komponen perangkat.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('pelanggan.request_sparepart.index') }}" 
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- LAYOUT MENURUN --}}
    <div class="space-y-4">

        {{-- BLOK 1: INFORMASI UTAMA & STATUS --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Informasi Perangkat & Status Pengajuan</h3>
                
                {{-- BADGE STATUS REQUEST SPAREPART --}}
                <div>
                    @if($requestSparepart->status_request == 'dikirim_ke_pelanggan')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">Menunggu Persetujuan Anda</span>
                    @elseif($requestSparepart->status_request == 'disetujui_pelanggan')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-blue-100 text-blue-700 border border-blue-200">Telah Anda Setujui</span>
                    @elseif($requestSparepart->status_request == 'disetujui')
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-green-100 text-green-700 border border-green-200">Selesai / Diproses Admin</span>
                    @else
                        <span class="px-2.5 py-0.5 text-[11px] rounded font-bold uppercase bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                    @endif
                </div>
            </div>

            {{-- Info Utama Grid --}}
            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">ID Request</span>
                    <strong class="text-gray-800 text-sm">#{{ $requestSparepart->id_request }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nomor Nota Servis</span>
                    <strong class="text-blue-600 text-sm">#{{ $requestSparepart->penugasan->servis->kode_servis ?? '-' }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Nama Perangkat</span>
                    <span class="text-gray-800 font-semibold">{{ $requestSparepart->penugasan->servis->booking->merk_tipe }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Kategori Servis</span>
                    <span class="text-indigo-700 font-bold uppercase text-[10px] bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 inline-block mt-0.5">
                        {{ $requestSparepart->penugasan->servis->booking->kategori_servis ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- BLOK 2: TABEL RINCIAN KOMPONEN & ESTIMASI BIAYA --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Komponen & Estimasi Biaya</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600 table-fixed">
                    <thead class="bg-gray-100 uppercase font-bold text-gray-600 border-b">
                        <tr>
                            <th class="w-5/12 px-4 py-2">Nama Sparepart / Komponen</th>
                            <th class="w-2/12 px-4 py-2 text-right">Harga Satuan</th>
                            <th class="w-2/12 px-4 py-2 text-center">QTY</th>
                            <th class="w-3/12 px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-gray-800 font-medium">
                                <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded mr-1 uppercase">Part</span>
                                {{ $requestSparepart->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}
                                <span class="block text-[10px] text-gray-400 font-normal mt-0.5">Kategori: {{ $requestSparepart->sparepart->kategori ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-2 text-right text-gray-700 font-medium">
                                Rp {{ number_format($requestSparepart->sparepart->harga_jual ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-center font-bold text-gray-800">
                                {{ $requestSparepart->jumlah }} Pcs
                            </td>
                            <td class="px-4 py-2 text-right font-bold text-blue-600 bg-blue-50/20">
                                Rp {{ number_format(($requestSparepart->sparepart->harga_jual ?? 0) * $requestSparepart->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        {{-- ROW TOTAL ESTIMASI --}}
                        <tr class="bg-gray-50/70 font-bold border-t-2 border-gray-100 text-gray-700">
                            <td colspan="3" class="px-4 py-3 text-right uppercase tracking-wider text-[10px]">
                                Total Estimasi Tambahan Biaya:
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-extrabold text-gray-900 bg-gray-100/50">
                                Rp {{ number_format(($requestSparepart->sparepart->harga_jual ?? 0) * $requestSparepart->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BLOK 3: CATATAN REKOMENDASI TEKNISI --}}
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Catatan Rekomendasi dari Teknisi</h3>
            </div>
            <div class="p-4 text-xs bg-gray-50/30">
                <div class="bg-white border border-gray-200 rounded p-3 text-gray-700 italic min-h-[50px]">
                    "{{ $requestSparepart->alasan }}"
                </div>
            </div>
        </div>

        {{-- PANEL AKSI KONFIRMASI / FOOTER INFORMASI --}}
        @if($requestSparepart->status_request == 'dikirim_ke_pelanggan')
            <div class="p-4 bg-gray-50 border border-gray-200 rounded shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
                <div class="text-center md:text-left">
                    <p class="font-bold text-gray-800">Apakah Anda menyetujui penggantian komponen beserta biaya di atas?</p>
                    <p class="text-gray-400 mt-0.5">Biaya ini akan otomatis diakumulasikan ke dalam total nota invoice servis Anda.</p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto justify-center">
                    {{-- FORM REJECT/TOLAK --}}
                    <form id="form-reject" action="{{ route('pelanggan.request_sparepart.reject', $requestSparepart->id_request) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded bg-white border border-red-200 text-red-600 hover:bg-red-50 font-bold text-xs shadow-sm transition-colors whitespace-nowrap">
                            Tolak Pergantian
                        </button>
                    </form>

                    {{-- FORM APPROVE/SETUJU --}}
                    <form id="form-approve" action="{{ route('pelanggan.request_sparepart.approve', $requestSparepart->id_request) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded bg-green-600 hover:bg-green-700 text-white font-bold text-xs shadow-sm transition-colors whitespace-nowrap">
                            Setujui & Lanjutkan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="p-3 bg-gray-100 border border-gray-200 rounded text-center text-xs text-gray-400 font-medium">
                Pengajuan ini sudah ditinjau dan tidak memerlukan tindakan lebih lanjut dari Anda.
            </div>
        @endif

    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handler untuk Tombol Tolak
    const formReject = document.getElementById('form-reject');
    if (formReject) {
        formReject.addEventListener('submit', function (e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan MENOLAK pergantian komponen ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    formReject.submit();
                }
            });
        });
    }

    // Handler untuk Tombol Setuju
    const formApprove = document.getElementById('form-approve');
    if (formApprove) {
        formApprove.addEventListener('submit', function (e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: "Apakah Anda MENYETUJUI pergantian komponen beserta biaya ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setuju!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    formApprove.submit();
                }
            });
        });
    }
});
</script>
@endsection