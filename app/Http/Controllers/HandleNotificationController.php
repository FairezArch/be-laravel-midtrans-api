<?php

namespace App\Http\Controllers;

use App\Models\HistoryPaymentUser;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandleNotificationController extends Controller
{
    //
    public function notificationComming(Request $request)
    {
        $payload = $request->all();

        Log::info('incomming-notification-midtrans', ['mitrans' => $payload]);

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $signature = $payload['signature_key'];
        $transStatus = $payload['transaction_status'];

        $hashSignature = hash('sha512', $orderId.$statusCode.$grossAmount.config('midtrans.server_key'));

        if($signature !== $hashSignature) {
            Log::info('incomming-notification-midtrans', ['mitrans' => [
                'success' => false,
                'message' => 'Invalid Signature',
                'order_id' => $orderId,
            ]]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid Signature'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $order = Order::where('transaction_id', $orderId)->first();

        if(empty($order)) {
            Log::info('incomming-notification-midtrans', ['mitrans' => [
                'success' => false,
                'message' => 'Invalid Order',
                'order_id' => $orderId,
            ]]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid Order'
            ], Response::HTTP_BAD_REQUEST);
        }

        //code...
        if($transStatus == 'settlement') {
            Order::where('transaction_id', $orderId)->update([
                'status' => 'paid'
            ]);
            Log::info('incomming-notification-midtrans', ['mitrans' => [
                'success' => true,
                'message' => 'transaction Paid',
                'order_id' => $orderId,
            ]]);
        }

        if($transStatus == 'expire') {
            Order::where('transaction_id', $orderId)->update([
                'status' => 'failed'
            ]);
            Log::info('incomming-notification-midtrans', ['mitrans' => [
                'success' => true,
                'message' => 'transaction failed',
                'order_id' => $orderId,
            ]]);
        }

        HistoryPaymentUser::create([
            'transaction_id' => $orderId,
            'status_payment' => $transStatus,
            'payload' => json_encode($payload)
        ]);

        return response()->json([
            'success' => true,
            'message' => __('validation.success_response'),
            'data' => [
                'transaction' => $transStatus == 'settlement' ? 'success' : 'failed/expire',
            ],
        ]);
    }
}
