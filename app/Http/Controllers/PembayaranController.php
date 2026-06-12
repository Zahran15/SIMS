<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * DAFTAR PEMBAYARAN PELANGGAN
     */
    public function index()
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        $pembayaran = Pembayaran::with([
                'booking',
                'servis'
            ])
            ->whereHas('booking', function ($q) use ($idPelanggan) {
                $q->where('id_pelanggan', $idPelanggan);
            })
            ->latest()
            ->paginate(10);

        return view('pelanggan.proses.pembayaran.index', compact('pembayaran'));
    }

    /**
     * DETAIL PEMBAYARAN
     */
    public function detail($id)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        $pembayaran = Pembayaran::with([
                'booking.pelanggan',
                'servis'
            ])
            ->whereHas('booking', function ($q) use ($idPelanggan) {
                $q->where('id_pelanggan', $idPelanggan);
            })
            ->findOrFail($id);

        return view('pelanggan.proses.pembayaran.detail', compact('pembayaran'));
    }

    /**
     * GENERATE SNAP TOKEN
     */
    public function bayar($id)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();

        $pembayaran = Pembayaran::with([
                'booking.pelanggan',
                'servis'
            ])
            ->whereHas('booking', function ($q) use ($idPelanggan) {
                $q->where('id_pelanggan', $idPelanggan);
            })
            ->findOrFail($id);

        if ($pembayaran->status_pembayaran == 'sukses'
            || $pembayaran->snap_token
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah dibuat'
            ]);
        }

        try {

            $orderId = 'PAY-' .
                $pembayaran->id_pembayaran .
                '-' .
                time();

            $result = $this->midtrans->createSnapToken([
                'order_id' => $orderId,
                'amount' => $pembayaran->nominal,

                'customer' => [
                    'name'  => $pembayaran->booking->pelanggan->nama,
                    'email' => $pembayaran->booking->pelanggan->email,
                    'phone' => $pembayaran->booking->pelanggan->no_hp,
                ],

                'items' => [
                    [
                        'id' => $pembayaran->id_pembayaran,
                        'name' => $pembayaran->jenis_pembayaran == 'deposit'
                            ? 'Deposit Booking Servis'
                            : 'Pelunasan Servis Laptop',
                        'price' => $pembayaran->nominal,
                        'quantity' => 1
                    ]
                ],

                'finish_url' => route('pelanggan.pembayaran.detail', $pembayaran->id_pembayaran)
            ]);

            $pembayaran->update([
                'snap_token' => $result['token'],
                'midtrans_order_id' => $orderId
            ]);

            return response()->json([
                'success' => true,
                'token' => $result['token']
            ]);

        } catch (\Exception $e) {

            Log::error('Midtrans Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi'
            ], 500);
        }
    }

    public function success(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
    
        if ($pembayaran->status_pembayaran == 'sukses') {
            return response()->json([
                'success' => true
            ]);
        }
    
        $pembayaran->update([
            'status_pembayaran' => 'sukses',
            'midtrans_transaction_id' => $request->transaction_id,
            'tanggal_bayar' => now()
        ]);
    
        if ($pembayaran->jenis_pembayaran == 'deposit') {
    
            $pembayaran->booking->update([
                'status_deposit' => 'sudah lunas'
            ]);
        } 
    
        if (
            $pembayaran->jenis_pembayaran == 'pelunasan' &&
            $pembayaran->servis
        ) {
    
            $pembayaran->servis->update([
                'status_pelunasan' => 'sudah lunas',
                'status_servis' => 'bisa diambil'
            ]);
        }
    
        return response()->json([
            'success' => true
        ]);
    }

    public function indexAdmin()
    {
        // Mengambil semua pembayaran milik seluruh pelanggan
        $pembayaran = Pembayaran::with(['booking.pelanggan', 'servis'])->latest()->paginate(10);
        return view('admin.proses.pembayaran.index', compact('pembayaran'));
    }

    public function detailAdmin($id)
    {
        $pembayaran = Pembayaran::with(['booking.pelanggan', 'servis'])->findOrFail($id);
        return view('admin.proses.pembayaran.detail', compact('pembayaran'));
    }

    public function editAdmin($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('admin.proses.pembayaran.edit', compact('pembayaran'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->metode_pembayaran == 'cash') {
                $pembayaran->update([
                    'metode_pembayaran' => 'cash',
                    'status_pembayaran' => 'sukses',
                    'snap_token'        => null,
                    'midtrans_order_id' => null,
                    'tanggal_bayar'     => now(),
                ]);
                // Sinkronisasi status deposit booking jika jenisnya deposit
                if ($pembayaran->jenis_pembayaran == 'deposit') {
                    $pembayaran->booking->update(['status_deposit' => 'sudah lunas']);
                }
                // Sinkronisasi status servis jika jenisnya pelunasan
                if ($pembayaran->jenis_pembayaran == 'pelunasan' && $pembayaran->servis) {
                    $pembayaran->servis->update(['status_pelunasan' => 'sudah lunas', 'status_servis' => 'bisa diambil']);
                }
                } else {
                // Jika admin mengembalikan ke mode midtrans (opsional)
                $pembayaran->update(['metode_pembayaran' => 'midtrans','status_pembayaran' => 'pending','tanggal_bayar' => null]);
            }

            DB::commit();
            return redirect()->route('admin.pembayaran.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}