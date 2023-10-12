<?php

namespace App\Services;

use App\Models\Gateway;
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
            Payment::create([
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
        });
        return $driver->pay()->render();
    }
}
