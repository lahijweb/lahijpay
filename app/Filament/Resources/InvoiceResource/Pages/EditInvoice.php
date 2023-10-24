<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function afterSave(): void
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
