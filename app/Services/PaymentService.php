<?php

namespace App\Services;

use App\Enums\PayableTypeEnum;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\Payment;
use Shetabit\Payment\Facade\Payment as Driver;
use Shetabit\Multipay\Invoice;

class PaymentService
{
    public function store(object $request)
    {
        $gatewayInfo = Gateway::where('driver', $request->driver)->first();
        if (is_null($gatewayInfo)) {
            return back()->withErrors(__('message.gateway_invalid'));
        }
        $invoice = new Invoice();
        $invoice->amount($request->amount);
        $driver = Driver::via($request->driver)
            ->config($gatewayInfo->config)
            ->callbackUrl(url('/callback/' . $invoice->getUuid()));
        $driver->purchase($invoice, function ($driver, $transactionId) use ($invoice, $request, $gatewayInfo) {
            $newPayment = Payment::create([
                'uuid' => $invoice->getUuid(),
                'payable_type' => $request->payable_type,
                'payable_id' => $request->payable_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'amount' => $request->amount,
                'gateway_id' => $gatewayInfo->id,
                'transactionid' => $transactionId
            ]);
            if ($request->payable_type == PayableTypeEnum::Order->value) {
                $order = Order::find($request->payable_id);
                $order->payment_id = $newPayment->id;
                $order->save();
            }
        });
        return $driver->pay()->render();
    }
}
