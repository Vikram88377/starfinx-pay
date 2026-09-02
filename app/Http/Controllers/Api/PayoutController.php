<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayoutApiRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
        public function __construct(
        protected PaymentService $paymentService
    ) {}

        public function store(PayoutApiRequest $request)
        {
                $payout = $this->paymentService->createPayout($request->validated());

             return response()->json([
            'success' => true,
            'message' => 'Payout initiated successfully',
            'data' => $payout,
        ], 201);


        }



}
