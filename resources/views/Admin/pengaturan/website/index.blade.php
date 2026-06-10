@extends('layouts.layout')

@section('title', 'Pengaturan Website')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold mb-6">Pengaturan Website</h2>
        
        <form action="{{ route('admin.website.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <!-- Bagian Input Form di index.blade.php Anda -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Aplikasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
                        <input type="text" name="nama_aplikasi" value="{{ getSetting('nama_aplikasi') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Judul Website -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Website</label>
                        <input type="text" name="judul_website" value="{{ getSetting('judul_website') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Logo Aplikasi</label>
                        <input type="file" name="logo_aplikasi" class="mt-1 block w-full">
                    </div>

                    <!-- Background Login -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Background Login</label>
                        <input type="file" name="bg_login" class="mt-1 block w-full">
                    </div>

                    <!-- Background Register -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Background Register</label>
                        <input type="file" name="bg_register" class="mt-1 block w-full">
                    </div>
                </div>

            <button type="submit" class="mt-8 px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Simpan Perubahan 
            </button>
        </form>
    </div>
</div>
@endsection