<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Gateway;
use App\Models\Link;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LinkController extends Controller
{
    public function index(Link $link)
    {
        $message = $this->validateLink($link);
        if ($message)
            return View::make('error')->with('message', $message);

        $drivers = Gateway::active()->get();
        return view('link.link', compact(['drivers', 'link']));
    }

    public function store(StoreLinkRequest $request, Link $slug)
    {
        $message = $this->validateLink($slug);
        if ($message)
            return View::make('error')->with('message', $message);

        $paymentService = new PaymentService();
        $data = $this->preparePaymentData($request, $slug);
        return $paymentService->store((object)$data);
    }

    private function validateLink(Link $link)
    {
        if (!$link->is_active)
            return $this->errorMessage(__('message.link_not_active'));

        if ($link->is_scheduled) {
            $currentTime = now();
            if ($currentTime <= $link->start_date || $currentTime >= $link->end_date) {
                $message = __('message.link_is_schedule_error', [
                    'start_date' => verta($link->start_date),
                    'end_date' => verta($link->end_date),
                ]);
                return $this->errorMessage($message);
            }
        }

        if ($link->max_uses) {
            $used = $link->payments()->accepted()->count();
            if ($used >= $link->max_uses)
                return $this->errorMessage(__('message.link_max_uses_error'));
        }

        return null;
    }

    private function preparePaymentData(Request $request, Link $slug)
    {
        return [
            'driver' => $request->driver,
            'payable_type' => get_class($slug),
            'payable_id' => $slug->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'amount' => $slug->amount ?? $request->amount,
        ];
    }

    private function errorMessage($message)
    {
        return [
            'status' => '402',
            'statusText' => 'خطا',
            'message' => $message,
        ];
    }
}
