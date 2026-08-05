@extends('layouts.layout') {{-- Sesuaikan dengan nama layout kamu --}}

@section('title', 'Semua Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h1 class="text-lg font-bold text-gray-800 mb-4">Semua Notifikasi</h1>

    <div class="divide-y divide-gray-100">
        @forelse($notifications as $notification)
            <div class="py-4 flex items-start gap-4 {{ $notification->unread() ? 'bg-blue-50/50 -mx-6 px-6' : '' }}">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="{{ $notification->data['icon'] ?? 'fa-solid fa-bell' }}"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-sm font-semibold text-gray-800">
                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                    </h2>
                    <p class="text-xs text-gray-600 mt-1">
                        {{ $notification->data['message'] ?? '' }}
                    </p>
                    <span class="text-[11px] text-gray-400 mt-2 block">
                        {{ $notification->created_at->translatedFormat('d M Y, H:i') }} ({{ $notification->created_at->diffForHumans() }})
                    </span>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-gray-400">
                <i class="fa-regular fa-bell-slash text-4xl mb-3 block"></i>
                <p>Tidak ada riwayat notifikasi.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection