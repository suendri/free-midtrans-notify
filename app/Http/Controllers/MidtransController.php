<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransRouter;
use App\Models\PaymentNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\MidtransCallbackNotification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();

        // Log awal untuk debugging
        Log::info('Delitekno Midtrans callback received', $data);

        // Validasi minimal
        $validator = Validator::make($data, [
            'order_id'      => 'required|string',
            'status_code'   => 'required|string',
            'gross_amount'  => 'required|string',
            'signature_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid payload received from Midtrans', $validator->errors()->toArray());
            return response()->json(['message' => 'Invalid payload', 'errors' => $validator->errors()], 400);
        }

        // Validasi signature
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $expectedSignature = hash(
            'sha512',
            $data['order_id'] . $data['status_code'] . $data['gross_amount'] . $serverKey
        );

        if ($data['signature_key'] !== $expectedSignature) {
            Log::warning('Invalid signature from Midtrans', [
                'received' => $data['signature_key'],
                'expected' => $expectedSignature,
                'order_id' => $data['order_id']
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Simpan ke database
        PaymentNotification::create([
            'order_id'           => $data['order_id'],
            'transaction_id'     => $data['transaction_id'] ?? '',
            'transaction_status' => $data['transaction_status'] ?? '',
            'payment_type'       => $data['payment_type'] ?? '',
            'gross_amount'       => $data['gross_amount'] ?? '',
            'currency'           => $data['currency'] ?? '',
            'fraud_status'       => $data['fraud_status'] ?? null,
            'transaction_time'   => $data['transaction_time'] ?? null,
            'settlement_time'    => $data['settlement_time'] ?? null,
            'raw_payload'        => $data,
        ]);

        // Tentukan sistem tujuan berdasarkan order_id
        try {
            $targetUrl = MidtransRouter::resolveTargetSystem($data['order_id']);

            if ($targetUrl) {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($targetUrl, $data);

                Log::info("Midtrans Forwarded to {$targetUrl}", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } else {
                Log::warning('Midtrans No target system matched for order_id: ' . $data['order_id']);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Failed to forward to target system: ' . $e->getMessage());
        }

        // Kirim email notifikasi
        try {
            if (($data['transaction_status'] ?? '') === 'settlement') {
                Mail::to('delitekno.mediamandiri@gmail.com')->send(
                    new MidtransCallbackNotification(
                        $data['order_id'],
                        $targetUrl ?? null,
                        $data
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Failed to send email notification: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Notification received'], 200);
    }
}
