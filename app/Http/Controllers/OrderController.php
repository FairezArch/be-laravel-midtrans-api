<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    //

    public function store(TransactionRequest $request)
    {
        try {
            //code...
            DB::beginTransaction();

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');


            $usr = User::find($request->user_id);
            $customer_details = [
                'first_name' => $usr->name,
                'email' => $usr->email,
            ];

            $now = Carbon::now()->toDateTimeString();
            $inputProduct = [];
            $uuid = (string) Str::uuid();
            $gross_amount = 0;
            $order_id = $uuid;

            foreach($request->products as $key => $value){
                $currentPrice = Product::select('price')->firstWhere('id', $value['id'])->price;
                $inputProduct[$key]['user_id'] = $request->user_id;
                $inputProduct[$key]['product_id'] = $value['id'];
                $inputProduct[$key]['transaction_id'] = $uuid;
                $inputProduct[$key]['order_date'] = $now;
                $inputProduct[$key]['price'] = $currentPrice;
                $inputProduct[$key]['qty'] = $value['qty'];
                $inputProduct[$key]['amount'] = $value['qty'] * $currentPrice;

                $inputProduct[$key]['created_at'] = $now;
                $inputProduct[$key]['updated_at'] = $now;

                $gross_amount += $value['qty'] * $currentPrice;
            }

            $midtrans_params = [
                'payment_type' => 'bank_transfer',
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $gross_amount,
                ],
                'bank_transfer' => [
                    'bank' => $request->bank
                ],
                'customer_details' => $customer_details
            ];

            $res = Http::withBasicAuth(config('midtrans.server_key'), '')->post((string)config("midtrans.sandbox_url"), $midtrans_params);

            if($res->json()['status_code'] != '201'){
                Log::error('Store Order error: ', ['order' => $res->json()['status_message']]);

                return response()->json([
                    'success' => false,
                    'message' => $res->json()['status_message'],
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            Order::insert($inputProduct);

            DB::commit();

            Log::info('Store Order: ', ['order' => [
                'success' => true,
                'message' => __('validation.success_response'),
                'data' => [
                    'transaction' => $order_id,
                    'bank' => $res->json()['va_numbers'][0]['bank'],
                    'va' => $res->json()['va_numbers'][0]['va_number'],
                    'name' => $usr->name,
                    'email' => $usr->email
                ],
            ]]);

            return response()->json([
                'success' => true,
                'message' => __('validation.success_response'),
                'data' => [
                    'transaction' => $order_id,
                    'bank' => $res->json()['va_numbers'][0]['bank'],
                    'va' => $res->json()['va_numbers'][0]['va_number'],
                    'name' => $usr->name,
                    'email' => $usr->email
                ],
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error('Store Order error: ', ['order' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
