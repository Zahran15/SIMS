<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - {{ getSetting('judul_website') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Figtree', sans-serif; }
        
        /* Background Image diambil secara dinamis dari database */
        .bg-login {
            background-image: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.7)), 
            url('{{ getSettingAsset("bg_login") }}');
            background-size: cover;
            background-position: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-login flex items-center justify-center min-h-screen p-4 relative overflow-hidden">
    
    <!-- Tombol Back ke Home (Dibuat lebih kontras karena background gelap) -->
    <a href="/" class="absolute top-8 left-8 text-white/70 hover:text-white transition-all hidden md:flex items-center gap-2 font-bold text-xs uppercase tracking-widest z-20">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="glass-card rounded-[2.5rem] shadow-2xl flex overflow-hidden w-full max-w-4xl min-h-[580px] z-10 border border-white/20">
        
        <!-- Sisi Kiri: Visual Branding -->
        <div class="hidden md:flex w-1/2 bg-slate-900/80 text-white p-12 flex-col justify-between relative overflow-hidden border-r border-white/10">
        
            <!-- [ATAS] Judul & Branding -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-blue-600 to-emerald-500 p-2.5 rounded-2xl rotate-3 shadow-lg">
                        <i class="fas fa-laptop-medical text-xl text-white"></i>
                    </div>
                    <!-- Mengganti teks statis S.I.M.S dengan getSetting -->
                    <span class="text-2xl font-black text-emerald-500 uppercase tracking-tighter italic">
                        {{ getSetting('nama_aplikasi') }}
                    </span>
                </div>
            </div>
        
            <!-- [TENGAH] Visual Simbolik (Floating Tech Orbit) -->
            <div class="relative z-10 flex justify-center items-center py-10">
                <div class="relative w-64 h-64 flex items-center justify-center">
                    <!-- Icon Utama -->
                    <div class="z-20 bg-slate-800/50 p-8 rounded-[3rem] border border-white/10 backdrop-blur-xl animate-pulse">
                        <i class="fas fa-microchip text-7xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        
            <!-- [BAWAH] Teks Deskripsi -->
            <div class="relative z-10">
                <!-- Footer Kecil -->
                <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3em]">
                        &copy; 2026 Seven Komputer Cilacap
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="w-full md:w-1/2 p-8 md:p-14 flex flex-col justify-center">
            <div class="mb-10 text-center md:text-left">
                <h3 class="text-3xl font-black text-slate-800 mb-2">Login Akun</h3>
                <p class="text-sm text-gray-400 font-medium">Silahkan masuk ke sistem manajemen.</p>
            </div>

            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                <i data-lucide="check-circle" class="h-5 w-5 mr-3"></i>
                <p class="text-sm font-medium italic">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100 flex items-center">
                <i data-lucide="alert-circle" class="h-4 w-4 mr-2"></i>
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="/login" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 ml-1">Email Address</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" placeholder="email@anda.com" required
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-300 text-sm">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 ml-1">Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-300 text-sm">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="eyeIcon" class="fas fa-eye text-sm hidden"></i>
                            <i id="eyeOffIcon" class="fas fa-eye-slash text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-[0.2em] hover:bg-slate-900 shadow-xl shadow-blue-200 hover:shadow-slate-200 transition-all duration-500 active:scale-[0.98]">
                        Masuk Sekarang <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>

            <div class="text-xs text-center mt-10">
                <span class="text-gray-400 font-medium">Belum punya akun?</span>
                <a href="/register" class="text-blue-600 font-black uppercase tracking-tighter hover:text-amber-500 transition-colors ml-1">Daftar Akun Baru</a>
            </div>
        </div>
    </div>

<script>
function togglePassword() {
    const passwordInput = document.querySelector('input[name="password"]');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    }
}
</script>
</body>
</html>