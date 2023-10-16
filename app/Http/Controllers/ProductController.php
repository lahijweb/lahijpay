<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Order;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Product $product)
    {
        if ($product->is_active != true) {
            $message = [
                'status' => '402',
                'statusText' => 'خطا',
                'message' => __('message.link_not_active'),
            ];
            return view('error')->with('message', $message);
        }
        if ($product->is_scheduled) {
            $current_time = date('Y-m-d H:i:s');
            if ($current_time < $product->start_date || $current_time > $product->end_date) {
                $message = [
                    'status' => '402',
                    'statusText' => 'خطا',
                    'message' => __('message.link_is_schedule_error'),
                    'data' => [
                        'اعتبار لینک از تاریخ ' . verta($product->start_date) . ' تا تاریخ ' . verta($product->end_date) . ' می‌باشد.'
                    ]
                ];
                return view('error')->with('message', $message);
            }
        }
        // todo check qty
        $drivers = Gateway::active()->get();
        return view('product.product', compact(['drivers', 'product']));
    }

    public function store(Request $request, Product $slug)
    {
        $newOrder = Order::create([
            'product_id' => $slug->id,
            'qty' => 1,
            'total_price' => $slug->price,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'province' => $request->province,
            'city' => $request->city,
            'address' => $request->address,
            'zip' => $request->zip,
            'status_id' => 1,
        ]);
        $paymentService = new PaymentService();
        $data = [
            'driver' => $request->driver,
            'payable_type' => get_class($newOrder),
            'payable_id' => $newOrder->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'amount' => $slug->price,
        ];
        return $paymentService->store((object)$data);
    }
}
