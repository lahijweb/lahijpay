<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'title' => 'در انتظار پرداخت',
                'description' => 'سفارش در انتظار پرداخت است.',
                'is_active' => true,
                'is_primary' => true,
            ],
            [
                'title' => 'پرداخت شده',
                'description' => 'سفارش پرداخت شده است.',
                'is_active' => true,
                'is_primary' => true,
            ],
            [
                'title' => 'در حال پردازش',
                'description' => 'سفارش در حال پردازش است.',
                'is_active' => true,
                'is_primary' => false,
            ],
            [
                'title' => 'ارسال شده',
                'description' => 'سفارش ارسال شده است.',
                'is_active' => true,
                'is_primary' => false,
            ],
            [
                'title' => 'تکمیل شده',
                'description' => 'سفارش تکمیل شده است.',
                'is_active' => true,
                'is_primary' => false,
            ],
            [
                'title' => 'لغو شده',
                'description' => 'سفارش لغو شده است.',
                'is_active' => true,
                'is_primary' => false,
            ],
            [
                'title' => 'مرجوعی',
                'description' => 'سفارش مرجوع شده است.',
                'is_active' => true,
                'is_primary' => false,
            ],
        ];
        foreach ($statuses as $status) {
            OrderStatus::create($status);
        }
    }
}
