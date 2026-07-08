@extends('layouts.layout')

@section('title', 'Daftar Request Sparepart - Admin')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Request Sparepart</h2>
        <p class="text-gray-500 mt-1">Konfirmasi, teruskan ke pelanggan, dan kelola pengajuan komponen dari seluruh teknisi lapangan.</p>
    </div>

    {{-- NOTIFIKASI / FLASH MESSAGE --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 font-medium shadow-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-100 text-red-700 font-medium shadow-sm border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
        <form action="{{ route('admin.request_sparepart.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4 md:items-end">

                {{-- Input Pencarian Kode Servis --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Servis</label>
                    <input type="text" name="kode_servis" value="{{ request('kode_servis') }}" placeholder="Masukkan kode servis..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Input Pencarian Nama Pelanggan --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" value="{{ request('nama_pelanggan') }}" placeholder="Masukkan nama pelanggan..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status_request"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[250px] text-sm">
                        <option value="">Semua Status</option>
                        <option value="pending_admin" {{ request('status_request') == 'pending_admin' ? 'selected' : '' }}>Pending Admin (Baru)</option>
                        <option value="dikirim_ke_pelanggan" {{ request('status_request') == 'dikirim_ke_pelanggan' ? 'selected' : '' }}>Menunggu Konfirmasi Pelanggan</option>
                        <option value="disetujui_pelanggan" {{ request('status_request') == 'disetujui_pelanggan' ? 'selected' : '' }}>Disetujui Pelanggan (Siap ACC Akhir)</option>
                        <option value="disetujui" {{ request('status_request') == 'disetujui' ? 'selected' : '' }}>Selesai / Disetujui</option>
                        <option value="ditolak" {{ request('status_request') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                        Cari
                    </button>

                    <a href="{{ route('admin.request_sparepart.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Badge Filter Aktif --}}
    @if(request('kode_servis') || request('nama_pelanggan') || request('status_request'))
        <div class="mb-4 text-sm text-gray-600 flex flex-wrap gap-1 items-center"><span>Filter aktif:</span>
            @if(request('kode_servis'))<span class="font-semibold text-gray-800">Kode Servis: "{{ request('kode_servis') }}"</span>@endif
            @if(request('nama_pelanggan'))<span class="font-semibold text-gray-800">Pelanggan: "{{ request('nama_pelanggan') }}"</span>@endif
            @if(request('status_request'))<span class="font-semibold text-gray-800">Status: {{ ucfirst(request('status_request')) }}</span>@endif
        </div>
    @endif

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                {{-- THEAD --}}
                <thead class="bg-blue-600 text-white shadow-sm">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Kode Servis</th>
                        <th class="px-5 py-4 text-center">Nama Pelan</th>
                        <th class="px-5 py-4 text-center">Sparepart</th>
                        <th class="px-5 py-4 text-center">Jumlah</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($requestSparepart as $index => $r)
                    <tr class="hover:bg-gray-50/70 transition-all">
                        <td class="px-5 py-4 text-center text-gray-500">{{ $requestSparepart->firstItem() + $index }}</td>
                        <td class="px-5 py-4 text-center font-bold text-blue-600">{{ $r->penugasan->servis->kode_servis ?? '-' }}</td>
                        <td class="px-5 py-4 text-center text-gray-500">{{ $r->penugasan->servis->booking->pelanggan->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-center text-gray-800">{{ $r->sparepart->nama_sparepart ?? 'Komponen Terhapus' }}</td>
                        <td class="px-5 py-4 text-center text-gray-600 font-semibold">{{ $r->jumlah }} Pcs</td>
                        <td class="px-5 py-4 text-center">
                            @if($r->status_request == 'pending_admin')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">Pending Admin</span>
                            @elseif($r->status_request == 'dikirim_ke_pelanggan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">Di Pelanggan</span>
                            @elseif($r->status_request == 'disetujui_pelanggan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">ACC Pelanggan</span>
                            @elseif($r->status_request == 'disetujui')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Selesai</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- TOMBOL DETAIL (BISA DIAKSES KAPAN SAJA) --}}
                                <a href="{{ route('admin.request_sparepart.detail', $r->id_request) }}" 
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                                   title="Lihat Detail & Validasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- 1. JIKA STATUS PENDING ADMIN: TAMPILKAN TOMBOL TERUSKAN KE PELANGGAN --}}
                                @if($r->status_request == 'pending_admin')
                                    <form id="form-kirim-{{ $r->id_request }}" action="{{ route('admin.request_sparepart.kirim_pelanggan', $r->id_request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" 
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white transition-all shadow-sm" 
                                                title="Kirim ke Pelanggan untuk Persetujuan"
                                                onclick="confirmKirimPelanggan('{{ $r->id_request }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                {{-- 2. JIKA STATUS SUDAH DI-ACC PELANGGAN: TAMPILKAN TOMBOL APPROVE AKHIR (POTONG STOK) --}}
                                @if($r->status_request == 'disetujui_pelanggan')
                                    <form id="form-approve-final-{{ $r->id_request }}" action="{{ route('admin.request_sparepart.approve_final', $r->id_request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" 
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" 
                                                title="Setujui Akhir (Potong Stok)"
                                                onclick="confirmApproveFinal('{{ $r->id_request }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                {{-- 3. TOMBOL REJECT / TOLAK ADMIN (Hanya muncul jika status pending_admin atau disetujui_pelanggan) --}}
                                @if(in_array($r->status_request, ['pending_admin', 'disetujui_pelanggan']))
                                    <form id="form-reject-admin-{{ $r->id_request }}" action="{{ route('admin.request_sparepart.reject', $r->id_request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" 
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" 
                                                title="Tolak Permintaan"
                                                onclick="confirmRejectAdmin('{{ $r->id_request }}')">
                                            <svg xmlns="http://www.w3.org/2000/xl" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400 font-medium">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <span>Tidak ada riwayat pengajuan sparepart masuk.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $requestSparepart->appends(request()->query())->links() }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // 1. Konfirmasi Kirim ke Pelanggan
    function confirmKirimPelanggan(id) {
        Swal.fire({
            title: 'Kirim ke Pelanggan?',
            text: "Permintaan sparepart akan diteruskan ke pelanggan untuk mendapatkan persetujuan biaya.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ea580c', // Warna orange sesuai Tailwind bg-orange-600
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-kirim-' + id).submit();
            }
        });
    }

    // 2. Konfirmasi Setuju Akhir (Potong Stok)
    function confirmApproveFinal(id) {
        Swal.fire({
            title: 'Setujui Permintaan Akhir?',
            text: "Sistem akan menyetujui permintaan ini dan memotong stok gudang secara otomatis.",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#16a34a', // Warna hijau sesuai Tailwind bg-green-600
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Setujui & Potong Stok',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-approve-final-' + id).submit();
            }
        });
    }

    // 3. Konfirmasi Tolak Admin
    function confirmRejectAdmin(id) {
        Swal.fire({
            title: 'Tolak Permintaan?',
            text: "Apakah Anda yakin ingin menolak permintaan sparepart ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Warna merah sesuai Tailwind bg-red-600
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-reject-admin-' + id).submit();
            }
        });
    }
</script>
@endsection