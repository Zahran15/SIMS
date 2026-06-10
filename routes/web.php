<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\JasaServisController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\PengadaanSparepartController;
use App\Http\Controllers\RequestSparepartController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PenugasanTeknisiController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\ServisKerjaController;
use App\Http\Controllers\ServisStatusController;
use App\Http\Controllers\HistoriAktivitasController;
use App\Http\Controllers\RiwayatPekerjaanController;
use App\Http\Controllers\RiwayatServisController;
use App\Http\Controllers\MonitoringTeknisiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\LaporanServisController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\PembayaranController;

// ================= AUTH =================
Route::get('/', [AuthController::class, 'showWelcome']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'registerPelanggan']);
Route::get('/logout', [AuthController::class, 'logout']);

// GROUP UTAMA ADMIN
Route::middleware(['auth:web', 'cek_role:admin'])->prefix('admin')->group(function () {
    // DASHBOARD ADMIN
    Route::get('/dashboard', [DashboardController::class, 'admin']);
    // DATA PELANGGAN
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan.index');
    Route::get('/pelanggan/create',[PelangganController::class, 'create'])->name('admin.pelanggan.create');
    Route::post('/pelanggan/store', [PelangganController::class, 'store'])->name('admin.pelanggan.store');
    Route::get('/pelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('admin.pelanggan.edit');
    Route::put('/pelanggan/update/{id}', [PelangganController::class, 'update'])->name('admin.pelanggan.update');
    Route::delete('/pelanggan/delete/{id}', [PelangganController::class, 'destroy'])->name('admin.pelanggan.delete');
    Route::get('/pelanggan/detail/{id}', [PelangganController::class, 'show'])->name('admin.pelanggan.detail');
    // DATA SPAREPART 
    Route::get('/sparepart', [SparepartController::class, 'index'])->name('admin.sparepart.index');
    Route::get('/sparepart/tambah', [SparepartController::class, 'create'])->name('admin.sparepart.create');    
    Route::post('/sparepart/store', [SparepartController::class, 'store'])->name('admin.sparepart.store');
    Route::get('/sparepart/edit/{id}', [SparepartController::class, 'edit'])->name('admin.sparepart.edit');
    Route::put('/sparepart/update/{id}', [SparepartController::class, 'update'])->name('admin.sparepart.update');
    Route::delete('/sparepart/delete/{id}', [SparepartController::class, 'destroy'])->name('admin.sparepart.delete');
    // DATA JASA SERVIS
    Route::get('/jasa_servis', [JasaServisController::class, 'index'])->name('admin.jasa_servis.index');
    Route::get('/jasa_servis/create', [JasaServisController::class, 'create'])->name('admin.jasa_servis.create');
    Route::post('/jasa_servis/store', [JasaServisController::class, 'store'])->name('admin.jasa_servis.store');
    Route::get('/jasa_servis/edit/{id}', [JasaServisController::class, 'edit'])->name('admin.jasa_servis.edit');
    Route::put('/jasa_servis/update/{id}', [JasaServisController::class, 'update'])->name('admin.jasa_servis.update');
    Route::delete('/jasa_servis/delete/{id}', [JasaServisController::class, 'destroy'])->name('admin.jasa_servis.delete');
    // DATA PENGADAAN SPAREPART ADMIN
    Route::get('/pengadaan_sparepart', [PengadaanSparepartController::class, 'index'])->name('admin.pengadaan_sparepart.index');
    Route::get('/pengadaan_sparepart/create', [PengadaanSparepartController::class, 'create'])->name('admin.pengadaan_sparepart.create');
    Route::post('/pengadaan_sparepart/store', [PengadaanSparepartController::class, 'store'])->name('admin.pengadaan_sparepart.store');
    Route::get('/pengadaan_sparepart/edit/{id}', [PengadaanSparepartController::class, 'edit'])->name('admin.pengadaan_sparepart.edit');
    Route::put('/pengadaan_sparepart/update/{id}', [PengadaanSparepartController::class, 'update'])->name('admin.pengadaan_sparepart.update');
    Route::get('/pengadaan_sparepart/detail/{id}', [PengadaanSparepartController::class, 'detail'])->name('admin.pengadaan_sparepart.detail');
    Route::delete('/pengadaan_sparepart/delete/{id}', [PengadaanSparepartController::class, 'destroy'])->name('admin.pengadaan_sparepart.delete');
    // DATA PENGADAAN TOOLS ADMIN
    Route::get('/pengadaan_tools', [ToolsController::class, 'index'])->name('admin.pengadaan_tools.index');
    Route::get('/pengadaan_tools/create', [ToolsController::class, 'create'])->name('admin.pengadaan_tools.create');
    Route::post('/pengadaan_tools/store', [ToolsController::class, 'store'])->name('admin.pengadaan_tools.store');
    Route::get('/pengadaan_tools/edit/{id}', [ToolsController::class, 'edit'])->name('admin.pengadaan_tools.edit');
    Route::put('/pengadaan_tools/update/{id}', [ToolsController::class, 'update'])->name('admin.pengadaan_tools.update');
    Route::delete('/pengadaan_tools/delete/{id}', [ToolsController::class, 'destroy'])->name('admin.pengadaan_tools.delete');
    // DATA REQUEST SPAREPART ADMIN
    Route::get('/request_sparepart', [RequestSparepartController::class, 'index'])->name('admin.request_sparepart.index');
    Route::get('/request_sparepart/detail/{id}', [RequestSparepartController::class, 'detail'])->name('admin.request_sparepart.detail');
    Route::post('/request_sparepart/{id}/approve', [RequestSparepartController::class, 'approve'])->name('request.approve');
    Route::post(' /request_sparepart/{id}/reject', [RequestSparepartController::class, 'reject'])->name('request.reject');
    // DATA BOOKING
    Route::get('/booking', [BookingController::class, 'index'])->name('admin.booking.index');
    Route::get('/booking/tambah', [BookingController::class, 'create'])->name('admin.booking.create'); 
    Route::post('/booking/store', [BookingController::class, 'store'])->name('admin.booking.store');
    Route::get('/booking/edit/{id}', [BookingController::class, 'edit'])->name('admin.booking.edit');
    Route::put('/booking/update/{id}', [BookingController::class, 'update'])->name('admin.booking.update');
    Route::get('/booking/detail/{id}', [BookingController::class, 'show'])->name('admin.booking.show');
    Route::delete('/booking/delete/{id}', [BookingController::class, 'destroy'])->name('admin.booking.destroy');
    // AUTO BUAT SERVIS DARI BOOKING
    Route::post('/servis/create_from_booking/{id_booking}', [ServisController::class, 'createFromBooking'])->name('admin.servis.create');
    // DATA PENUGASAN TEKNISI
    Route::get('/penugasan', [PenugasanTeknisiController::class, 'index'])->name('admin.penugasan.index');
    Route::get('/penugasan/tambah/{id}', [PenugasanTeknisiController::class, 'create'])->name('admin.penugasan.create');
    Route::post('/penugasan/store', [PenugasanTeknisiController::class, 'store'])->name('admin.penugasan.store');
    Route::get('/penugasan/edit/{id}', [PenugasanTeknisiController::class, 'edit'])->name('admin.penugasan.edit');
    Route::put('/penugasan/update/{id}', [PenugasanTeknisiController::class, 'update'])->name('admin.penugasan.update');
    Route::delete('/penugasan/delete/{id}', [PenugasanTeknisiController::class, 'destroy'])->name('admin.penugasan.delete');
    Route::get(' /penugasan/detail/{id}', [PenugasanTeknisiController::class, 'show'])->name('admin.penugasan.detail');
    // SERVIS PROSES
    Route::get('/servis_proses', [ServisController::class, 'prosesindex'])->name('admin.servis_proses.index');
    Route::get('/servis_proses/detail/{id}', [ServisController::class, 'detailProses'])->name('admin.servis_proses.detail');
    Route::get('/servis_proses/edit/{id}', [ServisController::class, 'edit'])->name('admin.servis_proses.edit');
    Route::put('/servis_proses/update/{id}', [ServisController::class, 'update'])->name('admin.servis_proses.update');
    Route::post('/servis/{id}/ajax-jasa',[ServisController::class, 'ajaxTambahJasa'])->name('admin.servis.ajaxJasa');
    Route::post('/servis/{id}/ajax-sparepart',[ServisController::class, 'ajaxTambahSparepart'])->name('admin.servis.ajaxSparepart');
    Route::get('/servis_proses/nota/{id}', [ServisController::class, 'nota'])->name('admin.servis_proses.nota');
    // SERVIS SELESAI
    Route::get('/servis_selesai', [ServisController::class, 'selesaiindex'])->name('admin.servis_selesai.index');
    Route::get('/servis_selesai/detail/{id}', [ServisController::class, 'detailSelesai'])->name('admin.servis_selesai.detail');
    Route::get('/servis_selesai/edit/{id}', [ServisController::class, 'editSelesai'])->name('admin.servis_selesai.edit');
    Route::put('/servis_selesai/update/{id}', [ServisController::class, 'updateSelesai'])->name('admin.servis_selesai.update');
    Route::get('/servis_selesai/tanda_terima/{id}', [ServisController::class, 'tandaTerima'])->name('admin.servis_selesai.tanda_terima');
    // PEMBAYARAN
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'indexAdmin'])->name('admin.pembayaran.index');
    Route::get('/pembayaran/detail/{id}', [App\Http\Controllers\PembayaranController::class, 'detailAdmin'])->name('admin.pembayaran.detail');
    Route::get('/pembayaran/edit/{id}', [App\Http\Controllers\PembayaranController::class, 'editAdmin'])->name('admin.pembayaran.edit');
    Route::put('/pembayaran/update/{id}', [App\Http\Controllers\PembayaranController::class, 'updateAdmin'])->name('admin.pembayaran.update');
    // HISTORI AKTIVITAS
    Route::get('/histori', [HistoriAktivitasController::class, 'index'])->name('admin.histori.index');
    Route::get('/histori/detail/{id}', [HistoriAktivitasController::class, 'detail'])->name('admin.histori.detail');
    // PENGATURAN
    Route::get('/backup', [PengaturanController::class, 'backupIndex'])->name('admin.backup.index');
    Route::get('/website', [PengaturanController::class, 'websiteIndex'])->name('admin.website.index');
    Route::post('/website/update', [PengaturanController::class, 'updateWebsite'])->name('admin.website.update');
    Route::get('/backup/download', [PengaturanController::class, 'downloadBackup'])->name('admin.backup.download');
    Route::get('/backup/code', [PengaturanController::class, 'downloadCode'])->name('admin.backup.code');
});

