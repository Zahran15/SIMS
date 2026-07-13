@extends('layouts.layout')

@section('title', 'Pengaturan Website')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-4 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold mb-6 text-gray-800">Pengaturan Website</h2>
        
        <form action="{{ route('admin.website.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <!-- Bagian Input Form di index.blade.php Anda -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Nama Aplikasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
                        <input type="text" name="nama_aplikasi" value="{{ getSetting('nama_aplikasi') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    </div>

                    <!-- Judul Website -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Website</label>
                        <input type="text" name="judul_website" value="{{ getSetting('judul_website') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Logo Aplikasi</label>
                        <input type="file" name="logo_aplikasi" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <!-- Background Login -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Background Login</label>
                        <input type="file" name="bg_login" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <!-- Background Register -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Background Register</label>
                        <input type="file" name="bg_register" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <!-- Background Reset -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Background Reset Password</label>
                        <input type="file" name="bg_reset" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

            <div class="mt-8 flex justify-start">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200 shadow-sm text-sm sm:text-base">
                    Simpan Perubahan 
                </button>
            </div>
        </form>
    </div>
@endsection