<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merchant;
use App\Models\Wallet;



class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = [

       [
                'name' => 'Demo Merchant',
                'email' => 'merchant@example.com',
                'phone' => '9876543210',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Test Merchant',
                'email' => 'test@example.com',
                'phone' => '9876543211',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Inactive Merchant',
                'email' => 'inactive@example.com',
                'phone' => '9876543212',
                'status' => 'INACTIVE',
            ],

];

            foreach ($merchants as $merchantData) {
            $merchant = Merchant::create($merchantData);

            Wallet::create([
                'merchant_id' => $merchant->id,
                'balance' => 10000.00,
            ]);
        }



        }
}
