@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6 uppercase tracking-wider">Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 flex justify-between items-center">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Booking Baru</p>
            <p class="text-3xl font-bold text-green-500 mt-1">0</p>
        </div>
        <i class="far fa-clipboard text-3xl text-green-500"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500 flex justify-between items-center">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Verifikasi Deposit</p>
            <p class="text-3xl font-bold text-purple-500 mt-1">0</p>
        </div>
        <i class="fas fa-money-bill-wave text-3xl text-purple-500"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-teal-500 flex justify-between items-center">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Dalam Proses</p>
            <p class="text-3xl font-bold text-teal-500 mt-1">0</p>
        </div>
        <i class="fas fa-sync-alt text-3xl text-teal-500"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Siap Diambil</p>
            <p class="text-3xl font-bold text-blue-500 mt-1">0</p>
        </div>
        <i class="fas fa-laptop text-3xl text-blue-500"></i>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
    <div class="bg-orange-500 text-white px-6 py-2 text-center font-bold text-sm tracking-widest uppercase">
        Booking
    </div>
    <div class="p-6">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b text-gray-400 uppercase text-[11px] tracking-wider">
                    <th class="py-3 px-2">No</th>
                    <th>Nama Pelanggan</th>
                    <th>Tgl Booking</th>
                    <th>Keluhan</th>
                    <th>Status Deposit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b hover:bg-gray-50 transition">
                    <td colspan="6" class="py-10 text-center text-gray-400 italic">Belum ada data booking terbaru</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection