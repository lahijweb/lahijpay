<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Gateway;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function index()
    {
        $drivers = Gateway::active()->get();
        return view('payment.payment', compact('drivers'));
    }

    public function store(StorePaymentRequest $request)
    {
        $paymentService = new PaymentService();
        $data = [
            'driver' => $request->driver,
            'payable_type' => get_class(new Payment()),
            'payable_id' => null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'amount' => $request->amount,
        ];
        return $paymentService->store((object)$data);
    }

}
