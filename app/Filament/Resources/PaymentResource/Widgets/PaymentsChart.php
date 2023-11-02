<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentsChart extends ChartWidget
{
    protected static ?string $heading = 'پرداخت‌های 7 روز گذشته';

    protected function getData(): array
    {
        $data = Trend::query(
            Payment::query()->paid(),
        )
            ->between(
                start: now()->subDays(6)->startOfDay(),
                end: now()
            )
            ->perDay()
            ->sum('amount');
        return [
            'datasets' => [
                [
                    'label' => 'ریال',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => verta($value->date)->format('l')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
