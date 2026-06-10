<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected string $apiUrl;
    protected string $snapUrl;
    protected array  $enabledPayments;
    protected array  $expiry;

    public function __construct()
    {
        $this->serverKey       = config('midtrans.server_key');
        $this->clientKey       = config('midtrans.client_key');
        $this->apiUrl          = config('midtrans.api_url');
        $this->snapUrl         = config('midtrans.snap_url');
        $this->enabledPayments = config('midtrans.enabled_payments', []);
        $this->expiry          = config('midtrans.expiry');
    }

    /**
     * Buat Snap Token untuk transaksi baru.
     *
     * @param  array $params  Parameter transaksi (lihat buildPayload)
     * @return array ['snap_token' => string, 'redirect_url' => string]
     * @throws \Exception
     */
    public function createSnapToken(array $params): array
    {
        $payload  = $this->buildPayload($params);
        $auth     = base64_encode($this->serverKey . ':');
        $response = Http::withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Basic ' . $auth,
                'Content-Type'  => 'application/json',
            ])
            ->post($this->apiUrl, $payload);
        if ($response->failed()) {
            throw new \Exception(
                'Midtrans error: ' . $response->body(),
                $response->status()
            );
        }
        return $response->json();
        // returns: ['token' => '...', 'redirect_url' => '...']
    }

    /**
     * Verifikasi notifikasi dari Midtrans webhook.
     * Signature dihitung: SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifyNotification(array $notification): bool
    {
        $signatureKey = hash('sha512',
            $notification['order_id'] .
            $notification['status_code'] .
            $notification['gross_amount'] .
            $this->serverKey
        );

        return $signatureKey === $notification['signature_key'];
    }

    /**
     * Tentukan status transaksi dari notifikasi Midtrans.
     * Return: 'paid' | 'pending' | 'failed' | 'expired' | 'refunded' | 'unknown'
     */
    public function resolveTransactionStatus(array $notification): string
    {
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus       = $notification['fraud_status'] ?? '';
        return match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept'  => 'paid',
            $transactionStatus === 'settlement'                            => 'paid',
            $transactionStatus === 'pending'                               => 'pending',
            $transactionStatus === 'deny'                                  => 'failed',
            $transactionStatus === 'expire'                                => 'expired',
            $transactionStatus === 'cancel'                                => 'failed',
            $transactionStatus === 'refund'                                => 'refunded',
            default                                                        => 'unknown',
        };
    }

    /**
     * Return client key (dipakai di frontend / Blade view).
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Return Snap JS URL (sandbox atau production).
     */
    public function getSnapUrl(): string
    {
        return $this->snapUrl;
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    private function buildPayload(array $params): array
    {
        // Wajib ada: order_id, amount, customer (name, email, phone)
        $orderId    = $params['order_id']    ?? 'ORDER-' . Str::upper(Str::random(10));
        $amount     = (int) ($params['amount'] ?? 0);
        $firstName  = $params['customer']['name']  ?? 'Customer';
        $email      = $params['customer']['email'] ?? '';
        $phone      = $params['customer']['phone'] ?? '';
        $items      = $params['items']        ?? [];

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $firstName,
                'email'      => $email,
                'phone'      => $phone,
            ],
            'callbacks' => [
                'finish' => $params['finish_url'] ?? url('/payment/finish'),
            ],
            'expiry' => [
                'unit'     => $this->expiry['unit'],
                'duration' => $this->expiry['duration'],
            ],
        ];

        // Item details (opsional tapi sangat direkomendasikan)
        if (!empty($items)) {
            $payload['item_details'] = collect($items)->map(fn($item) => [
                'id'       => $item['id']       ?? Str::uuid(),
                'price'    => (int) $item['price'],
                'quantity' => (int) ($item['quantity'] ?? 1),
                'name'     => $item['name'],
            ])->toArray();
        }

        // Filter payment methods jika di-set
        if (!empty($this->enabledPayments)) {
            $payload['enabled_payments'] = $this->enabledPayments;
        }

        return $payload;
    }
}
