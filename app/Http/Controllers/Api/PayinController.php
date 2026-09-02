<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PayinApiRequest;
use App\Services\PaymentService;


class PayinController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}


    public function store(PayinApiRequest $request)

    {
        $payin = $this->paymentService->CreatePayin($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pay-in initiated successfully',
            'data' => $payin,
        ], 201);



    }




}
