@extends('layouts.layout')

@section('title', 'Ubah Metode Pembayaran')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- HEADER HALAMAN & TOMBOL --}}
<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Ubah Metode Pembayaran</h2>
        <p class="text-gray-500 text-xs">Ubah jalur transaksi tagihan dan kelola status integrasi gateway pembayaran instan Anda</p>
    </div>
    
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.pembayaran.index') }}"
           class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
            Kembali
        </a>
    </div>
</div>

<div class="space-y-4">
    {{-- CARD FORM UTAMA --}}
    <div class="bg-white rounded border border-gray-200 shadow-sm">
        {{-- Card Header --}}
        <div class="px-4 py-2 border-b bg-gray-50">
            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Form Perubahan Metode</h3>
        </div>

        {{-- Card Body --}}
        <div class="p-4 text-xs">
            <form id="form-pembayaran" action="{{ route('admin.pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Row Konten Form dengan struktur Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-gray-400 block mb-1">Kode Booking</span>
                        <input type="text" class="w-full p-2 bg-gray-50 border border-gray-200 rounded text-blue-600 font-bold text-sm tracking-wide focus:outline-none cursor-not-allowed" value="{{ $pembayaran->booking->kode_booking }}" disabled>
                    </div>

                    <div>
                        <span class="text-gray-400 block mb-1">Nominal Tagihan</span>
                        <input type="text" class="w-full p-2 bg-gray-50 border border-gray-200 rounded text-gray-900 font-bold text-sm focus:outline-none cursor-not-allowed" value="Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}" disabled>
                    </div>

                    <div>
                        <span class="text-gray-400 block mb-1">Metode Pembayaran</span>
                        {{-- MENAMBAHKAN ID select-metode --}}
                        <select name="metode_pembayaran" id="select-metode"
                                class="w-full p-2 border border-gray-300 rounded font-semibold text-gray-800 bg-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            <option value="transfer" {{ $pembayaran->metode_pembayaran == 'transfer' }}>
                                TRANSFER (Online)
                            </option>
                            <option value="cash" {{ $pembayaran->metode_pembayaran == 'cash' }}>
                                CASH / TUNAI (Manual Kasir)
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.pembayaran.index') }}" 
                       class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs transition-colors shadow-sm">
                        Batal
                    </a>
                    {{-- MENAMBAHKAN ID btn-simpan DAN CLASS disabled: --}}
                    <button type="submit" id="btn-simpan"
                            class="px-4 py-1.5 rounded bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all shadow-sm disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed disabled:hover:bg-gray-300">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ALERT INFORMASI STATIS --}}
    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800 flex items-start gap-2 shadow-sm">
        <div>
            <strong class="block mb-0.5">Peringatan Integrasi</strong>
            <span>Jika metode dialihkan ke <b>CASH / TUNAI</b>, jalur otomatisasi tagihan Midtrans otomatis terputus dan sistem akan langsung menandai tagihan ini sebagai Lunas/Sukses secara manual melalui kasir.</span>
        </div>
    </div>
</div>

{{-- SCRIPT MANAGEMENT VALIDASI TOMBOL DAN SWEETALERT2 --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('form-pembayaran');
    let selectMetode = document.getElementById('select-metode');
    let btnSimpan = document.getElementById('btn-simpan');

    // Fungsi untuk cek status metode pembayaran
    function toggleSubmitButton() {
        if (selectMetode.value === 'cash') {
            btnSimpan.removeAttribute('disabled');
        } else {
            btnSimpan.setAttribute('disabled', 'true');
        }
    }

    // Jalankan fungsi saat halaman pertama kali dimuat (antisipasi jika default-nya sudah cash)
    toggleSubmitButton();

    // Jalankan fungsi setiap kali pilihan select box berubah
    selectMetode.addEventListener('change', toggleSubmitButton);
    
    // Handler SweetAlert saat Form Submit
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Ubah ke Cash dan tagihan ini Lunas/Sukses.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', 
                cancelButtonColor: '#6b7280', 
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); 
                }
            });
        });
    }
});
</script>
@endsection