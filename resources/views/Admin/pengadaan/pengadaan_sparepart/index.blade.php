@extends('layouts.layout')

@section('title', 'Data Pengadaan Sparepart')

@section('content')
    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data Pengadaan Sparepart</h2>
            <p class="text-gray-500 mt-1">Kelola pembelian dan riwayat stok sparepart masuk</p>
        </div>

        <a href="{{ route('admin.pengadaan_sparepart.create') }}"
            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Tambah Pengadaan Sparepart
        </a>
    </div>

    {{-- FLASH ALERT --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 font-medium border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
        <form action="{{ route('admin.pengadaan_sparepart.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4 md:items-end">
                {{-- Filter Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status_pengadaan"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
                        <option value="">Semua Status</option>
                        <option value="disetujui" {{ request('status_pengadaan') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="diterima" {{ request('status_pengadaan') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status_pengadaan') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="diajukan" {{ request('status_pengadaan') == 'diajukan' ? 'selected' : ''}}>Diajukan</option>
                    </select>
                </div>
                {{-- Tombol Aksi --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                        Cari
                    </button>
                    <a href="{{ route('admin.pengadaan_sparepart.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Badge Filter Aktif --}}
    @if(request('status_pengadaan'))
        <div class="mb-4 text-sm text-gray-600">
            Menampilkan data filter: 
            @if(request('status_pengadaan')) Status Pengadaan <span class="font-semibold text-gray-800">{{ ucfirst(request('status_pengadaan')) }}</span> @endif
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                {{-- HEADER --}}
                <thead class="bg-blue-600 text-xs uppercase text-white">
                    <tr>
                        <th class="px-5 py-4 text-center">No</th>
                        <th class="px-5 py-4 text-center">Sparepart</th>
                        <th class="px-5 py-4 text-center">Tanggal</th>
                        <th class="px-5 py-4 text-center">Jumlah</th>
                        <th class="px-5 py-4 text-center">Harga Beli</th>
                        <th class="px-5 py-4 text-center">Total</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse ($pengadaan as $index => $p)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-4 text-center text-gray-500">{{ $pengadaan->firstItem() + $index }}</td>
                            <td class="px-5 py-4 text-center text-gray-900 font-medium">{{ $p->sparepart->nama_sparepart ?? '-' }}</td>
                            <td class="px-5 py-4 text-center text-gray-500 font-medium">{{ \Carbon\Carbon::parse($p->tgl_pesan)->translatedFormat('d F Y') }}</td>
                            <td class="px-5 py-4 text-center font-medium text-gray-700">{{ $p->jumlah }} Pcs</td>
                            <td class="px-5 py-4 text-center text-blue-600 font-semibold">Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center text-green-600 font-medium">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border
                                    @if($p->status_pengadaan == 'diajukan')
                                        bg-yellow-100 text-yellow-700 border-yellow-200
                                    @elseif($p->status_pengadaan == 'disetujui')
                                        bg-blue-100 text-blue-700 border-blue-200
                                    @elseif($p->status_pengadaan == 'diterima')
                                        bg-green-100 text-green-700 border-green-200
                                    @elseif($p->status_pengadaan == 'ditolak')
                                        bg-red-100 text-red-700 border-red-200
                                    @endif">
                                    {{ ucfirst($p->status_pengadaan) }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-1.5">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('admin.pengadaan_sparepart.detail', $p->id_pengadaan) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all"
                                        title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if($p->status_pengadaan == 'diajukan')
                                    <a href="{{ route('admin.pengadaan_sparepart.edit', $p->id_pengadaan) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all" 
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif

                                    @if($p->status_pengadaan == 'diajukan')
                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.pengadaan_sparepart.delete', $p->id_pengadaan) }}"
                                        method="POST"
                                        class="form-hapus inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-colors"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif

                                    @if($p->status_pengadaan == 'disetujui')
                                    <form action="{{ route('admin.pengadaan_sparepart.terima', $p->id_pengadaan) }}"
                                          method="POST"
                                          class="form-terima">
                                        @csrf
                                        @method('PUT')
                                        <button
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white"
                                            title="Terima Barang">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400 font-medium italic">
                                Belum ada data pengadaan sparepart.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5">
        {{ $pengadaan->appends(request()->query())->links() }}
    </div>

    <script>
        const formsHapus = document.querySelectorAll('.form-hapus');
        formsHapus.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pengadaan ini akan dihapus permanen!",
                    icon: 'warning', 
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626', 
                    cancelButtonColor: '#6b7280',  
                    confirmButtonText: 'Ya, Hapus saja!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            });
        });
    
    // TERIMA BARANG
    document.querySelectorAll('.form-terima').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Barang sudah diterima?',
                text: 'Stok sparepart akan ditambahkan dan harga jual akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection