<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifBookingDiterima extends Notification
{
    use Queueable;

    protected $namaPelanggan;
    protected $kodeBooking;
    protected $merkTipe;

    public function __construct($namaPelanggan, $kodeBooking, $merkTipe)
    {
        $this->namaPelanggan = $namaPelanggan;
        $this->kodeBooking = $kodeBooking;
        $this->merkTipe = $merkTipe;
    }

    // 🔹 PERBAIKAN: Nama method harus 'via', bukan 'toRoute'
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Booking Servis Disetujui - Seven Komputer')
                    ->greeting('Halo, ' . $this->namaPelanggan . '!')
                    ->line('Kabar baik, pengajuan booking servis Anda telah disetujui oleh Admin.')
                    ->line('Berikut adalah detail booking Anda:')
                    ->line('• Kode Booking: ' . $this->kodeBooking)
                    ->line('• Perangkat: ' . $this->merkTipe)
                    ->line('Unit Anda sekarang telah masuk ke dalam antrean servis kami dengan status **Menunggu**.')
                    ->action('Lihat Detail Booking', url('/pelanggan/booking'))
                    ->line('Terima kasih telah mempercayakan servis perangkat Anda kepada Seven Komputer!');
    }
}