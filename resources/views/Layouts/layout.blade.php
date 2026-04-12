<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Seven Komputer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">
            
            <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
                <div class="text-green-600 font-bold tracking-widest">
                    SEVEN <span class="text-orange-500">KOMPUTER</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-gray-700 text-sm font-medium">Selamat Datang, {{ Session::get('nama') }}</span>
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center border">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                </div>
            </header>

            <main class="p-8">
                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000 });
    </script>
    @endif

</body>
</html>