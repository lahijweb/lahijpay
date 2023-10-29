<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatusEnum;
use App\Models\Gateway;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    public function index(Invoice $invoice)
    {
        $message = $this->validateInvoice($invoice);
        if ($message)
            return View::make('error')->with('message', $message);

        $drivers = Gateway::active()->get();
        return View::make('invoice.invoice', compact('drivers', 'invoice'));
    }

    public function store(Request $request, Invoice $invoice)
    {
        $message = $this->validateInvoice($invoice);
        if ($message)
            return View::make('error')->with('message', $message);

        $paymentService = new PaymentService();
        $data = $this->preparePaymentData($request, $invoice);
        return $paymentService->store((object)$data);
    }

    private function validateInvoice(Invoice $invoice)
    {
        if ($invoice->status != InvoiceStatusEnum::Unpaid)
            return $this->errorMessage(__('message.invoice_error'));

        return null;
    }

    private function preparePaymentData(Request $request, Invoice $invoice)
    {
        return [
            'driver' => $request->driver,
            'payable_type' => get_class($invoice),
            'payable_id' => $invoice->id,
            'first_name' => $invoice->customer->first_name,
            'last_name' => $invoice->customer->last_name,
            'email' => $invoice->customer->email,
            'mobile' => $invoice->customer->mobile,
            'amount' => $invoice->total,
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

    public function print(Invoice $invoice)
    {
        $message = $this->validateInvoice($invoice);
        if ($message)
            return View::make('error')->with('message', $message);
        $invoice->load('products');
        return View::make('invoice.print', compact('invoice'));
    }
}
