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
                'driver' => 'fanavacard',
                'name' => 'فن آوا کارت',
                'config' => [
                    'username' => '',
                    'password' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'atipay',
                'name' => 'آتی پی',
                'config' => [
                    'apikey' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'asanpardakht',
                'name' => 'آسان پرداخت',
                'config' => [
                    'username' => '',
                    'password' => '',
                    'merchantConfigID' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'behpardakht',
                'name' => 'به پرداخت',
                'config' => [
                    'terminalId' => '',
                    'username' => '',
                    'password' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'digipay',
                'name' => 'دیجی پی',
                'config' => [
                    'username' => '',
                    'password' => '',
                    'client_id' => '',
                    'client_secret' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'etebarino',
                'name' => 'اعتبارینو',
                'config' => [
                    'merchantId' => '',
                    'terminalId' => '',
                    'username' => '',
                    'password' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'idpay',
                'name' => 'آیدی پی',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'irankish',
                'name' => 'ایران کیش',
                'config' => [
                    'terminalId' => '',
                    'password' => '',
                    'acceptorId' => '',
                    'pubKey' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'nextpay',
                'name' => 'نکست پی',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'omidpay',
                'name' => 'امید پی',
                'config' => [
                    'username' => '',
                    'merchantId' => '',
                    'password' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'parsian',
                'name' => 'پارسیان',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'pasargad',
                'name' => 'پاسارگاد',
                'config' => [
                    'merchantId' => '',
                    'terminalCode' => '',
                    'certificate' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'payir',
                'name' => 'پی',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'paypal',
                'name' => 'PayPal',
                'config' => [
                    'currency' => '',
                    'id' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'payping',
                'name' => 'پی پینگ',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'paystar',
                'name' => 'پی استار',
                'config' => [
                    'gatewayId' => '',
                    'signKey' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'poolam',
                'name' => 'پولام',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'sadad',
                'name' => 'سداد',
                'config' => [
                    'key' => '',
                    'merchantId' => '',
                    'terminalId' => '',
                    '' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'saman',
                'name' => 'سامان',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'sep',
                'name' => 'سپ',
                'config' => [
                    'terminalId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'sepehr',
                'name' => 'سپهر',
                'config' => [
                    'terminalId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'walleta',
                'name' => 'والتا',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'yekpay',
                'name' => 'یک پی',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'zarinpal',
                'name' => 'زرین پال',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'zibal',
                'name' => 'زیبال',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'sepordeh',
                'name' => 'سپرده',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'rayanpay',
                'name' => 'رایان پی',
                'config' => [
                    'username' => '',
                    'client_id' => '',
                    'password' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'sizpay',
                'name' => 'سیزپی',
                'config' => [
                    'merchantId' => '',
                    'terminal' => '',
                    'username' => '',
                    'password' => '',
                    'SignData' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'vandar',
                'name' => 'وندار',
                'config' => [
                    'merchantId' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'aqayepardakht',
                'name' => 'آقای پرداخت',
                'config' => [
                    'pin' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'azki',
                'name' => 'ازکی',
                'config' => [
                    'merchantId' => '',
                    'key' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'payfa',
                'name' => 'پی فا',
                'config' => [
                    'apiKey' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
            [
                'driver' => 'toman',
                'name' => 'تومن',
                'config' => [
                    'shop_slug' => '',
                    'auth_code' => '',
                    'data' => '',
                ],
                'is_default' => false,
                'is_active' => false,
            ],
        ];

        foreach ($drivers as $driver) {
            Gateway::create($driver);
        }
    }
}