// GROUP UTAMA OWNER
Route::middleware(['auth:web', 'cek_role:owner'])->prefix('owner')->group(function () {
    // DASHBOARD OWNER
    Route::get('/dashboard', [DashboardController::class, 'owner']);
    // DATA USERS 
    Route::get('/users', [UsersController::class, 'index'])->name('owner.users.index');
    Route::post('/users/store', [UsersController::class, 'store'])->name('owner.users.store');
    Route::get('/users/create', [UsersController::class, 'create'])->name('owner.users.create');
    Route::get('/users/edit/{id}', [UsersController::class, 'edit'])->name('owner.users.edit');
    Route::put('/users/update/{id}', [UsersController::class, 'update'])->name('owner.users.update');
    Route::delete('/users/delete/{id}', [UsersController::class, 'destroy'])->name('owner.users.delete');
    Route::get('/users/detail/{id}', [UsersController::class, 'show'])->name('owner.users.show');
    // DATA SPAREPART
    Route::get('/sparepart', [SparepartController::class, 'index'])->name('owner.sparepart.index');
    // DATA MONITORING TEKNISI
    Route::get('/monitoring_teknisi', [MonitoringTeknisiController::class, 'index'])->name('owner.monitoring_teknisi.index');
    // DATA PENGADAAN SPAREPART OWNER
    Route::get('/pengadaan_sparepart', [PengadaanSparepartController::class, 'index'])->name('owner.pengadaan_sparepart.index');
    Route::get('/pengadaan_sparepart/detail/{id}', [PengadaanSparepartController::class, 'detail'])->name('owner.pengadaan_sparepart.detail');
    // DATA PENGADAAN TOOLS OWNER
    Route::get('/pengadaan_tools', [ToolsController::class, 'index'])->name('owner.pengadaan_tools.index');
    // DATA REQUEST SPAREPART OWNER
    Route::get('/request_sparepart', [RequestSparepartController::class, 'index'])->name('owner.request_sparepart.index');
    Route::get('/request_sparepart/detail/{id}', [RequestSparepartController::class, 'detail'])->name('owner.request_sparepart.detail');
    // DATA LAPORAN SERVIS
    Route::get('/laporan_servis', [LaporanServisController::class, 'index'])->name('owner.laporan_servis.index');
    // DATA LAPORAN KEUANGAN
    Route::get('/laporan_keuangan', [LaporanKeuanganController::class, 'index'])->name('owner.laporan_keuangan.index');
});

// GROUP UTAMA TEKNISI
Route::middleware(['auth:web', 'cek_role:teknisi'])->prefix('teknisi')->group(function () {
    // DASHBOARD TEKNISI
    Route::get('/dashboard', [DashboardController::class, 'teknisi']);
    // SERVIS KERJA TEKNISI
    Route::get('/servis_kerja', [ServisKerjaController::class, 'index'])->name('teknisi.servis_kerja.index');
    Route::get('/servis_kerja/detail/{id}', [ServisKerjaController::class, 'show'])->name('teknisi.servis_kerja.detail');
    Route::get('/servis_kerja/edit/{id}', [ServisKerjaController::class, 'edit'])->name('teknisi.servis_kerja.edit');
    Route::put('/servis_kerja/update/{id}', [ServisKerjaController::class, 'updateStatus'])->name('teknisi.servis_kerja.updateStatus');
    // DATA REQUEST SPAREPART TEKNISI
    Route::get('/request_sparepart', [RequestSparepartController::class, 'index'])->name('teknisi.request_sparepart.index');
    Route::get('/request_sparepart/create', [RequestSparepartController::class, 'create'])->name('teknisi.request_sparepart.create');
    Route::post('/request_sparepart/store', [RequestSparepartController::class, 'store'])->name('teknisi.request_sparepart.store');
    Route::get('/request_sparepart/detail/{id}', [RequestSparepartController::class, 'detail'])->name('teknisi.request_sparepart.detail');
    // RIWAYAT PEKERJAAN TEKNISI
    Route::get('/riwayat_pekerjaan', [RiwayatPekerjaanController::class, 'index'])->name('teknisi.riwayat_pekerjaan.index');
    Route::get('/riwayat_pekerjaan/detail/{id}', [RiwayatPekerjaanController::class, 'detail'])->name('teknisi.riwayat_pekerjaan.detail');
});
 
