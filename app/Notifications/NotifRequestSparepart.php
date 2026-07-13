<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifRequestSparepart extends Notification
{
    use Queueable;

    protected $namaPelanggan;
    protected $namaLaptop;
    protected $namaSparepart;
    protected $jumlah;
    protected $harga;

    public function __construct($namaPelanggan, $namaLaptop, $namaSparepart, $jumlah, $harga)
    {
        $this->namaPelanggan = $namaPelanggan;
        $this->namaLaptop = $namaLaptop;
        $this->namaSparepart = $namaSparepart;
        $this->jumlah = $jumlah;
        $this->harga = $harga;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $hargaFormat = 'Rp ' . number_format($this->harga, 0, ',', '.');
        $totalFormat = 'Rp ' . number_format($this->harga * $this->jumlah, 0, ',', '.');

        return (new MailMessage)
                    ->subject('Persetujuan Penggantian Sparepart - Seven Komputer')
                    ->greeting('Halo, ' . $this->namaPelanggan . '!')
                    ->line('Teknisi kami telah melakukan pemeriksaan pada perangkat Anda: ' . $this->namaLaptop)
                    ->line('Ditemukan komponen yang memerlukan penggantian dengan rincian berikut:')
                    ->line('• Komponen: ' . $this->namaSparepart)
                    ->line('• Harga Satuan: ' . $hargaFormat) 
                    ->line('• Jumlah: ' . $this->jumlah . ' Pcs')
                    ->line('• Total Estimasi: ' . $totalFormat) 
                    ->line('Silakan masuk ke akun Anda untuk meninjau estimasi biaya serta memberikan persetujuan agar proses perbaikan dapat kami lanjutkan.')
                    ->action('Tinjau & Setujui Sekarang', url('/login'))
                    ->line('Jika Anda tidak menyetujui penggantian ini, perbaikan komponen tersebut akan kami batalkan. Terima kasih atas kepercayaan Anda!');
    }
}