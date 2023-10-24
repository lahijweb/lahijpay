<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uuid'] = (string)Str::uuid();
        $data['amount'] = 0;
        $data['total'] = 0;
        return $data;
    }

    protected function afterCreate(): void
    {
        $products = $this->record->products;
        $amount = $discount = $tax = 0;
        foreach ($products as $product) {
            $amount += $product->qty * $product->price;
            $discount += $product->discount;
            $tax += $this->calculateTax($product);
        }
        $total = $amount - $discount + $tax;
        $this->record->amount = $amount;
        $this->record->discount = $discount;
        $this->record->tax = $tax;
        $this->record->total = $total;
        $this->record->save();
    }

    protected function calculateTax(object $product): int
    {
        $total = $product->qty * $product->price;
        $total -= $product->discount;
        $total *= ($product->tax / 100);
        return $total;
    }
}
