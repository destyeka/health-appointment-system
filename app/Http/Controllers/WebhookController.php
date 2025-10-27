<?php

namespace App\Http\Controllers;

use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePayment(Request $request)
    {
        // Log webhook received
        Log::info('Webhook received', $request->all());

        // Verify webhook signature
        $signature = $request->header('X-Webhook-Signature');
        $webhookSecret = config('services.payment.webhook_secret');

        // Get payload and calculate expected signature
        $payload = $request->all();
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $webhookSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid webhook signature', [
                'expected' => $expectedSignature,
                'received' => $signature,
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Process webhook
        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'payment.success') {
            $externalId = $data['external_id'];

            $paymentDetails = PaymentDetail::where('id_payment_detail', $externalId)->first();

            if (!$paymentDetails) {
                Log::warning('Payment details not found', ['external_id' => $externalId]);
                return response()->json(['error' => 'Payment details not found'], 404);
            }

            // Check if already processed (idempotency)
            if ($paymentDetails->status_payment === 'paid') {
                Log::info('Payment already processed', ['id_payment_detail' => $paymentDetails->id_payment_detail]);
                return response()->json(['message' => 'Already processed'], 200);
            }


            // Update order status
            $paymentDetails->update([
                'status_payment' => 'paid',
                'updated_at' => now(),
            ]);

            // // Reduce product stock
            // $product = $paymentDetails->product;
            // $product->decrement('stock', $paymentDetails->quantity);

            // Log::info('Payment success processed', [
            //     'order_id' => $paymentDetails->id,
            //     'order_number' => $paymentDetails->order_number,
            //     'amount' => $paymentDetails->total_amount,
            // ]);

            return response()->json(['message' => 'Webhook processed successfully'], 200);
        }

        if ($event === 'payment.failed') {
            $externalId = $data['external_id'];

            $paymentDetails = PaymentDetail::where('id_payment_detail', $externalId)->first();

            if ($paymentDetails) {
                $paymentDetails->update(['status_payment' => 'unpaid']);
                Log::info('Payment failed processed', ['id_payment_detail' => $paymentDetails->id_payment_detail]);
            }

            return response()->json(['message' => 'Webhook processed'], 200);
        }

        if ($event === 'payment.expired') {
            $externalId = $data['external_id'];

            $paymentDetails = PaymentDetail::where('id_payment_detail', $externalId)->first();

            if ($paymentDetails) {
                $paymentDetails->update(['status_payment' => 'unpaid']);
                Log::info('Payment expired processed', ['id_payment_detail' => $paymentDetails->id_payment_detail]);
            }

            return response()->json(['message' => 'Webhook processed'], 200);
        }

        return response()->json(['message' => 'Event not handled'], 200);
    }
}
