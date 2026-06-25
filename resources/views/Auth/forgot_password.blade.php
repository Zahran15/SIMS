<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Ulang Kata Sandi - {{ getSetting('judul_website') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
        
        /* Background Image disamakan persis dengan halaman login */
        .bg-reset {
            background-image: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.7)), 
            url('{{ getSettingAsset("bg_reset") }}');
            background-size: cover;
            background-position: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-reset flex items-center justify-center min-h-screen p-4 relative overflow-hidden">
    
    <a href="/login" class="absolute top-8 left-8 text-white/70 hover:text-white transition-all hidden md:flex items-center gap-2 font-bold text-xs uppercase tracking-widest z-20">
        <i class="fas fa-arrow-left"></i> Kembali ke Login
    </a>

    <div class="glass-card rounded-[2.5rem] shadow-2xl flex overflow-hidden w-full max-w-4xl min-h-[580px] z-10 border border-white/20"
         x-data="{ 
            email: '', 
            isSending: false, 
            countdown: 0,
            successMessage: '',
            errorMessage: '',
            sendCode() {
                if(!this.email) { 
                    this.errorMessage = 'Silahkan masukkan alamat email Anda terlebih dahulu.'; 
                    return; 
                }
                this.isSending = true;
                this.errorMessage = '';
                this.successMessage = '';
                
                fetch('{{ route('password.send_code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: this.email })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    this.isSending = false;
                    this.successMessage = data.message;
                    this.countdown = 60;
                    let timer = setInterval(() => {
                        this.countdown--;
                        if(this.countdown <= 0) clearInterval(timer);
                    }, 1000);
                })
                .catch(err => {
                    this.isSending = false;
                    this.errorMessage = err.message || 'Terjadi kesalahan, pastikan email Anda benar.';
                });
            }
         }">
        
        <div class="hidden md:flex w-1/2 bg-slate-900/80 text-white p-12 flex-col justify-between relative overflow-hidden border-r border-white/10">
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-blue-600 to-emerald-500 p-2.5 rounded-2xl rotate-3 shadow-lg">
                        <i class="fas fa-laptop-medical text-xl text-white"></i>
                    </div>
                    <span class="text-2xl font-black text-emerald-500 uppercase tracking-tighter italic">
                        {{ getSetting('nama_aplikasi') }}
                    </span>
                </div>
            </div>
            <div class="relative z-10 flex justify-center items-center py-10">
                <div class="relative w-64 h-64 flex items-center justify-center">
                    <div class="z-20 bg-slate-800/60 p-8 rounded-[3rem] border border-white/10 backdrop-blur-2xl shadow-2xl relative group">
                        <div class="absolute inset-0 bg-blue-500/10 blur-xl rounded-[3rem] -z-10"></div>
                        <i class="fas fa-shield-halved text-7xl text-emerald-500"></i>
                        <i class="fas fa-key absolute bottom-6 right-6 text-xl text-blue-400"></i>
                    </div>
                </div>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                    <div class="text-[10px] text-gray-500 uppercase tracking-[0.3em]">
                        &copy; 2026 Seven Komputer Cilacap
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-14 flex flex-col justify-center">
            <div class="mb-8 text-center md:text-left">
                <h3 class="text-3xl font-black text-slate-800 mb-2">Atur Ulang Sandi</h3>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">Masukkan email untuk menerima kode verifikasi.</p>
            </div>

            <div x-show="successMessage" x-transition class="bg-green-50 text-green-700 p-4 mb-4 rounded-2xl text-xs font-medium border border-green-100 flex items-center">
                <i class="fas fa-check-circle text-sm mr-2"></i>
                <span x-text="successMessage"></span>
            </div>
            <div x-show="errorMessage" x-transition class="bg-red-50 text-red-600 p-4 mb-4 rounded-2xl text-xs font-medium border border-red-100 flex items-center">
                <i class="fas fa-exclamation-circle text-sm mr-2"></i>
                <span x-text="errorMessage"></span>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 mb-4 rounded-2xl text-xs font-medium border border-red-100 space-y-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center">
                        <i class="fas fa-circle-exclamation mr-2"></i> {{ $error }}
                    </div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 ml-1">Email Address</label>
                    <div class="flex gap-2">
                        <div class="relative group flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="fas fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" x-model="email" value="{{ old('email') }}" placeholder="email@anda.com" required
                                class="w-full bg-gray-100/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-300 text-sm">
                        </div>
                        
                        <button type="button" @click="sendCode()" :disabled="isSending || countdown > 0"
                            class="bg-amber-500 hover:bg-slate-900 text-white disabled:bg-gray-200 disabled:text-gray-400 font-bold uppercase text-[10px] tracking-[0.1em] px-4 rounded-2xl transition-all duration-300 shadow-md disabled:shadow-none active:scale-[0.97] whitespace-nowrap">
                            <span x-text="isSending ? '...' : (countdown > 0 ? countdown+'s' : 'Kirim')"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 ml-1">Kode Verifikasi</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-key text-sm"></i>
                        </span>
                        <input type="text" name="token" placeholder="6 digit kode" required autocomplete="off"
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-300 text-sm">
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 ml-1">Kata Sandi Baru</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" required
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-2xl pl-11 pr-12 py-4 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-300 text-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-eye text-sm" x-show="show"></i>
                            <i class="fas fa-eye-slash text-sm" x-show="!show"></i>
                        </button>
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 ml-1">Konfirmasi Kata Sandi</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-shield-halved text-sm"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••" required
                            class="w-full bg-gray-100/50 border border-gray-200 rounded-2xl pl-11 pr-12 py-4 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-300 text-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-eye text-sm" x-show="show"></i>
                            <i class="fas fa-eye-slash text-sm" x-show="!show"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-[0.2em] hover:bg-slate-900 shadow-xl shadow-blue-200 hover:shadow-slate-200 transition-all duration-500 active:scale-[0.98]">
                        Simpan Kata Sandi <i class="fas fa-save ml-2"></i>
                    </button>
                </div>
            </form>

            <div class="text-xs text-center mt-6 md:hidden">
                <a href="/login" class="text-blue-600 font-bold uppercase tracking-wider hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>