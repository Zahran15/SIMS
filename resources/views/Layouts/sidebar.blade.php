<script src="//unpkg.com/alpinejs" defer></script>

@php
    // Cek apakah yang login adalah user (admin/owner/teknisi) atau pelanggan
    if (Auth::guard('web')->check()) {
        $role = Auth::guard('web')->user()->role;
    } elseif (Auth::guard('pelanggan')->check()) {
        $role = 'pelanggan';
    } else {
        $role = null;
    }
@endphp

<div x-data="{ open: true }" class="flex">

    <aside class="bg-blue-600 h-screen flex flex-col shadow-2xl z-50 border-r border-white/5 transition-all duration-300 fixed md:relative"
           :class="open ? 'w-[280px]' : 'w-[80px]'">

        <div class="h-[73px] bg-white flex items-center justify-between px-4 border-b overflow-hidden">
            <div x-show="open" x-transition class="flex-1 flex justify-center pl-6">
                <img src="{{ getSettingAsset('logo_aplikasi') }}" class="h-12 w-12 object-contain">
            </div>
            
            <button @click="open = !open" class="text-gray-500 hover:text-blue-600 p-2 rounded-xl transition-colors mx-auto">
                <i class="fas text-lg text-blue-600" :class="open ? 'fa-bars' : 'fa-bars'"></i>
            </button>
        </div>
     
        <div class="flex-1 overflow-y-auto px-3 py-6 custom-scroll" x-data="{ activeMenu: '{{ Request::segment(2) }}' }">
            
            <a href="/{{ $role }}/dashboard"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group mb-1 justify-center"
               :class="[
                    activeMenu === 'dashboard' ? 'bg-white text-blue-600 shadow-lg shadow-blue-600/20' : 'text-white hover:bg-white hover:text-blue-600',
                    open ? 'justify-start' : 'justify-center'
               ]">
                <i class="fas fa-gauge-high text-lg transition-colors duration-300" 
                   :class="activeMenu === 'dashboard' ? 'text-blue-600' : 'text-white group-hover:text-blue-600'">
                </i>
                <span x-show="open" x-transition:enter="transition ease-out duration-200" class="text-sm font-bold tracking-wide whitespace-nowrap">DASHBOARD UTAMA</span>
            </a>

            @if($role == 'admin')
            <div x-show="open" class="text-[10px] uppercase text-white mt-8 mb-3 px-4 font-black tracking-[0.2em] whitespace-nowrap">Administrator</div>

            <div class="space-y-1 mb-2">
                <button @click="open ? (activeMenu = activeMenu === 'master' ? null : 'master') : open = true"
                    class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                    :class="open ? 'justify-between' : 'justify-center'">
                    <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                        <span class="w-6 flex justify-center"><i class="fas fa-database text-blue-1000 text-lg"></i></span> 
                        <span x-show="open" class="whitespace-nowrap">MASTER DATA</span>
                    </span>
                    <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'master' }"></i>
                </button>
                
                <div x-show="activeMenu === 'master' && open" x-transition class="pl-11 space-y-2 pb-2">
                    <a href="/admin/pelanggan" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Data Pelanggan</a>
                    <a href="/admin/sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Data Sparepart</a>
                    <a href="/admin/jasa_servis" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Harga Jasa</a>
                </div>
            </div>
            
            <div class="space-y-1 mb-2">
                <button @click="open ? (activeMenu = activeMenu === 'pengadaan' ? null : 'pengadaan') : open = true"
                    class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                    :class="open ? 'justify-between' : 'justify-center'">
                    <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                        <span class="w-6 flex justify-center"><i class="fas fa-truck-loading text-blue-1000 text-lg"></i></span>
                        <span x-show="open" class="whitespace-nowrap">PENGADAAN</span>
                    </span>
                    <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'pengadaan' }"></i>
                </button>
                <div x-show="activeMenu === 'pengadaan' && open" x-transition class="pl-11 space-y-2 pb-2">
                    <a href="/admin/pengadaan_sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pengadaan Sparepart</a>
                    <a href="/admin/pengadaan_tools" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pengadaan Toolskit</a>
                    <a href="/admin/request_sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Request Sparepart</a>
                </div>
            </div>

            <div class="space-y-1 mb-2">
                <button @click="open ? (activeMenu = activeMenu === 'proses' ? null : 'proses') : open = true"
                    class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                    :class="open ? 'justify-between' : 'justify-center'">
                    <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                        <span class="w-6 flex justify-center"><i class="fas fa-layer-group text-blue-1000 text-lg"></i></span> 
                        <span x-show="open" class="whitespace-nowrap">OPERASIONAL</span>
                    </span>
                    <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'proses' }"></i>
                </button>
                <div x-show="activeMenu === 'proses' && open" x-transition class="pl-11 space-y-2 pb-2">                
                    <a href="/admin/booking" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Booking</a>
                    <a href="/admin/penugasan" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Penugasan</a>
                    <a href="/admin/servis_proses" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Proses</a>
                    <a href="/admin/servis_selesai" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Selesai</a>
                    <a href="/admin/pembayaran" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pembayaran</a>
                    <a href="/admin/histori" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Histori</a>
                </div>
            </div>

            <div class="space-y-1 mb-2">
                <button @click="open ? (activeMenu = activeMenu === 'pengaturan' ? null : 'pengaturan') : open = true"
                    class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                    :class="open ? 'justify-between' : 'justify-center'">
                    <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                        <span class="w-6 flex justify-center"><i class="fas fa-cogs text-blue-1000 text-lg"></i></span> 
                        <span x-show="open" class="whitespace-nowrap">PENGATURAN</span>
                    </span>
                    <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'pengaturan' }"></i>
                </button>
                <div x-show="activeMenu === 'pengaturan' && open" x-transition class="pl-11 space-y-2 pb-2">
                    <a href="/admin/backup" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Backup Data</a>
                    <a href="/admin/website" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pengaturan Website</a>
                </div>
            </div>
            @endif

            @if($role == 'owner')
            <div x-show="open" class="text-[10px] uppercase text-white mt-8 mb-3 px-4 font-black tracking-[0.2em] whitespace-nowrap">Executive Owner</div>

                <div class="space-y-1 mb-2">
                    <button @click="open ? (activeMenu = activeMenu === 'master' ? null : 'master') : open = true"
                        class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                        :class="open ? 'justify-between' : 'justify-center'">
                        <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                            <span class="w-6 flex justify-center"><i class="fas fa-database text-blue-1000 text-lg"></i></span> 
                            <span x-show="open" class="whitespace-nowrap">MASTER DATA</span>
                        </span>
                        <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'master' }"></i>
                    </button>
                    <div x-show="activeMenu === 'master' && open" x-transition class="pl-11 space-y-2 pb-2">
                        <a href="/owner/users" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Data Users</a>
                        <a href="/owner/sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Data Sparepart</a>
                    </div>
                </div>

                <div class="space-y-1 mb-2">
                    <button @click="open ? (activeMenu = activeMenu === 'monitoring' ? null : 'monitoring') : open = true"
                        class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                        :class="open ? 'justify-between' : 'justify-center'">
                        <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                            <span class="w-6 flex justify-center"><i class="fas fa-eye text-blue-1000 text-lg"></i></span> 
                            <span x-show="open" class="whitespace-nowrap">MONITORING</span>
                        </span>
                        <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'monitoring' }"></i>
                    </button>
                    <div x-show="activeMenu === 'monitoring' && open" x-transition class="pl-11 space-y-2 pb-2">
                        <a href="/owner/monitoring_teknisi" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Monitoring Teknisi</a>
                    </div>
                </div>

                <div class="space-y-1 mb-2">
                    <button @click="open ? (activeMenu = activeMenu === 'pengadaan' ? null : 'pengadaan') : open = true"
                        class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                        :class="open ? 'justify-between' : 'justify-center'">
                        <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                            <span class="w-6 flex justify-center"><i class="fas fa-truck-loading text-blue-1000 text-lg"></i></span>
                            <span x-show="open" class="whitespace-nowrap">PENGADAAN</span>
                        </span>
                        <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'pengadaan' }"></i>
                    </button>
                    <div x-show="activeMenu === 'pengadaan' && open" x-transition class="pl-11 space-y-2 pb-2">
                        <a href="/owner/pengadaan_sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pengadaan Sparepart</a>
                        <a href="/owner/pengadaan_tools" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pengadaan Toolskit</a>
                        <a href="/owner/request_sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Request Sparepart</a>
                    </div>
                </div>

                <div class="space-y-1 mb-2">
                    <button @click="open ? (activeMenu = activeMenu === 'laporan' ? null : 'laporan') : open = true"
                        class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                        :class="open ? 'justify-between' : 'justify-center'">
                        <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                            <span class="w-6 flex justify-center"><i class="fas fa-file-contract text-blue-1000 text-lg"></i></span> 
                            <span x-show="open" class="whitespace-nowrap">LAPORAN</span>
                        </span>
                        <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'laporan' }"></i>
                    </button>
                    <div x-show="activeMenu === 'laporan' && open" x-transition class="pl-11 space-y-2 pb-2">
                        <a href="/owner/laporan_keuangan" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Laporan Keuangan</a>
                        <a href="/owner/laporan_servis" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Laporan Servis</a>
                    </div>
                </div>
            @endif

            @if($role == 'teknisi')
                <div x-show="open" class="text-[10px] uppercase text-white mt-8 mb-3 px-4 font-black tracking-[0.2em] whitespace-nowrap">Technician Control</div>

                <div class="space-y-1 mb-2">
                    <button @click="open ? (activeMenu = activeMenu === 'proses' ? null : 'proses') : open = true"
                        class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                        :class="open ? 'justify-between' : 'justify-center'">
                        <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                            <span class="w-6 flex justify-center"><i class="fas fa-tools text-blue-1000 text-lg"></i></span> 
                            <span x-show="open" class="whitespace-nowrap">OPERASIONAL</span>
                        </span>
                        <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'proses' }"></i>
                    </button>
                    <div x-show="activeMenu === 'proses' && open" x-transition class="pl-11 space-y-2 pb-2">
                        <a href="/teknisi/servis_kerja" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Daftar Servis</a>
                        <a href="/teknisi/request_sparepart" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Request Sparepart</a>
                        <a href="/teknisi/riwayat_pekerjaan" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Riwayat Pekerjaan</a>
                    </div>
                </div>
            @endif

            @if($role == 'pelanggan')
                <div x-show="open" class="text-[10px] uppercase text-white mt-8 mb-3 px-4 font-black tracking-[0.2em] whitespace-nowrap">Customer Portal</div>

                <div class="space-y-1 mb-2">
                    <button @click="open ? (activeMenu = activeMenu === 'layanan' ? null : 'layanan') : open = true"
                        class="flex items-center w-full px-4 py-3 rounded-xl transition-all text-white hover:bg-white hover:text-blue-600"
                        :class="open ? 'justify-between' : 'justify-center'">
                        <span class="flex items-center gap-3 text-sm font-bold tracking-wide">
                            <span class="w-6 flex justify-center"><i class="fas fa-user-gear text-blue-1000 text-lg"></i></span> 
                            <span x-show="open" class="whitespace-nowrap">LAYANAN</span>
                        </span>
                        <i x-show="open" class="fas fa-chevron-right text-[10px] transition-transform duration-300" :class="{ 'rotate-90 text-blue-1000': activeMenu === 'layanan' }"></i>
                    </button>
                    <div x-show="activeMenu === 'layanan' && open" x-transition class="pl-11 space-y-2 pb-2">
                        <a href="/pelanggan/booking" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Booking Servis</a>
                        <a href="/pelanggan/status_servis" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Cek Status</a>
                        <a href="/pelanggan/pembayaran" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Pembayaran</a>
                        <a href="/pelanggan/riwayat_servis" class="block text-xs text-white hover:text-emerald-500 transition font-bold uppercase tracking-widest whitespace-nowrap">Riwayat Servis</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="p-2 mt-auto">
            <div class="bg-white rounded-2xl p-3 mb-4 border border-white/5 transition-all">
                <div class="flex items-center gap-3 mb-3" :class="open ? 'justify-start' : 'justify-center'">
                    <div class="w-10 h-10 min-w-[40px] rounded-full bg-gradient-to-tr from-blue-600 to-cyan-400 flex items-center justify-center text-white font-bold shadow-lg">
                        {{ strtoupper(substr($role ?? 'U', 0, 1)) }}
                    </div>
                    <div x-show="open" x-transition class="flex flex-col overflow-hidden">
                        <span class="text-xs font-black text-blue-600 truncate uppercase">{{ $role }} Account</span>
                    </div>
                </div>
                <a href="/logout"
                   class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300"
                   :title="!open ? 'Keluar Sistem' : ''">
                    <i class="fas fa-power-off text-base"></i> 
                    <span x-show="open" class="whitespace-nowrap">Keluar Sistem</span>
                </a>
            </div>
        </div>
    </aside>
</div>