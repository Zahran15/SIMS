@extends('layouts.layout')

@section('title', 'Detail Pembayaran')

@section('content')
    {{-- HEADER HALAMAN & TOMBOL --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b pb-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Transaksi Pembayaran</h2>
            <p class="text-gray-500 text-xs">Pantau rincian tagihan dan status integrasi pembayaran instan Anda</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('pelanggan.pembayaran.index') }}"
               class="px-3 py-1.5 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs shadow-sm transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-4 py-2 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wide">Rincian Transaksi</h3>
            </div>

            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 block">Kode Booking Terkait</span>
                    <strong class="text-blue-600 text-sm tracking-wide">{{ $pembayaran->booking->kode_booking }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block">Jenis Pembayaran</span>
                    <span class="text-gray-800 font-semibold text-sm">
                        {{ ucfirst($pembayaran->jenis_pembayaran) }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block">Total Nominal</span>
                    <span class="text-gray-900 font-bold text-sm">
                        Rp {{ number_format($pembayaran->nominal,0,',','.') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Status Pembayaran</span>
                    @if($pembayaran->status_pembayaran == 'sukses')
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-green-100 text-green-800 border border-green-200 uppercase inline-block"> Lunas</span>
                    @elseif($pembayaran->status_pembayaran == 'pending')
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase inline-block">Pending</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-800 border border-red-200 uppercase inline-block">Gagal</span>
                    @endif
                </div>
            </div>
        </div>

        @if($pembayaran->status_pembayaran != 'sukses')
            <div class="bg-white rounded border border-gray-200 shadow-sm p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500 text-center sm:text-left">
                    <strong class="text-gray-700 block text-sm mb-0.5">Selesaikan Pembayaran Anda</strong>
                    Gunakan payment gateway Midtrans untuk membayar secara instan dan aman melalui berbagai metode transfer.
                </div>
                <button type="button" id="btnBayar"
                        class="w-full sm:w-auto text-center px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-100 transition-all uppercase tracking-wider">
                    Bayar Sekarang
                </button>
            </div>
        @endif

        {{-- ALERT INFORMASI DINAMIS --}}
        @if($pembayaran->status_pembayaran == 'sukses')
            <div class="p-3 bg-green-50 border border-green-200 rounded text-xs text-green-800 flex items-start gap-2 shadow-sm">
                <div>
                    <strong class="block mb-0.5">Pembayaran Sukses / Lunas</strong>
                    <span>Transaksi ini telah berhasil diverifikasi oleh sistem. Riwayat tagihan Anda untuk kode booking ini dinyatakan selesai. Terima kasih!</span>
                </div>
            </div>
        @elseif($pembayaran->status_pembayaran == 'pending')
            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800 flex items-start gap-2 shadow-sm">
                <div>
                    <strong class="block mb-0.5">Menunggu Pembayaran</strong>
                    <span>Tagihan ini berstatus pending. Silakan klik tombol <b>Bayar Sekarang</b> untuk memunculkan instruksi pembayaran elektronik atau mengecek ulang status transfer Anda.</span>
                </div>
            </div>
        @else
            <div class="p-3 bg-red-50 border border-red-200 rounded text-xs text-red-800 flex items-start gap-2 shadow-sm">
                <div>
                    <strong class="block mb-0.5">Transaksi Gagal</strong>
                    <span>Pembayaran melewati batas waktu tenggat (*expired*) atau dibatalkan. Silakan lakukan koordinasi ulang dengan customer service atau admin toko jika terdapat kendala saldo.</span>
                </div>
            </div>
        @endif

    </div>
</div>

{{-- GATEWAY MIDTRANS SCRIPTS --}}
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){
    let btnBayar = document.getElementById('btnBayar');
    if(btnBayar){
        btnBayar.addEventListener('click', function(){
            fetch(
                "{{ route('pelanggan.pembayaran.bayar',$pembayaran->id_pembayaran) }}",
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }
            )
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    snap.pay(data.token, {
                        onSuccess: function(result){
                            fetch(
                                "{{ route('pelanggan.pembayaran.success',$pembayaran->id_pembayaran) }}",
                                {
                                    method: 'POST',
                                    headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                    body: JSON.stringify({transaction_id: result.transaction_id})
                                }
                            )
                            .then(res => res.json())
                            .then(data => {
                                alert('Pembayaran berhasil');
                                location.reload();
                            });
                        },
                        onPending: function(result){
                            alert('Menunggu pembayaran');
                            location.reload();
                        },
                        onError: function(result){
                            alert('Pembayaran gagal');
                        },
                        onClose: function(){
                            console.log('Popup ditutup');
                        }
                    });
                }else{
                    alert(data.message);
                }
            });
        });
    }
});
</script>
@endsection