<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - {{ getSetting('judul_website') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Figtree', sans-serif; }
        
        /* Background Image dengan Overlay (Sama dengan Login) */
        .bg-register {
            background-image: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.7)), 
            url('{{ getSetting("bg_register") }}');
            background-size: cover;
            background-position: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        /* Custom scrollbar untuk form yang panjang */
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-register flex items-center justify-center min-h-screen p-4 relative overflow-hidden">
    
    <!-- Tombol Back ke Beranda (Kontras Putih) -->
    <a href="/" class="absolute top-8 left-8 text-white/70 hover:text-white transition-all hidden md:flex items-center gap-2 font-bold text-xs uppercase tracking-widest z-20">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="glass-card rounded-[2.5rem] shadow-2xl flex overflow-hidden w-full max-w-4xl min-h-[600px] z-10 border border-white/20">
        
        <!-- Sisi Kiri: Visual Branding (Register Version - Simpel) -->
        <div class="hidden md:flex w-1/2 bg-slate-900/80 text-white p-12 flex-col justify-between relative overflow-hidden border-r border-white/10">

            <!-- [ATAS] Judul & Branding -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-emerald-600 to-blue-500 p-2.5 rounded-2xl rotate-3 shadow-lg">
                        <i class="fas fa-user-plus text-xl text-white"></i>
                    </div>
                    <!-- Mengganti teks statis S.I.M.S dengan getSetting -->
                    <span class="text-2xl font-black text-emerald-500 uppercase tracking-tighter italic">
                        {{ getSetting('nama_aplikasi') }}
                    </span>
                </div>
            </div>

            <!-- [TENGAH] Visual Simbolik (Satu Icon Utama - Simpel) -->
            <div class="relative z-10 flex justify-center items-center py-10">
                <div class="relative w-64 h-64 flex items-center justify-center">
                    <!-- Icon Utama -->
                    <div class="z-20 bg-slate-800/50 p-8 rounded-[3rem] border border-white/10 backdrop-blur-xl animate-pulse">
                        <i class="fas fa-id-card text-7xl text-emerald-500"></i>
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

        <!-- Sisi Kanan: Form Register -->
        <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <h3 class="text-2xl font-black text-slate-800 mb-1">Buat Akun Baru</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Silahkan lengkapi data diri Anda</p>
            </div>

            <form method="POST" action="/register" class="space-y-4 custom-scroll overflow-y-auto max-h-[420px] pr-2">
                @csrf

                <!-- Row 1: Kode & Nama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Kode Pelanggan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-600">
                                <i class="fas fa-id-badge text-sm"></i>
                            </span>
                            <input type="text" value="{{ $kode }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 rounded-xl pl-11 pr-3 py-3 text-sm font-black text-gray-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Nama Lengkap</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="fas fa-user text-sm"></i>
                            </span>
                            <input type="text" name="nama" placeholder="Jhon Doe" required
                                class="w-full bg-gray-100/50 border border-gray-200 rounded-xl pl-11 pr-3 py-3 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all outline-none">
                        </div>
                    </div>
                </div>

                <!-- Row 2: Email & WA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Email</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="fas fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" placeholder="mail@anda.com" required
                                class="w-full bg-gray-100/50 border border-gray-200 rounded-xl pl-11 pr-3 py-3 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">WhatsApp</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </span>
                            <input type="text" name="no_hp" placeholder="0812..." required
                                class="w-full bg-gray-100/50 border border-gray-200 rounded-xl pl-11 pr-3 py-3 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all outline-none">
                        </div>
                    </div>
                </div>

                <!-- Row 3: Alamat -->
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Alamat Lengkap</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-map-location-dot text-sm"></i>
                        </span>
                        <input type="text" name="alamat" placeholder="Jl. Raya Cilacap No. XX" required
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-xl pl-11 pr-3 py-3 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all outline-none">
                    </div>
                </div>

                <!-- Row 4: Password -->
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-xl pl-11 pr-3 py-3 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all outline-none">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="eyeIcon" class="fas fa-eye text-sm hidden"></i>
                            <i id="eyeOffIcon" class="fas fa-eye-slash text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-4 pb-2">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-[0.2em] hover:bg-slate-900 shadow-xl shadow-blue-200 hover:shadow-slate-200 transition-all duration-500 active:scale-[0.98]">
                        Daftar Akun Baru <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>

            <div class="text-xs text-center mt-6">
                <span class="text-gray-400 font-medium">Sudah memiliki akun?</span>
                <a href="/login" class="text-blue-600 font-black uppercase tracking-tighter hover:text-amber-500 transition-colors ml-1">Login Sekarang</a>
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