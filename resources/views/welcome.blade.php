<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seven Komputer - Laptop Service Center Cilacap</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 scroll-smooth">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-green-600 p-2 rounded-lg text-white">
                        <i class="fas fa-laptop-medical text-xl"></i>
                    </div>
                    <span class="text-xl font-black text-amber-500 uppercase tracking-tighter">Seven<span class="text-green-600">Komputer</span></span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-xs font-bold uppercase tracking-widest text-gray-600 hover:text-blue-600 transition">Layanan</a>
                    <a href="#location" class="text-xs font-bold uppercase tracking-widest text-gray-600 hover:text-blue-600 transition">Lokasi</a>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest text-gray-700 hover:text-blue-600 transition">Log In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-200">Register</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-[1.1] mb-6">
                Solusi Perbaikan <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Laptop Terpercaya.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-gray-500 text-lg mb-10">
                Jangan biarkan pekerjaan terhambat. Kami hadir dengan sistem manajemen servis modern. Pantau progres perbaikan secara transparan dan real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                <a href="{{ route('login') }}" class="bg-gray-900 text-white px-8 py-4 rounded-2xl font-bold uppercase text-sm tracking-widest hover:bg-blue-600 transition-all duration-300 shadow-xl">
                    Cek Status Servis
                </a>
            </div>

            <!-- Client Logos / Brands -->
            <div class="pt-10 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-8 text-center">Melayani Berbagai Merk</p>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-40 grayscale">
                    <img src="https://unpkg.com/simple-icons@v11/icons/asus.svg" alt="ASUS" class="h-11 w-auto" />
                    <img src="https://unpkg.com/simple-icons@v11/icons/acer.svg" alt="ACER" class="h-10 w-auto" />
                    <img src="https://unpkg.com/simple-icons@v11/icons/lenovo.svg" alt="LENOVO" class="h-10 w-auto" />
                    <img src="https://unpkg.com/simple-icons@v11/icons/hp.svg" alt="HP" class="h-12 w-auto" />
                    <img src="https://unpkg.com/simple-icons@v11/icons/dell.svg" alt="DELL" class="h-12 w-auto" />
                    <img src="https://unpkg.com/simple-icons@v11/icons/apple.svg" alt="MACBOOK" class="h-12 w-auto" />
                </div>
            </div>
        </div>

        <!-- Background Blobs -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-0 opacity-20">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-amber-300 rounded-full blur-[150px]"></div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-4xl font-black mb-4">Kenapa Seven Komputer?</h2>
                <div class="w-20 h-1.5 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="group p-8 rounded-3xl hover:bg-gray-50 transition-all duration-500 border border-transparent hover:border-gray-100">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-microchip text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-3 uppercase tracking-tight">Teknisi Ahli</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Spesialis mainboard, penggantian layar, hingga optimasi software dengan peralatan standar industri.</p>
                </div>

                <div class="group p-8 rounded-3xl hover:bg-gray-50 transition-all duration-500 border border-transparent hover:border-gray-100">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-history text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-3 uppercase tracking-tight">Update Real-time</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Dapatkan notifikasi progres pengerjaan laptop Anda langsung melalui dashboard pelanggan kami.</p>
                </div>

                <div class="group p-8 rounded-3xl hover:bg-gray-50 transition-all duration-500 border border-transparent hover:border-gray-100">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-3 uppercase tracking-tight">Pengerjaan Terukur</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Proses diagnosa dan perbaikan dilakukan secara efisien oleh teknisi berpengalaman tanpa mengurangi ketelitian pada setiap komponen.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="location" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-12 items-center">
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-4xl font-black mb-6">Mampir Ke Workshop Kami</h2>
                    <p class="text-gray-500 mb-8 text-lg leading-relaxed">Kami berlokasi di pusat kota Cilacap.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start justify-center md:justify-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <p class="font-bold text-gray-700">Jl. Pudang No.53, Pasiran, Tegalreja, Kec. Cilacap Sel., Kabupaten Cilacap, Jawa Tengah 53214</p>
                        </div>
                        <div class="flex items-start justify-center md:justify-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-700">Senin - Sabtu: 08.00 - 17.00</p>
                                <p class="text-sm text-gray-500">Minggu & Hari Libur Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 h-80 rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white">
                    <!-- Ganti SRC dengan Link Embed Google Maps Anda -->
                    <iframe class="w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-700" 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.6962584284856!2d109.00384977653361!3d-7.715703692302226!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6513847e670feb%3A0x305bca2052c2a455!2sServis%20Laptop%20Cilacap!5e0!3m2!1sid!2sid!4v1778028648823!5m2!1sid!2sid" width="1920" height="1080" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-12 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-center md:text-left">
                <span class="text-xl font-black uppercase tracking-tighter italic text-amber-500">Seven<span class="text-green-600 opacity-50">Komputer</span></span>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-2">Cilacap Laptop Service Center</p>
            </div>
            
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest text-center">
                &copy; {{ date('Y') }} Seven Komputer Cilacap. All Rights Reserved.
            </p>

        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6285879000070" target="_blank" class="fixed bottom-8 right-8 bg-[#25D366] text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-all z-50 animate-bounce">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>

</body>
</html>