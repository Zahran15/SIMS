<aside class="w-64 bg-blue-600 text-white min-h-screen flex flex-col shadow-lg">
    <div class="p-4 flex items-center space-x-2 bg-white mb-6">
        <img src="{{ asset('images/8pp-crop.jpg') }}" class="w-10">
        <span class="text-blue-600 font-bold text-lg leading-tight">SEVEN<br><span class="text-orange-500">KOMPUTER</span></span>
    </div>

    <nav class="flex-1 px-2 space-y-1">
        <a href="/{{ Session::get('role') }}/dashboard" 
           class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->is('*/dashboard') ? 'bg-blue-800 shadow-inner' : '' }}">
            <i class="fas fa-th-large w-6"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        @if(in_array(Session::get('role'), ['admin', 'owner']))
        <div x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition focus:outline-none">
                <i class="fas fa-database w-6"></i>
                <span class="ml-3 flex-1 text-left">Master Data</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" class="pl-12 pr-4 py-2 space-y-1 text-sm bg-blue-800/30">
                <a href="/admin/pelanggan" class="block py-2 hover:text-orange-300">Data Pelanggan</a>
                <a href="/admin/perangkat" class="block py-2 hover:text-orange-300">Data Perangkat</a>
            </div>
        </div>

        <div x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition focus:outline-none">
                <i class="fas fa-file-invoice w-6"></i>
                <span class="ml-3 flex-1 text-left">Pengadaan</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" class="pl-12 pr-4 py-2 bg-blue-800/30">
                <a href="/admin/stok" class="block py-2 text-sm hover:text-orange-300">Stok Barang</a>
            </div>
        </div>
        @endif

        @if(in_array(Session::get('role'), ['admin', 'owner', 'teknisi']))
        <div x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition focus:outline-none">
                <i class="fas fa-tools w-6"></i>
                <span class="ml-3 flex-1 text-left">Proses</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" class="pl-12 pr-4 py-2 bg-blue-800/30">
                <a href="/{{ Session::get('role') }}/service-masuk" class="block py-2 text-sm hover:text-orange-300">Service Masuk</a>
            </div>
        </div>
        @endif

        @if(Session::get('role') == 'admin')
        <a href="/admin/setting" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-cog w-6"></i>
            <span class="ml-3">Pengaturan</span>
        </a>
        @endif
    </nav>

    <div class="p-4 border-t border-blue-500">
        <a href="/logout" class="flex items-center px-4 py-2 text-sm text-blue-100 hover:text-white transition">
            <i class="fas fa-sign-out-alt w-6"></i>
            <span>Keluar</span>
        </a>
    </div>
</aside>