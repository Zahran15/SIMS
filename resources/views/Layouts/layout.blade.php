<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Seven Komputer</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
    
    {{-- Bagian Kiri Header: Tombol Hamburger & Judul Website --}}
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg md:hidden focus:outline-none" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        
        <div class="font-black uppercase tracking-widest text-green-600 text-sm md:text-base truncate">
            {{ getSetting('judul_website') }}
        </div>
    </div>

    @if($user_login)
    <div class="flex items-center gap-3 md:gap-5">
        
        @php
            $unreadNotifications = collect();
            $unreadCount = 0;
            
            if ($user_login && method_exists($user_login, 'unreadNotifications')) {
                try {
                    // Ambil max 5 notifikasi terbaru yang belum dibaca
                    $unreadNotifications = $user_login->unreadNotifications()->take(5)->get();
                    $unreadCount = $user_login->unreadNotifications()->count();
                } catch (\Exception $e) {
                    $unreadCount = 0;
                }
            }
        @endphp

        <!-- Tombol & Dropdown Lonceng Notifikasi (Alpine.js Container) -->
        <div x-data="{ open: false }" class="relative" @click.outside="open = false">
            
            <!-- Tombol Lonceng -->
            <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition focus:outline-none">
                <i class="fa-solid fa-bell text-xl"></i>

                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Menu Dropdown Notifikasi -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 style="display: none;"
                 class="absolute right-0 mt-2 w-80 md:w-96 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                
                <!-- Header Dropdown -->
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-gray-800 text-sm">Notifikasi</h3>
                        @if($unreadCount > 0)
                            <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full font-semibold">
                                {{ $unreadCount }} Baru
                            </span>
                        @endif
                    </div>

                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Tandai dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Body / List Notifikasi -->
                <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                    @forelse($unreadNotifications as $notification)
                        <div class="p-3.5 hover:bg-gray-50 transition flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="{{ $notification->data['icon'] ?? 'fa-solid fa-circle-info' }} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate">
                                    {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <span class="text-[10px] text-gray-400 mt-1 block">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            <i class="fa-regular fa-bell-slash text-3xl mb-2 block"></i>
                            <p class="text-xs">Belum ada notifikasi baru</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer Dropdown -->
                <div class="p-3 border-t border-gray-100 bg-gray-50 text-center">
                    <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold block">
                        Lihat Semua Notifikasi
                    </a>
                </div>

            </div>
        </div>

        {{-- Profil User --}}
        <div class="flex items-center gap-3 md:gap-4 pl-2 border-l border-gray-200">
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