<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; 

class PembayaranController extends Controller
{
    /**
     * DAFTAR PEMBAYARAN PELANGGAN
     */
    public function index()
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        $pembayaran = Pembayaran::with(['booking', 'servis'])
            ->whereHas('booking', function ($q) use ($idPelanggan) {
                $q->where('id_pelanggan', $idPelanggan);
            })->latest()->paginate(10);
        return view('pelanggan.proses.pembayaran.index', compact('pembayaran'));
    }

    /**
     * DETAIL PEMBAYARAN
     */
    public function detail($id)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        $pembayaran = Pembayaran::with(['booking.pelanggan', 'servis'])
            ->whereHas('booking', function ($q) use ($idPelanggan) {
                $q->where('id_pelanggan', $idPelanggan);
            })->findOrFail($id);
        return view('pelanggan.proses.pembayaran.detail', compact('pembayaran'));
    }

    /**
     * GENERATE SNAP TOKEN ()
     */
    public function bayar($id)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        $pembayaran = Pembayaran::with(['booking.pelanggan', 'servis'])
            ->whereHas('booking', function ($q) use ($idPelanggan) {
                $q->where('id_pelanggan', $idPelanggan);
            })->findOrFail($id);

        if ($pembayaran->status_pembayaran == 'sukses' || $pembayaran->snap_token) {
            return response()->json(['success' => false, 'message' => 'Transaksi sudah dibuat']);
        }

        try {
            $orderId = 'PAY-' . $pembayaran->id_pembayaran . '-' . time();
            $desc = $pembayaran->jenis_pembayaran == 'dp' ? 'DP Booking Servis' : 'Pelunasan Servis Laptop';
            $payload = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $pembayaran->nominal,
                ],
                'customer_details' => [
                    'first_name' => $pembayaran->booking->pelanggan->nama,
                    'email'      => $pembayaran->booking->pelanggan->email,
                    'phone'      => $pembayaran->booking->pelanggan->no_hp,
                ],
                'custom_field1' => $desc,
                'callbacks' => [
                    'finish' => route('pelanggan.pembayaran.detail', $pembayaran->id_pembayaran),
                ],
                'expiry' => [
                    'unit'     => config('midtrans.expiry.unit', 'minutes'),
                    'duration' => config('midtrans.expiry.duration', 15),
                ],
                'item_details' => [
                    [
                        'id'       => $pembayaran->id_pembayaran,
                        'name'     => $desc,
                        'price'    => (int) $pembayaran->nominal,
                        'quantity' => 1
                    ]
                ]
            ];

            if (!empty(config('midtrans.enabled_payments'))) {
                $payload['enabled_payments'] = config('midtrans.enabled_payments');
            }

            $serverKey = config('midtrans.server_key');
            $apiUrl    = config('midtrans.api_url');
            $auth      = base64_encode($serverKey . ':');
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Basic ' . $auth,
                    'Content-Type'  => 'application/json',
                ])
                ->post($apiUrl, $payload);

            if ($response->failed()) {
                throw new \Exception('Midtrans error: ' . $response->body());
            }

            $result = $response->json();
            $pembayaran->update([
                'snap_token'        => $result['token'],
                'midtrans_order_id' => $orderId
            ]);
            return response()->json([
                'success' => true,
                'token'   => $result['token']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Error', ['message' => $e->getMessage()]);
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
            return response()->json(['success' => true]);
        }
    
        $pembayaran->update([
            'status_pembayaran' => 'sukses',
            'midtrans_transaction_id' => $request->transaction_id,
            'tanggal_bayar' => now()
        ]);
    
        if ($pembayaran->jenis_pembayaran == 'dp') {
            $pembayaran->booking->update(['status_dp' => 'sudah lunas']);
        } 
    
        if ($pembayaran->jenis_pembayaran == 'pelunasan' && $pembayaran->servis) {
            $pembayaran->servis->update([
                'status_pelunasan' => 'sudah lunas',
                'status_servis' => 'bisa diambil'
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function indexAdmin(Request $request)
    {
        $query = Pembayaran::query();
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('booking.pelanggan', function($q) use ($request) {
                $q->where('nama', 'LIKE', $request->search . '%');
            });
        }
        if ($request->has('jenis_pembayaran') && $request->jenis_pembayaran != '') {
        $query->where('jenis_pembayaran', $request->jenis_pembayaran);
        }
        if ($request->has('status_pembayaran') && $request->status_pembayaran != '') {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        $pembayaran = $query->with(['booking.pelanggan', 'servis'])->latest()->paginate(10);
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
                if ($pembayaran->jenis_pembayaran == 'dp') {
                    $pembayaran->booking->update(['status_dp' => 'sudah lunas']);
                }
                if ($pembayaran->jenis_pembayaran == 'pelunasan' && $pembayaran->servis) {
                    $pembayaran->servis->update(['status_pelunasan' => 'sudah lunas', 'status_servis' => 'bisa diambil']);
                }
                } else {
                $pembayaran->update(['metode_pembayaran' => 'transfer','status_pembayaran' => 'pending','tanggal_bayar' => null]);
            }

            DB::commit();
            return redirect()->route('admin.pembayaran.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}