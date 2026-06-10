<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifServisSelesai extends Notification
{
    use Queueable;

    protected $namaPelanggan;
    protected $perangkat;

    // Ambil data dari Controller
    public function __construct($namaPelanggan, $perangkat)
    {
        $this->namaPelanggan = $namaPelanggan;
        $this->perangkat = $perangkat;
    }

    // Aktifkan channel 'mail' saja
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    // Atur Struktur dan Isi Email
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject(' Pemberitahuan Selesai Servis - Seven Komputer')
                    ->greeting('Halo, ' . $this->namaPelanggan . '!')
                    ->line('Kami ingin mengabarkan bahwa **' . $this->perangkat . '** Anda telah selesai diperbaiki oleh teknisi kami.')
                    ->line('Sekarang perangkat Anda sudah dalam status **Siap Diambil**.')
                    ->action('Cek Status Dashboard', url('/login'))
                    ->line('Terima kasih telah memercayakan servis Anda di Seven Komputer!');
    }
}