@extends('layouts.layout')

@section('title', 'Monitoring Teknisi')

@section('content')
    {{-- HEADER SECTION --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Monitoring Teknisi</h2>
            <p class="text-gray-500 mt-1">Monitor performa dan aktivitas teknisi.</p>
        </div>
    </div>

    {{-- TABLE MONITORING --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-8">
        {{-- HEADER TABLE --}}
        <div class="bg-orange-500 px-6 py-4">
            <h3 class="text-white text-md font-bold uppercase tracking-wider">
                Tabel Monitoring Utama
            </h3>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-gray-800 uppercase text-xs font-bold bg-gray-50">
                        <th class="px-6 py-4">Nama Teknisi</th>
                        <th class="px-6 py-4 text-center">Total Servis Ditangani</th>
                        <th class="px-6 py-4 text-center">Servis Selesai</th>
                        <th class="px-6 py-4 text-center">Servis Pending</th>
                        <th class="px-6 py-4 text-center">Servis Dibatalkan</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-gray-700">
                    @forelse ($monitoring as $m)
                        <tr class="border-b hover:bg-gray-50 transition">
                            {{-- NAMA TEKNISI --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $m->nama }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $m->email }}</div>
                            </td>
                            {{-- TOTAL SERVIS --}}
                            <td class="px-6 py-4 text-center">
                                <span class="font-extrabold text-blue-600 text-lg tracking-tight">
                                    {{ $m->total_servis_ditangani }}
                                </span>
                            </td>
                            {{-- SELESAI --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-green-100 text-green-700 font-bold text-xs min-w-[65px]">
                                    {{ $m->servis_selesai }}
                                </span>
                            </td>
                            {{-- PENDING --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-yellow-100 text-yellow-700 font-bold text-xs min-w-[65px]">
                                    {{ $m->servis_pending }}
                                </span>
                            </td>
                            {{-- DIBATALKAN --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-red-100 text-red-700 font-bold text-xs min-w-[65px]">
                                    {{ $m->servis_dibatalkan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500 italic">
                                Belum ada data monitoring teknisi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRAFIK KINERJA TEKNISI --}}
    <div class="mt-8">
        <h3 class="text-2xl font-bold text-gray-800 uppercase mb-4">Grafik Kinerja</h3>
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="h-80 w-full">
                <canvas id="kinerjaTeknisiChart" 
                    data-labels="{{ json_encode($nama_teknisi) }}"
                    data-selesai="{{ json_encode($data_selesai) }}"
                    data-pending="{{ json_encode($data_pending) }}">
                </canvas>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const canvas = document.getElementById('kinerjaTeknisiChart');
        
        // Membaca data kiriman dari Controller via atribut HTML
        const labelsTeknisi = JSON.parse(canvas.getAttribute('data-labels'));
        const dataSelesai = JSON.parse(canvas.getAttribute('data-selesai'));
        const dataPending = JSON.parse(canvas.getAttribute('data-pending'));

        const ctx = canvas.getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsTeknisi,
                datasets: [
                    {
                        label: 'Servis Selesai',
                        data: dataSelesai,
                        backgroundColor: 'rgba(34, 197, 94, 0.85)', // Hijau Tailwind (bg-green-500)
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: 'Servis Pending',
                        data: dataPending,
                        backgroundColor: 'rgba(234, 179, 8, 0.85)', // Kuning Tailwind (bg-yellow-500)
                        borderColor: 'rgb(234, 179, 8)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            font: { weight: 'bold' }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) { if (value % 1 === 0) { return value; } }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection