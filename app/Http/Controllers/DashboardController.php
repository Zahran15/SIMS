<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Servis;
use App\Models\PenugasanTeknisi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        if (Auth::user()->role !== 'admin') {abort(403, 'Anda tidak memiliki akses ke halaman ini.');}
        $totalBookingBaru = Booking::where('status_booking', 'pending')->count();
        $totalVerifikasiDeposit = Booking::where('status_deposit', 'belum lunas')->count();
        $totalDalamProses = Servis::where('status_servis', 'proses')->count();
        $totalSiapDiambil = Servis::whereIn('status_servis', ['selesai', 'bisa diambil', 'sudah diambil'])->count();
        $bookings = Booking::latest()->take(5)->get();
        return view('admin.dashboard.index', compact('totalBookingBaru', 'totalVerifikasiDeposit', 'totalDalamProses', 'totalSiapDiambil', 'bookings'));
    }

    public function owner()
    {
        if (Auth::user()->role !== 'owner') {abort(403, 'Anda tidak memiliki akses ke halaman ini.');}
        $totalPendapatan = Pembayaran::where('status_pembayaran', 'sukses')->sum('nominal');
        $depositMasuk = Pembayaran::where('jenis_pembayaran', 'deposit')->where('status_pembayaran', 'sukses')->sum('nominal');
        $totalPelunasan = Pembayaran::where('jenis_pembayaran', 'pelunasan')->where('status_pembayaran', 'sukses')->sum('nominal');
        $pembayaranPending = Pembayaran::where('status_pembayaran', 'pending')->count();
        $pembayaran_terbaru = Pembayaran::with(['booking.pelanggan', 'servis'])->where('status_pembayaran', 'sukses')->latest('tanggal_bayar')->take(5)->get();
        $tahun_ini = date('Y');
        $pendapatan_per_bulan = Pembayaran::where('status_pembayaran', 'sukses')->whereYear('tanggal_bayar', $tahun_ini)->select(DB::raw('MONTH(tanggal_bayar) as bulan'),DB::raw('SUM(nominal) as total'))->groupBy('bulan')->pluck('total', 'bulan')->toArray();
        $data_chart_owner = [];for ($i = 1; $i <= 12; $i++) {
        $data_chart_owner[] = isset($pendapatan_per_bulan[$i]) ? (float)$pendapatan_per_bulan[$i] : 0;}
        return view('owner.dashboard.index', compact('totalPendapatan', 'depositMasuk', 'totalPelunasan', 'pembayaranPending','pembayaran_terbaru','data_chart_owner'));
    }  

    public function teknisi()
    {
        $id_teknisi = Auth::id();
        $sedang_dikerjakan = PenugasanTeknisi::where('id_user', $id_teknisi)->where('status_penugasan', 'sedang dikerjakan')->count();
        $menunggu_sparepart = PenugasanTeknisi::where('id_user', $id_teknisi)->where('status_penugasan', 'menunggu sparepart')->count();
        $selesai_bulan_ini = PenugasanTeknisi::where('id_user', $id_teknisi)->where('status_penugasan', 'selesai')->whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year)->count();
        $total_servis = PenugasanTeknisi::where('id_user', $id_teknisi)->count();
        $tugas_prioritas = PenugasanTeknisi::with(['servis.booking.pelanggan'])->where('id_user', $id_teknisi)->whereIn('status_penugasan', ['sedang dikerjakan', 'menunggu sparepart'])->latest()->get();
        return view('teknisi.dashboard.index', compact('sedang_dikerjakan', 'menunggu_sparepart', 'selesai_bulan_ini', 'total_servis', 'tugas_prioritas'));
    }

    public function pelanggan()
    {
        $id_pelanggan = Auth::guard('pelanggan')->id();
        $servis_aktif = Servis::with(['booking'])->whereHas('booking', function ($query) use ($id_pelanggan) {
        $query->where('id_pelanggan', $id_pelanggan);})->where('status_servis', '!=', 'selesai')->latest()->get();
        return view('pelanggan.dashboard.index', compact('servis_aktif'));
    }
}