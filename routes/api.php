<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PayinController;
use App\Http\Controllers\Api\PayoutController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {

    Route::post('/payin', [PayinController::class, 'store']);
    Route::post('/payout', [PayoutController::class, 'store']);

});
