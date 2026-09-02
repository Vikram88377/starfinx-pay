<?php

namespace App\Services;
use App\Models\Payin;
use App\Models\Payout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Create a new class instance.
     */

        public function CreatePayin(array $data): Payin
        {


            $transactionId = 'PAYIN-' . strtoupper(Str::random(12));

            $payin = Payin::create([
            'merchant_id' => $data['merchant_id'],
            'transaction_id' => $transactionId,
            'amount' => $data['amount'],
            'status' => 'PENDING',
            'request_payload' => $data,
        ]);

        Log::info('Pay-in initiated',

         [
            'transaction_id' => $transactionId,
            'merchant_id' => $data['merchant_id'],
            'amount' => $data['amount'],
        ]

        );
                 return $payin;

        }
        public function createPayout(array $data): Payout
        {


            $transactionId = 'PAYOUT-' . strtoupper(Str::random(12));

            $payout = Payout::create([
            'merchant_id' => $data['merchant_id'],
            'transaction_id' => $transactionId,
            'amount' => $data['amount'],
            'status' => 'PENDING',
            'request_payload' => $data,
        ]);

        Log::info('Pay-out initiated',

         [
            'transaction_id' => $transactionId,
            'merchant_id' => $data['merchant_id'],
            'amount' => $data['amount'],
        ]

        );
                 return $payout;


        }




}
