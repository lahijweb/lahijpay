<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Gateway;
use App\Models\Link;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment as Driver;

class LinkController extends Controller
{
    public function index(Link $link)
    {
        if ($link->is_active != true) {
            $message = [
                'status' => '402',
                'statusText' => 'خطا',
                'message' => __('message.link_not_active'),
            ];
            return view('error')->with('message', $message);
        }
        if ($link->is_scheduled) {
            $current_time = date('Y-m-d H:i:s');
            if ($current_time < $link->start_date || $current_time > $link->end_date) {
                $message = [
                    'status' => '402',
                    'statusText' => 'خطا',
                    'message' => __('message.link_is_schedule_error'),
                    'data' => [
                        'اعتبار لینک از تاریخ ' . verta($link->start_date) . ' تا تاریخ ' . verta($link->end_date) . ' می‌باشد.'
                    ]
                ];
                return view('error')->with('message', $message);
            }
        }
        if ($link->max_uses) {
            // todo max use
        }
        $drivers = Gateway::active()->get();
        return view('link.link', compact(['drivers', 'link']));
    }

    public function store(StorePaymentRequest $request, Link $slug)
    {
        $gatewayInfo = Gateway::where('driver', $request->driver)->first();
        if (is_null($gatewayInfo))
            return Redirect::route('link.index')->withErrors(__('message.gateway_invalid'));
        $invoice = new Invoice();
        $invoice->amount($request->amount);
        return Driver::via($request->driver)
            ->config($gatewayInfo->config)
            ->callbackUrl(url('/callback/' . $invoice->getUuid()))
            ->purchase($invoice, function ($driver, $transactionId) use ($invoice, $request, $gatewayInfo, $slug) {
                $slug->payments()->create([
                    'uuid' => $invoice->getUuid(),
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'amount' => $request->amount,
                    'gateway_id' => $gatewayInfo->id,
                    'transactionid' => $transactionId
                ]);
            })->pay()->render();
    }
}
