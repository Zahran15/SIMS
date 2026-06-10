<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Seven Komputer</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-gray-100 overflow-hidden">

    @php
        // LOGIC UNTUK MENENTUKAN DATA USER/PELANGGAN YANG SEDANG LOGIN
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

    <div class="flex h-screen w-screen">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0">

            <header class="h-[73px] bg-white border-b flex items-center justify-between px-8">
                <div class="font-black uppercase tracking-widest text-green-600">
                    {{ getSetting('judul_website') }}
                </div>

                @if($user_login)
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <small class="text-gray-400 text-xs block">
                            Status: Login sebagai <span class="capitalize font-bold text-blue-600">{{ $role_display }}</span>
                        </small>
                        <span class="font-semibold text-gray-800">
                            {{ $user_login->nama }}
                        </span>
                    </div>
                
                    <div class="w-11 h-11 bg-blue-600 rounded-full flex items-center justify-center border-2 border-blue-200 shadow text-white font-bold">
                        {{ strtoupper(substr($user_login->nama, 0, 1)) }}
                    </div>
                </div>
                @endif
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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