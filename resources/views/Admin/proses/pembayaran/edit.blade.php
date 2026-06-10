@extends('layouts.layout')

@section('title', 'Ubah Metode Pembayaran')

@section('content')
<div class="mb-4 border-b pb-3">
    <h2 class="text-2xl font-bold text-gray-800">Ubah Metode Pembayaran</h2>
    <p class="text-gray-500 text-xs">Jika dialihkan ke CASH, integrasi Midtrans otomatis terputus.</p>
</div>

<div class="max-w-md bg-white rounded border p-4 text-xs shadow-sm">
    <form action="{{ route('admin.pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin? Mengubah ke Cash akan memotong jalur Midtrans dan menandai tagihan ini sebagai Lunas/Sukses.')">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            <div>
                <label class="block text-gray-600 font-bold mb-1">Kode Booking</label>
                <input type="text" class="w-full p-2 bg-gray-100 border rounded" value="{{ $pembayaran->booking->kode_booking }}" disabled>
            </div>
            <div>
                <label class="block text-gray-600 font-bold mb-1">Nominal</label>
                <input type="text" class="w-full p-2 bg-gray-100 border rounded font-bold" value="Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}" disabled>
            </div>
            <div>
                <label class="block text-gray-600 font-bold mb-1">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="w-full p-2 border rounded focus:ring-1 focus:ring-blue-500">
                    <option value="midtrans" {{ $pembayaran->metode_pembayaran == 'midtrans' ? 'selected' : '' }}>MIDTRANS (Online)</option>
                    <option value="cash" {{ $pembayaran->metode_pembayaran == 'cash' ? 'selected' : '' }}>CASH / TUNAI (Manual Kasir)</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold shadow-sm">Simpan Perubahan</button>
            <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection