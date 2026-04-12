<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Seven Komputer</title>
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

        <div class="w-full md:w-1/2 p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('images/8pp-crop.jpg') }}" class="mx-auto w-16 mb-4">                
                <p class="text-sm text-gray-500">Buat Akun Pelanggan</p>
            </div>

            <form method="POST" action="/register">
                @csrf

                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">Kode Pelanggan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-tag"></i>
                        </span>
                        <input type="text" value="{{ $kode }}" readonly
                            class="w-full border rounded pl-10 pr-3 py-2 bg-gray-50 text-gray-500 outline-none">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="nama" placeholder="Masukkan nama" required
                            class="w-full border rounded pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" placeholder="Masukkan email" required
                            class="w-full border rounded pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">No. Handphone</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="text" name="no_hp" placeholder="Masukkan No. HP" required
                            class="w-full border rounded pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-600 mb-1 block">Alamat</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" name="alamat" placeholder="Alamat lengkap" required
                            class="w-full border rounded pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="text-sm text-gray-600 mb-1 block">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full border rounded pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition font-semibold shadow-md">
                    Daftar
                </button>
            </form>

            <p class="text-sm text-center mt-6 text-gray-600">
                Sudah punya akun?
                <a href="/login" class="text-blue-600 font-bold hover:underline">Masuk</a>
            </p>

        </div>
    </div>

</body>
</html>