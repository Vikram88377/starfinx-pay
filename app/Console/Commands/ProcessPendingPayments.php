<?php

namespace App\Console\Commands;
use App\Models\Payin;
use App\Models\Payout;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
            protected $signature = 'payments:process';


    /**
     * The console command description.
     *
     * @var string
     */
      protected $description = 'Process pending payins and payouts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
                $this->processPayins();
             $this->processPayouts();

            $this->info('Pending payments processed.');
    }


       private function processPayins()
{
    $payins = Payin::where('status', 'PENDING')->get();

                foreach ($payins as $payin) {
                    try {
                        DB::transaction(function () use ($payin) {

                            $payment = Payin::where('id', $payin->id)
                                ->where('status', 'PENDING')
                                ->lockForUpdate()
                                ->first();

                            if (!$payment) {
                                return;
                            }

                            $status = $this->getRandomStatus();

                            $payment->update([
                                'status' => $status,
                                'response_payload' => [
                                    'transaction_id' => $payment->transaction_id,
                                    'status' => $status,
                                    'processed_at' => now()->toDateTimeString(),
                                ],
                            ]);

                            Log::info('Pay-in status updated', [
                                'transaction_id' => $payment->transaction_id,
                                'status' => $status,
                            ]);

                            if ($status === 'SUCCESS') {

                                $wallet = Wallet::where('merchant_id', $payment->merchant_id)
                                    ->lockForUpdate()
                                    ->first();

                                $wallet->increment('balance', $payment->amount);

                                Log::info('Pay-in amount added to wallet', [
                                    'transaction_id' => $payment->transaction_id,
                                    'amount' => $payment->amount,
                                ]);
                            }
                        });
                    } catch (\Exception $e) {
                        Log::error('Pay-in processing failed', [
                            'transaction_id' => $payin->transaction_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
                    private function processPayouts()
                    {
                        $payouts = Payout::where('status', 'PENDING')->get();

                        foreach ($payouts as $payout) {
                            try {
                                DB::transaction(function () use ($payout) {

                                    $payment = Payout::where('id', $payout->id)
                                        ->where('status', 'PENDING')
                                        ->lockForUpdate()
                                        ->first();

                                    if (!$payment) {
                                        return;
                                    }

                                    $status = $this->getRandomStatus();

                                    // For payout success, check wallet balance
                                    if ($status === 'SUCCESS') {

                                        $wallet = Wallet::where('merchant_id', $payment->merchant_id)
                                            ->lockForUpdate()
                                            ->first();

                                        if (!$wallet || $wallet->balance < $payment->amount) {

                                            $payment->update([
                                                'status' => 'FAILED',
                                            ]);

                                            Log::warning('Payout failed due to insufficient balance', [
                                                'transaction_id' => $payment->transaction_id,
                                            ]);

                                            return;
                                        }

                                        $wallet->decrement('balance', $payment->amount);

                                        Log::info('Payout amount deducted from wallet', [
                                            'transaction_id' => $payment->transaction_id,
                                            'amount' => $payment->amount,
                                        ]);
                                    }

                                    $payment->update([
                                        'status' => $status,
                                        'response_payload' => [
                                            'transaction_id' => $payment->transaction_id,
                                            'status' => $status,
                                            'processed_at' => now()->toDateTimeString(),
                                        ],
                                    ]);

                                    Log::info('Payout status updated', [
                                        'transaction_id' => $payment->transaction_id,
                                        'status' => $status,
                                    ]);
                                });
                            } catch (\Exception $e) {
                                Log::error('Payout processing failed', [
                                    'transaction_id' => $payout->transaction_id,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                    }

    private function getRandomStatus()
    {
        return collect([
            'SUCCESS',
            'FAILED',
            'PENDING',
        ])->random();
    }
}
