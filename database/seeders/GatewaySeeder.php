<?php

namespace Database\Seeders;

use App\Models\Gateway;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'driver' => 'zarinpal',
                'name' => 'زرین پال',
                'config' => ['mode' => 'sandbox', 'merchantId' => '111111111111111111111111111111111111'],
                'is_default' => true,
                'is_active' => true
            ],
            [
                'driver' => 'idpay',
                'name' => 'آیدی پی',
                'config' => ['sandbox' => true, 'merchantId' => '6a7f99eb-7c20-4412-a972-6dfb7cd253a4'],
                'is_default' => true,
                'is_active' => true
            ],
        ];

        foreach ($drivers as $driver){
            Gateway::create($driver);
        }
    }
}
