<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\VerifyController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/', [PaymentController::class, 'store'])->name('payment.store');
Route::any('callback/{uuid}', [VerifyController::class, 'verify'])->name('payment.verify')->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/callback', [VerifyController::class, 'callback'])->name('payment.callback');

Route::get('/link/{link}', [LinkController::class, 'index'])->name('link.index');
Route::post('/link/{slug}', [LinkController::class, 'store'])->name('link.store');

Route::get('/product/{product}', [ProductController::class, 'index'])->name('product.index');
Route::post('/product/{slug}', [ProductController::class, 'store'])->name('product.store');

Route::get('/invoice/{invoice}', [InvoiceController::class, 'index'])->name('invoice.index');
Route::post('/invoice/{invoice}', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/invoice/print/{invoice}', [InvoiceController::class, 'print'])->name('invoice.print');
