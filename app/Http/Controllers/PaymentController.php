<?php

namespace App\Http\Controllers;

use App\Models\Pemesanans;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false; // Set true untuk production
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function create($pemesanan_id)
    {
        $pemesanan = Pemesanans::with('details.produk')->findOrFail($pemesanan_id);

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pemesanan->id . '-' . time(),
                'gross_amount' => $pemesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => 'Pelanggan',
                'email' => 'pelanggan@example.com',
                'phone' => '08123456789',
            ],
            'item_details' => $this->prepareItems($pemesanan),
            'callbacks' => [
                'finish' => route('payment.finish')
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('payment.create', compact('pemesanan', 'snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function finish(Request $request)
    {
        // Handle notifikasi pembayaran selesai
        return view('payment.finish');
    }

    public function notification(Request $request)
    {
        // Handle notifikasi dari Midtrans
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Proses validasi pembayaran
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // Update status pemesanan menjadi "challenge"
                } else {
                    // Update status pemesanan menjadi "paid"
                }
            }
        } elseif ($transaction == 'settlement') {
            // Update status pemesanan menjadi "paid"
        } elseif ($transaction == 'pending') {
            // Update status pemesanan menjadi "pending"
        } elseif ($transaction == 'deny') {
            // Update status pemesanan menjadi "denied"
        } elseif ($transaction == 'expire') {
            // Update status pemesanan menjadi "expired"
        } elseif ($transaction == 'cancel') {
            // Update status pemesanan menjadi "canceled"
        }

        return response()->json(['status' => 'success']);
    }

    private function prepareItems($pemesanan)
    {
        $items = [];

        foreach ($pemesanan->details as $detail) {
            $items[] = [
                'id' => $detail->produk_id,
                'price' => $detail->produk->harga,
                'quantity' => $detail->jumlah,
                'name' => $detail->produk->nama
            ];
        }

        return $items;
    }
}
