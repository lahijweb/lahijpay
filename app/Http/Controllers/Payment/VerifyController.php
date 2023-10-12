<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Payment;
use Illuminate\Support\Facades\Redirect;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment as Driver;

class VerifyController extends Controller
{
    public function verify($uuid)
    {
        $transactionInfo = Payment::where('uuid', $uuid)->first();
        if (is_null($transactionInfo))
            return Redirect::route('payment.callback')->withErrors(__('message.transaction_not_found'));
        if ($transactionInfo->status != PaymentStatusEnum::Pending)
            return Redirect::route('payment.callback')->withErrors(__('message.transaction_not_pending'));
        $transaction_id = $transactionInfo->transactionid;
        $gatewayInfo = Gateway::find($transactionInfo->gateway_id);
        if (is_null($gatewayInfo))
            return Redirect::route('payment.callback')->withErrors(__('message.gateway_invalid'));
        try {
            $receipt = Driver::via($gatewayInfo->driver)
                ->amount($transactionInfo->amount)
                ->config($gatewayInfo->config)
                ->transactionId($transaction_id)->verify();
            $referenceId = $receipt->getReferenceId();
            $transactionInfo->referenceid = $referenceId;
            $transactionInfo->status = PaymentStatusEnum::Accepted;
            $transactionInfo->verified_at = now();
            $transactionInfo->save();
            $message = [
                'status' => '200',
                'statusText' => 'موفق',
                'message' => __('message.transaction_verified'),
                'uuid' => $uuid,
                'transactionId' => $transaction_id,
                'referenceId' => $referenceId,
            ];
            return Redirect::route('payment.callback')->with('message', $message);
        } catch (InvalidPaymentException $exception) {
            $transactionInfo->status = PaymentStatusEnum::Rejected;
            $transactionInfo->save();
            $errorMessage = $exception->getMessage();
            $message = [
                'status' => '402',
                'statusText' => 'خطا',
                'message' => __('message.transaction_not_verified'),
                'error' => $errorMessage,
                'uuid' => $uuid,
                'transactionId' => $transaction_id,
            ];
            return Redirect::route('payment.callback')->with('message', $message);
        }
    }

    public function callback()
    {
        return view('payment.callback');
    }
}