// GROUP UTAMA PELANGGAN 
Route::middleware(['auth:pelanggan'])->prefix('pelanggan')->group(function () {
    // DASHBOARD PELANGGAN
    Route::get('/dashboard', [DashboardController::class, 'pelanggan']);
    // BOOKING PELANGGAN
    Route::get('/booking', [BookingController::class, 'index'])->name('pelanggan.booking.index');
    Route::get('/booking/tambah', [BookingController::class, 'create'])->name('pelanggan.booking.create'); 
    Route::post('/booking/store', [BookingController::class, 'store'])->name('pelanggan.booking.store');
    Route::get('/booking/edit/{id}', [BookingController::class, 'edit'])->name('pelanggan.booking.edit');
    Route::put('/booking/update/{id}', [BookingController::class, 'update'])->name('pelanggan.booking.update');
    Route::get('/booking/detail/{id}', [BookingController::class, 'show'])->name('pelanggan.booking.show'); 
    Route::delete('/booking/delete/{id}', [BookingController::class, 'destroy'])->name('pelanggan.booking.destroy');
    // PEMBAYARAN MIDTRANS
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pelanggan.pembayaran.index');
    Route::get('/pembayaran/detail/{id}', [PembayaranController::class, 'detail'])->name('pelanggan.pembayaran.detail');
    Route::post('/pembayaran/{id}/bayar', [PembayaranController::class, 'bayar'])->name('pelanggan.pembayaran.bayar');
    Route::post('/pembayaran/{id}/success',[PembayaranController::class, 'success'])->name('pelanggan.pembayaran.success');
    // STATUS SERVIS
    Route::get('/status_servis', [ServisStatusController::class, 'index'])->name('pelanggan.servis_status.index');
    Route::get('/status_servis/detail/{id}', [ServisStatusController::class, 'show'])->name('pelanggan.servis_status.detail');
    // RIWAYAT SERVIS
    Route::get('/riwayat_servis', [RiwayatServisController::class, 'index'])->name('pelanggan.riwayat_servis.index');
    Route::get('/riwayat_servis/detail/{id}', [RiwayatServisController::class, 'show'])->name('pelanggan.riwayat_servis.show');
});