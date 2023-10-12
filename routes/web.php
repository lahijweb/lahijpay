<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\VerifyController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/', [PaymentController::class, 'store'])->name('payment.store');
Route::any('callback/{uuid}', [VerifyController::class, 'verify'])->name('payment.verify')->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/callback', [VerifyController::class, 'callback'])->name('payment.callback');

Route::get('/link/{link}', [LinkController::class, 'index'])->name('link.index');
Route::post('/link/{slug}', [LinkController::class, 'store'])->name('link.store');
