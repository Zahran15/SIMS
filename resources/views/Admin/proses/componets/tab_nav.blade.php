<div class="flex items-center gap-2 border-b border-gray-200 pb-4 mb-6 overflow-x-auto">
    <a href="/admin/booking" 
       class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 {{ request()->is('admin/booking*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        <i class="fas fa-bookmark"></i>
        <span>Booking</span>
    </a>

    <a href="/admin/penugasan" 
       class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 {{ request()->is('admin/penugasan*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        <i class="fas fa-user-tag"></i>
        <span>Penugasan</span>
    </a>

    <a href="/admin/servis_proses" 
       class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 {{ request()->is('admin/servis_proses*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        <i class="fas fa-tools"></i>
        <span>Proses</span>
    </a>

    <a href="/admin/servis_selesai" 
       class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 {{ request()->is('admin/servis_selesai*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        <i class="fas fa-check-circle"></i>
        <span>Selesai</span>
    </a>
</div>