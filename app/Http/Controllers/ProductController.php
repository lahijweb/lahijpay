<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatusEnum;
use App\Http\Requests\StoreProductRequest;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{
    public function index(Product $product)
    {
        $message = $this->validateProduct($product);
        if ($message)
            return View::make('error')->with('message', $message);

        $drivers = Gateway::active()->get();
        return View::make('product.product', compact('drivers', 'product'));
    }

    public function store(StoreProductRequest $request, Product $slug)
    {
        $message = $this->validateProduct($slug);
        if ($message)
            return View::make('error')->with('message', $message);

        $newOrder = $this->createOrder($request, $slug);
        $paymentService = new PaymentService();
        $data = $this->preparePaymentData($request, $newOrder, $slug);
        return $paymentService->store((object)$data);
    }

    private function validateProduct(Product $product)
    {
        if (!$product->is_active)
            return $this->errorMessage(__('message.product_not_active'));

        if ($product->status != ProductStatusEnum::Published)
            return $this->errorMessage(__('message.product_not_published'));

        if ($product->is_scheduled) {
            $current_time = now();
            if ($current_time < $product->start_date || $current_time > $product->end_date) {
                $message = __('message.product_is_schedule_error', [
                    'start_date' => verta($product->start_date),
                    'end_date' => verta($product->end_date),
                ]);
                return $this->errorMessage($message);
            }
        }

        if (!$product->isProductForSale())
            return $this->errorMessage(__('message.product_is_qty_error'));

        return null;
    }

    private function createOrder(Request $request, Product $slug)
    {
        return Order::create([
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
    }

    private function preparePaymentData(Request $request, Order $newOrder, Product $slug)
    {
        return [
            'driver' => $request->driver,
            'payable_type' => get_class($newOrder),
            'payable_id' => $newOrder->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'amount' => $slug->price,
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

