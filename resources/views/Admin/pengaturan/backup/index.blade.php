@extends('layouts.layout')

@section('title', 'Backup Data')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Pusat Cadangan Data (Backup)</h1>
            <p class="text-gray-600">Amankan data database dan source code aplikasi secara berkala.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card Backup Database SQL -->
            <div class="bg-white rounded-lg shadow-md p-8 border-t-4 border-blue-500">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Database SQL</h2>
                        <p class="text-gray-500 text-sm">Ekspor file .sql</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                    Mengambil seluruh data transaksi, pelanggan, dan riwayat servis. Cocok untuk pemulihan data cepat.
                </p>
                <a href="{{ route('admin.backup.download') }}" 
                   class="flex justify-center items-center w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download SQL
                </a>
            </div>

            <!-- Card Backup Source Code -->
            <div class="bg-white rounded-lg shadow-md p-8 border-t-4 border-green-500">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-green-100 rounded-full mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Source Code</h2>
                        <p class="text-gray-500 text-sm">Kompres file .zip</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                    Mencadangkan seluruh codingan (Laravel, View, Controller). Berguna untuk pengembangan lebih lanjut/migrasi server.
                </p>
                <a href="{{ route('admin.backup.code') }}" 
                   class="flex justify-center items-center w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Project
                </a>
            </div>
        </div>
    </div>
</div>
@endsection