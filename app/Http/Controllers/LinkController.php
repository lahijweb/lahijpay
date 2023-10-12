<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Gateway;
use App\Models\Link;
use App\Services\PaymentService;

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
            $used = $link->payments()->accepted()->count();
            if ($used >= $link->max_uses) {
                $message = [
                    'status' => '402',
                    'statusText' => 'خطا',
                    'message' => __('message.link_max_uses_error'),
                ];
                return view('error')->with('message', $message);
            }
        }
        $drivers = Gateway::active()->get();
        return view('link.link', compact(['drivers', 'link']));
    }

    public function store(StoreLinkRequest $request, Link $slug)
    {
        $paymentService = new PaymentService();
        $data = [
            'driver' => $request->driver,
            'payable_type' => get_class($slug),
            'payable_id' => $slug->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'amount' => $slug->amount ?? $request->amount,
        ];
        return $paymentService->store((object)$data);
    }
}
