<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusServisUpdated extends Notification
{
    use Queueable;

    protected $kodeBooking;
    protected $statusBaru;
    protected $namaPerangkat;

    // Receive parameter dari controller saat dikirim
    public function __construct($kodeBooking, $statusBaru, $namaPerangkat)
    {
        $this->kodeBooking = $kodeBooking;
        $this->statusBaru = $statusBaru;
        $this->namaPerangkat = $namaPerangkat;
    }

    // Tentukan channel pengiriman (pilih 'database')
    public function via($notifiable)
    {
        return ['database'];
    }

    // Struktur data JSON yang disimpan ke kolom 'data' di tabel notifications
    public function toDatabase($notifiable)
    {
        return [
            'title'   => 'Update Servis ' . $this->kodeBooking,
            'message' => 'Servis ' . $this->namaPerangkat . ' kamu statusnya berubah menjadi: ' . strtoupper($this->statusBaru),
            'icon'    => 'fa-solid fa-laptop-medical', // Ikon FontAwesome untuk header
            'link'    => route('pelanggan.servis.detail', $this->kodeBooking) // Opsional
        ];
    }
}