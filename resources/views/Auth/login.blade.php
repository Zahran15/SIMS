<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Seven Komputer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-lg flex overflow-hidden w-full max-w-4xl">
        <div class="hidden md:flex w-1/2 bg-gradient-to-br from-blue-500 to-blue-900 text-white p-8 flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-tight">
                    Sistem Informasi<br>
                    Manajemen Servis Laptop
                </h2>
            </div>
            <div class="text-xs text-blue-200">
                &copy; 2024 Seven Komputer. All rights reserved.
            </div>
        </div>

        <div class="w-full md:w-1/2 p-10">

            <div class="text-center mb-8">
                <img src="{{ asset('images/8pp-crop.jpg') }}" class="mx-auto w-16 mb-4">                
                <h3 class="text-xl font-bold text-gray-800">Selamat Datang</h3>
                <p class="text-xs text-gray-400">Silahkan masuk untuk melanjutkan</p>
            </div>

            @if(session('error'))
                <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="mb-5">
                    <label class="text-sm text-gray-600 block mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" placeholder="Masukkan email" required
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="text-sm text-gray-600 block mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" placeholder="Masukkan password" required
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 shadow-md hover:shadow-lg transition-all duration-300">
                    Masuk
                </button>
            </form>

            <div class="text-sm text-center mt-8">
                <span class="text-gray-500">Belum punya akun?</span>
                <a href="/register" class="text-blue-600 font-bold hover:underline ml-1">Buat Akun</a>
            </div>
        </div>
    </div>
</body>
</html>