<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Seven Komputer</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

{{-- Mengubah overflow-hidden menjadi md:overflow-hidden agar di HP halaman tetap bisa di-scroll alami jika dibutuhkan --}}
<body class="bg-gray-100 overflow-x-hidden md:overflow-hidden">

    @php
        if (Auth::guard('web')->check()) {
            $user_login = Auth::guard('web')->user();
            $role_display = $user_login->role;
        } elseif (Auth::guard('pelanggan')->check()) {
            $user_login = Auth::guard('pelanggan')->user();
            $role_display = 'pelanggan';
        } else {
            $user_login = null;
            $role_display = 'Guest';
        }
    @endphp

    {{-- Layout Utama: Berubah dari vertikal (di HP) ke horizontal (di Desktop/md:) --}}
    <div class="flex flex-col md:flex-row h-screen w-screen relative">

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

        {{-- Kita bungkus @include('layouts.sidebar') agar bisa kita atur posisi sembunyi/munculnya di HP --}}
        <div id="sidebar-wrapper" class="fixed inset-y-0 left-0 z-50 -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 md:z-auto">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col min-w-0 h-full">

            <header class="h-[73px] bg-white border-b flex items-center justify-between px-4 md:px-8 shrink-0">
                
                {{-- Bagian Kiri Header: Ditambah Tombol Hamburger untuk HP --}}
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg md:hidden focus:outline-none" aria-label="Toggle Sidebar">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    
                    <div class="font-black uppercase tracking-widest text-green-600 text-sm md:text-base truncate">
                        {{ getSetting('judul_website') }}
                    </div>
                </div>

                @if($user_login)
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="text-right hidden sm:block">
                        <small class="text-gray-400 text-xs block">
                            Status: <span class="capitalize font-bold text-blue-600">{{ $role_display }}</span>
                        </small>
                        <span class="font-semibold text-sm md:text-base text-gray-800 line-clamp-1">
                            {{ $user_login->nama }}
                        </span>
                    </div>
                
                    <div class="w-10 h-10 md:w-11 md:h-11 bg-blue-600 rounded-full flex items-center justify-center border-2 border-blue-200 shadow text-white font-bold text-sm md:text-base shrink-0">
                        {{ strtoupper(substr($user_login->nama, 0, 1)) }}
                    </div>
                </div>
                @endif
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar-wrapper');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Toggle class translate untuk menggeser sidebar masuk/keluar layar
            sidebar.classList.toggle('-translate-x-full');
            // Toggle overlay hitam di belakangnya
            overlay.classList.toggle('hidden');
        }
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>